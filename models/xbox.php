<?php
require ('public/php/simple_html_dom.php');
/**
 * Scrapes Game Card Info
 */
class getCard {
	private $gametag;
	private $data = array();
	function __construct($tag) {
	if($tag=="" || $tag == "%20"){
		return false;
	}
	else{
		$this->gametag = $tag;
	}
	}
	public function getCard(){
		return $this->gameCard();
	}
	private function fetchData($size, $url){
		$mh = curl_multi_init();
  		$handles = array();
		for($i=0;$i<$size;$i++){
		$c = curl_init();
		curl_setopt_array($c, array(
		CURLOPT_URL =>$url[$i],
		CURLOPT_HEADER=> false,
		CURLOPT_TIMEOUT=> 30,
		CURLOPT_RETURNTRANSFER=>true 
		));
		curl_multi_add_handle($mh,$c);
		$handles[] = $c;
		}
		$flag=null;
		do {
    	curl_multi_exec($mh,$flag);
		} while ($flag > 0);
		for($i = 0; $i < $size;$i++){
			$content[$i] =  curl_multi_getcontent ($handles[$i]);
			curl_multi_remove_handle($mh,$handles[$i]);
		}
		curl_multi_close($mh);
		return $content;
	}
	private function gameCard(){
	$url = array();
	$url[0] = CARD_URL_PRE . $this->gametag . CARD_URL_SUF;
	$url[1] = XBOX_LIVE . $this->gametag;
	$data = $this->fetchdata(2, $url);
	$html = str_get_html($data[0]);
	$profile = str_get_html($data[1]);
	$GamerScore = $html->find('#Gamerscore',0);
	$GamerScore = $GamerScore->plaintext;
	if($GamerScore == "--"){
		return "error 404";
	}
	$this->data['GamerScore'] = $GamerScore;
	$this->data['Location'] = $this->getTagInfo('Location', $html);
	$this->data['Motto'] = $this->getTagInfo('Motto', $html);
	$this->data['Name'] = $this->getTagInfo('Name', $html);
	$this->data['Bio'] = $this->getTagInfo('Bio', $html);
	$this->Rep($html);
	$this->getGTag($html);
	$this->Last5($html);
	$this->memberShipType($html);
	$this->Avatars();
	$this->status($profile);
	return $this->data;
	}
	private function Avatars(){
		$this->data['LargeAvatar'] = CARD_AVATAR . $this->gametag . CARD_AVATAR_LARGE;
		$this->data['MediumAvatar'] = CARD_AVATAR . $this->gametag . CARD_AVATAR_MEDIUM;
		$this->data['SmallAvatar'] = CARD_AVATAR . $this->gametag . CARD_AVATAR_SMALL;
	}
	private function memberShipType(&$html){
		if(sizeof($html->find('.Gold')) > 0){
			$this->data['Gold']=true;
		}
		else {
			$this->data['Gold']=false;
		}
	}
	private function getTagInfo($tag, &$html){
		$data = $html->find('#'.$tag,0);
		$data = $data->plaintext;
		return $data;
	}
	private function getGTag(&$html){
		$data = $html->find('#Gamertag',0);
		$this->data['Gamertag'] = $data->plaintext;
		$this->data['profile'] = $data->href;
		return $data;
	}
	private function Rep(&$html){
		$count = 0;
		$RepContainer = $html->find('.RepContainer',0)->children();
		foreach ($RepContainer as $stars) {
			if($stars->class == "Star Full"){
				$count++;
			}
		}
		$this->data['Rep'] = $count;
	}
	// replace with all games played with large pics
	private function Last5(&$html){
		$data = array();
		$list = $html->find('#PlayedGames',0);
		$count = 0;
			foreach ($list->find('li') as $game) {
				if($game->class == "Unplayed")
					continue;
				$gameinfo = $game->children(0);
				$details = array();
				$details['Title'] = $gameinfo->find('.Title',0)->plaintext;
				$details['LastPlayed'] = $gameinfo->find('.LastPlayed',0)->plaintext;
				$details['EarnedGamerscore'] = $gameinfo->find('.EarnedGamerscore',0)->plaintext;
				$details['AvailableGamerscore'] = $gameinfo->find('.AvailableGamerscore',0)->plaintext;
				$details['EarnedAchievements'] = $gameinfo->find('.EarnedAchievements',0)->plaintext;
				$details['AvailableAchievements'] = $gameinfo->find('.AvailableAchievements',0)->plaintext;
				$details['PercentageComplete'] = $gameinfo->find('.PercentageComplete',0)->plaintext;
				$details['GameThumb'] = $gameinfo->find('img',0)->src;
				$details['TID'] = $this->findTid($gameinfo->href);
				$tid = strtoupper(dechex($details['TID']));
				$details['LargeBoxArt']  = 'http://tiles.xbox.com/consoleAssets/' . $tid  . '/en-US/largeboxart.jpg';
				$details['SmallBoxArt'] = 'http://tiles.xbox.com/consoleAssets/' . $tid  . '/en-US/smallboxart.jpg';
				$details['Market']  = 'http://marketplace.xbox.com/en-US/Title/' . $tid;
				$details['Compare']  = 'http://live.xbox.com/en-US/Activity/Details?titleId=' . $details['TID']. '&compareTo=' . $this->gametag;
				$data[$count] = $details;
				$count++;
			}
		$this->data['Games']=$data;
		
	}
	private function findTid($string) {
		$tid = parse_url($string);
		$tid = explode('&', html_entity_decode($tid['query']));
		$tid = explode('=', $tid['0']);
		return $tid['1'];
}
	private function status(&$html){
		$data = array();
		$presence = $html->find('.presence',0)->plaintext;
		if(strstr($presence,"Online")){
			$data['Online'] = "true";
			$data['Activity'] = substr($presence,6);
		}
		else{
			$data['Online'] = "false";
			$data['Activity'] = $presence;
		}
		$this->data['status'] = $data;
		
	}
}

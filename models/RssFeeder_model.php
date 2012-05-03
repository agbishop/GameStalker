<?php
class RssFeeder_Model extends Model
{
	public $AllRss = " ";
public function __construct()
	{
		parent::__construct();
		
	}
	public function getRSS(){
		Session::init();
		$id = Session::get('id');
		$stat = $this->db->prepare("SELECT Rss FROM ops WHERE User=:id");
		$stat->execute(array(
			':id' => $id
		));
		$RssOps= $stat->fetch();
		$RssOps = json_decode($RssOps['Rss']);
		$urls = array();
		$ps3 =$RssOps->ps3->rss;
		$xbox = $RssOps->xbox->rss;
		$pc = $RssOps->pc->rss;
		if($ps3->ign == 1){
			$urls[] = IGN_PS3;
			//$ps3->ign = $this->getFeed(IGN_PS3);
		} 
		if($xbox->ign == 1){
			$urls[] = IGN_Xbox;
			//$xbox->ign = $this->getFeed(IGN_Xbox);
		} 
		if($pc->ign == 1){
			$urls[] = IGN_PC;
			//$pc->ign = $this->getFeed(IGN_PC);
		} 
		//
		if($ps3->gs == 1){
			$urls[] = GS_PS3;
			//$ps3->gs = $this->getFeed(GS_PS3);
		} 
		if($xbox->gs== 1){
			$urls[] = GS_Xbox;
			//$xbox->gs = $this->getFeed(GS_Xbox);
		} 
		if($pc->gs == 1){
			$urls[] = GS_PC;
			//$pc->gs = $this->getFeed(GS_PC);
		} 
		//
		if($ps3->up == 1){
			$urls[] = UP_PS3;
			//$ps3->up = $this->getFeed(UP_PS3);
		} 
		if($xbox->up == 1){
			$urls[] = UP_Xbox;
			//$xbox->up = $this->getFeed(UP_Xbox);
		} 
		if($pc->up == 1){
			$urls[] = UP_PC;
			//$pc->up = $this->getFeed(UP_PC);
		}
		$size = sizeof($urls);
		if($size == 0){
			echo "false";
			return false;
		}
		//print_r($this->make_content($this->getFeed($size,$urls),$RssOps));
		$this->make_content($this->getFeed($size,$urls),$RssOps);
		echo $this->AllRss;
	}
	public function getFeed($size, $url){
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
	public function make_content($content, $rss){
		$i = 0;
		$ps3 =$rss->ps3->rss;
		$xbox = $rss->xbox->rss;
		$pc = $rss->pc->rss;
		if($ps3->ign == 1){
			//print_r(new SimpleXMLElement($content[$i++]));
			$ps3->ign = $this->getImages($content[$i++], "psn");
		} 
		if($xbox->ign == 1){
			$xbox->ign =$this->getImages($content[$i++], "xbox");
		} 
		if($pc->ign == 1){
			$pc->ign = $this->getImages($content[$i++], "pc");
		} 
		//
		if($ps3->gs == 1){
			$ps3->gs =$this->getImages($content[$i++], "psn");
		} 
		if($xbox->gs== 1){
			$xbox->gs = $this->getImages($content[$i++], "xbox");
		} 
		if($pc->gs == 1){
			$pc->gs = $this->getImages($content[$i++], "pc");
		} 
		//
		if($ps3->up == 1){
			$ps3->up = $this->getImages($content[$i++], "psn");
		} 
		if($xbox->up == 1){
			$xbox->up =$this->getImages($content[$i++], "xbox");
		} 
		if($pc->up == 1){
			$pc->up = $this->getImages($content[$i++], "pc");
		}
		return $rss;
	}
	public function getImages($content, $type){
		error_reporting(E_ALL ^ E_NOTICE);
	$allPics= "";
	$stuff = new SimpleXMLElement($content);
	$counter = 0;
	foreach($stuff->channel->item as $itemNum => $item){
		if($counter == 11)
			break;
		$result = null;
		$tempRes =array();
		preg_match_all('/<img[^>]+>/i',$item->description, $result); 
		if($result[0][0] != "" || $result[0][0] != null)
			preg_match('/src=("[^"]*")/i',$result[0][0], $tempRes);
		else 
			$tempRes[1] = " ";
		//$allPics[$counter]['pic'] = str_replace('"','',$tempRes[1]);
		//$allPics[$counter]['title'] = $item->title[0];
		//$allPics[$counter]['link'] = $item->link[0];
		//$counter++;
		// my RSS is generated HTML, planned it out first then copy paste to php and fil in, okay?
		// tell me when i can move on
		$this->AllRss .= "<div class=\"contentBox item rss ".$type."\" style=\"cursor:pointer;opacity:0;background-color:black;width:300px;height:150px;overflow:hidden;background-image:url('".str_replace('"','',$tempRes[1])."');background-size:contain;background-repeat:no-repeat;background-position:center;\" >".
		"<div style=\"width: 300px;height: 50px;color: white;bottom: 0px;position: absolute;background: rgba(0, 0, 0, .7); text-align:center;font-family:venus;font-size:11px\">".$item->title[0]."</div>".
		"<a style=\"display:block;width:300px;height:150px\" target=\"_blank \"href=\"".$item->link[0]."\"> </a></div>";
		$counter++;
	}
	}

}

<?php
class RssFeeder_Model extends Model
{

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
		echo json_encode($this->make_content($this->getFeed($size,$urls),$RssOps));
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
	protected function make_content($content, $rss){
		$i = 0;
		$ps3 =$rss->ps3->rss;
		$xbox = $rss->xbox->rss;
		$pc = $rss->pc->rss;
		if($ps3->ign == 1){
			$ps3->ign = new SimpleXMLElement($content[$i++]);
		} 
		if($xbox->ign == 1){
			$xbox->ign =new SimpleXMLElement($content[$i++]);
		} 
		if($pc->ign == 1){
			$pc->ign = new SimpleXMLElement($content[$i++]);
		} 
		//
		if($ps3->gs == 1){
			$ps3->gs = new SimpleXMLElement($content[$i++]);
		} 
		if($xbox->gs== 1){
			$xbox->gs = new SimpleXMLElement($content[$i++]);
		} 
		if($pc->gs == 1){
			$pc->gs = new SimpleXMLElement($content[$i++]);
		} 
		//
		if($ps3->up == 1){
			$ps3->up = new SimpleXMLElement($content[$i++]);
		} 
		if($xbox->up == 1){
			$xbox->up = new SimpleXMLElement($content[$i++]);
		} 
		if($pc->up == 1){
			$pc->up = new SimpleXMLElement($content[$i++]);
		}
		return $rss;
	}

}
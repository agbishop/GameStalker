<?php
class RssFeeder_Model extends Model
{

public function __construct()
	{
		parent::__construct();
		
	}
	public function getRSS(){
		echo json_encode(new SimpleXMLElement($this->getFeed('http://feeds.ign.com/ignfeeds/ps3?format=xml')));
	}
	public function getFeed($url){
		$c = curl_init();
		curl_setopt_array($c, array(
		CURLOPT_URL =>$url,
		CURLOPT_HEADER=> false,
		CURLOPT_TIMEOUT=> 10,
		CURLOPT_RETURNTRANSFER=>true 
		));
		$r = curl_exec($c);
		curl_close($c);
		return $r;
	}
}
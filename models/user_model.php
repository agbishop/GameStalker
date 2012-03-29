<?php

class User_Model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function ops()
	{
		Session::init();
		$id = Session::get('id');
		$sth = $this->db->prepare("SELECT XboxId,PsnId,SteamId FROM user WHERE UserId=:id");
		$sth->execute(array(
			':id' => $id
		));
		$gameIds= $sth->fetch();
		$stat = $this->db->prepare("SELECT Rss FROM ops WHERE User=:id");
		$stat->execute(array(
			':id' => $id
		));
		$RssOps= $stat->fetch();
		$RssOps = json_decode($RssOps['Rss']);
		$data = array('Ids'=>$gameIds, 'Rss'=>$RssOps);
		echo json_encode($data);
	}
 
	
}
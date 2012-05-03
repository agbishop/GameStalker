<?php

class Forum extends Controller {
		
	public function __construct() {
		parent::__construct();
	}
	
	function pullXboxGamesList(){
		$this->model->pullXboxGamesList();
	}
	
	function queryGame($gameID){
		$this->model->queryGame($gameID);
	}
	
	function loadDB_Hub($gameName, $gameID){
		$this->model->loadDB_Hub($gameName, $gameID);
	}
		
	function createThreadTable($gameID){
		$this->model->createThreadTable($gameID);
	}
	
	function addThread($gameID, $threadName, $category){
		$this->model->addThread($gameID, $threadName, $category);
	}
	
	function deleteThread($gameID, $tid){
		$this->model->deleteThread($gameID, $tid);
	}
	
	function addPost($gameID, $tid, $content, $author){
		$this->model->addPost($gameID, $tid, $content, $author);
	}
	
	function deletePost($gameID, $tid, $tpid){
		$this->model->deletePost($gameID, $tid, $tpid);
	}
	
}

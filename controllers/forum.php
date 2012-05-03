<?php

class Forum extends Controller {
		
	public function __construct() {
		parent::__construct();
	}
	
	function pullXboxGamesList(){
		$this->model->pullXboxGamesList();
	}
	
	function loadDB_Hub(){
		$this->model->loadDB_Hub();
	}
	
	function addThread(){
		$this->model->addThread();
	}
	
	function deleteThread(){
		$this->model->deleteThread();
	}
	
	function addPost(){
		$this->model->addPost();
	}
	
	function deletePost(){
		$this->model->deletePost();
	}
	
	function viewGameThreads(){
		$this->model->viewGameThreads();
	}
	
}

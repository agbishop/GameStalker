<?php

class Login extends Controller {

	function __construct() {
		parent::__construct();	
	}
	function run()
	{
		$this->model->run();
	}
	function username()
	{
	$this->model->usernameCheck();
	}
	function logout()
	{
	$this->model->logout();
	}
	function add()
	{
	$this->model->add();
	}
	function getPlats()
	{
	$this->model->getPlats();
	}
	function checkSession(){
	$this->model->cs();
	}
	function index() {
		$this->view->error();
		
	}

}
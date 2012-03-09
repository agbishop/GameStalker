<?php

class RssFeeder extends Controller {

	function __construct() {
		parent::__construct();	
	}
	function getRSS(){
		$this->model->getRSS();
	}

}
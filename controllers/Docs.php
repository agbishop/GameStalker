<?php

class Docs extends Controller {

	function __construct() {
		parent::__construct();
	}
	
	function Docspage() {
		$this->view->Doc();
	}

}
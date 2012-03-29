<?php

class User extends Controller {

	public function __construct() {
		parent::__construct();
		}
	function getOps()
	{
		$this->model->ops();
	}
	}


<?php

class View {

	function __construct() {
	}

	public function render($name, $noInclude = false)
	{
			require 'views/header.php';

	}
	public function error()

	{
			require 'views/error.php';

	}
	public function Doc()
	
	{
		require 'views/Doc.php';
		
	}
	

}
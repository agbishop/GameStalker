<?php

define('LIBS', 'libs/');
require 'config/database.php';
require 'config/constants.php';


// Also spl_autoload_register (Take a look at it if you like)
function __autoload($class) {
	if(file_exists(LIBS . $class .".php")){
		require LIBS . $class .".php";
	}
}



$app = new Bootstrap();

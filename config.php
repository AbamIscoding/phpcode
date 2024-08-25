<?php
define('DB_SERVER', '127.0.0.1'); //defines the ip address 
define('DB_USERNAME', 'anthony'); //defines the username to connect on database system
define('DB_PASSWORD', 'alcantara'); //defines the password to connect on database system
define('DB_NAME', 'itc-127-2b-2024'); //defines the MYSQL database to connect

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false){
	div("ERROR: Could not connect," . mysql_connect_error());
}
date_default_timezone_set("Asia/Manila");

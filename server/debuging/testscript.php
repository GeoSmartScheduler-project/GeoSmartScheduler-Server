<?php
ini_set('display_errors', 1);
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/utils/config.php'); 
require_once (__ROOT__.'/dbQuery/db_traces_functions.php');

$response = http_get("http://127.0.0.1/server/request.php?id_twt=327476318285484032", array("timeout"=>1), $info);
if ($response)
{
print_r($info);
}
else {
	echo "ERROR";
}
?>



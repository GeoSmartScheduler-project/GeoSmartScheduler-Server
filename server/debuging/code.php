<?php
//require_once '../dbQuery/db_connect.php';
/*$x = new DB_Connect();
$bdcon=$x->connect();
$x->close($bdcon);

*/

//require_once(__ROOT__.'/utils/config.php');
ini_set('display_errors', 1);
$root=dirname(dirname(__FILE__));
require_once($root.'/dbQuery/db_pendingTweets_functions.php');
//$expression = strtotime(date()"00:00:08",time());
var_dump($expression);
 	
?>
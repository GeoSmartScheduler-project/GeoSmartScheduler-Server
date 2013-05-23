<?php
ini_set('display_errors', 1);
require_once('utils/config.php'); 
require_once ('dbQuery/db_traces_functions.php');

$x = new DB_Traces_Functions();
$result=$x->getTraceOfTweets(0);
$row =$result->fetch_array();
printf ("AQUI %s (%s)\n", $row[0], $row[1]);
//mysqli_store_result();
//mysqli_fetch_array($results);
$result->free();

?>



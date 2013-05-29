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

	$data = array("num_tweets" => 3, "array_id_twt" => array("id0"=>1 , "id1"=>2 , "id2"=>3));
 	$json = json_encode($data);
 	var_dump($json);
 	var_dump("esto es deserializado");
 	//esquema para recuperar id tweets
 	$post=json_decode($json,true);
 	//var_dump($post);
 	$array_id_twt = $post['array_id_twt'];
 	for ($i=0; $i<$post['num_tweets']; $i++){
 		
 		echo "Me esta pidiendo el tweet con id = ".$array_id_twt['id'.$i];
 	}
 	
?>
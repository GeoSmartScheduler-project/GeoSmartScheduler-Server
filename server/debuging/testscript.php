<?php
ini_set('display_errors', 1);
 if ($metodo_unico)
 {
$ch = curl_init("http://127.0.0.1/server/request.php?id_twt=327476318285484032");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

$json=curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($http_status== "200")
{
		echo $json;
}
 }
 
 else{
 	/*
 	 * realizar un post con los idtwt
 	*/
 	
 	$data = array("num_tweets" => 3, "array_id_twt" => array("id0"=>1 , "id1"=>2 , "id2"=>3));
 	$data_string = json_encode($data);
 	
 	$ch = curl_init("http://127.0.0.1/server/request.php");
 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
 	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 	'Content-Type: application/json',
 	'Content-Length: ' . strlen($data_string))
 	);
 	
 	$result = curl_exec($ch);
 	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 	curl_close($ch);
 	if ($http_status== "200")
 	{
 		echo $json;
 	}
 	
 }
?>



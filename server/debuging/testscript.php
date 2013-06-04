<?php
ini_set('display_errors', 1);

$metodo_unico=false;
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
 	
 	$data = array("num_tweets" => 4, "array_id_twt" => array("id0"=>"327195146397552640" , "id1"=>"327191873926078464" , "id2"=>"327189338767097858", "id3"=>"327189303899865089"));
 	$data_string = json_encode($data);
 	
 	$ch = curl_init("http://127.0.0.1/server/request.php");
 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 	//curl_setopt($ch, CURLOPT_POST, true);
 	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 	'Content-Type: application/json',
 	'Content-Length: ' . strlen($data_string)));
 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
 	
 	
 	$json = curl_exec($ch);
 	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 	curl_close($ch);
 	if ($http_status== "200")
 	{
 		echo $json;
 	}
 	else{
 		echo $json;
 	}
 	
 }
?>



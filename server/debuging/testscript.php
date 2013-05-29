<?php
ini_set('display_errors', 1);
 
$ch = curl_init("http://127.0.0.1/server/request.php?id_twt=327476318285484032");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, URLOPT_RETURNTRANSFER, true);

$json=curl_exec($ch);
$http_status = curl_getinfo($feed, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($http_status== "200")
{
		return $json;
}
?>



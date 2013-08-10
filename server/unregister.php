<?php

if (isset($_GET)and isset($_GET["gcm_regid"])){
	$dbGCM = new DB_GCM_Functions();
	if($dbGCM->deleteUserInfo($_GET["gcm_regid"])){
		error_log("User with registration id ".$_GET["gcm_regid"]." has been deleted");
		echo header("HTTP/1.0 200 OK");
	}
	else{
		echo header("HTTP/1.1 404 Not Found");
	}
	
}
else{
		echo header("HTTP/1.1 404 Not Found");
	}
?>
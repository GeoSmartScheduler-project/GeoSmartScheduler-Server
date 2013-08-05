<?php

// response json
$json = array();

/**
 * Registering a user device
 * Store reg id in users table
 */

if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["regId"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $gcm_regid = $_POST["regId"]; // GCM Registration ID
    // Store user details in db
    $root= dirname(__FILE__);
    require_once ($root.'/dbQuery/db_GCM_functions.php');
    require_once ($root.'/utils/GCM.php');

    $db = new DB_GCM_Functions();
    $gcm = new GCM();

    $res = $db->storeUser($name, $email, $gcm_regid);
    if ($res)
	{
		echo header("HTTP/1.0 200 OK");
	}
	else {
		echo header("HTTP/1.1 404 Not Found");
	}

} else { 
    // No user data 
    echo header("HTTP/1.1 404 Not Found");
}
?>
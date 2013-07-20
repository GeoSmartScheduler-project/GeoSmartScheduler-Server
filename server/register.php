<?php

// response json
$json = array();

/**
 * Registering a user device
 * Store reg id in users table
 */

//TODO: devolver un error si no se ha podido registrar el usuario en el servidor para abortar en la app continue
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
    //TODO: eliminar el envio del primer mensaje de you have sucessfully singned in y enviarlo en el cuerpo de la respuesta post
if ($res)
	{
    	//$registatoin_ids = array($gcm_regid);
    	//$message = array("message" => "You have successfully signed in");
    	//$result = $gcm->send_notification($registatoin_ids, $message);
		echo header("HTTP/1.0 200 OK");

	}
	else {
		echo header("HTTP/1.1 404 Not Found");
	}

} else {
    // user details missing
}
?>
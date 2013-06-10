<?php

/*
 * Used in the first version to send sms attached to the notification through a html interface
 */
if (isset($_GET["regId"]) && isset($_GET["message"])) {
    $regId = $_GET["regId"];
    $message = $_GET["message"];
     $root= dirname(__FILE__);
     require_once ($root.'/utils/GCM.php');
    
    $gcm = new GCM();

    $registatoin_ids = array($regId);
    $message = array("message" => $message);

    $result = $gcm->send_notification($registatoin_ids, $message);

    echo $result;
}
?>

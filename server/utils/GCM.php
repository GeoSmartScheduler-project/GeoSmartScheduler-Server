<?php

/**
 * Description of GCM
 * This class contain methods to push notifications to a device through GCM Google service
 * @author Ravi Tamada
 * @author Modified by Alberto Garcia
 */
class GCM {

    // constructor
    function __construct() {
        
    }

    /**
     * Sending Push Notification with enclouser data
     */
    public function send_notification($registation_ids, $message) {
        // include config
        $root=dirname(dirname(__FILE__));
		require_once ($root.'/utils/config.php'); 

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        //borrar $message= array("message"=> "puta" ,"size"=>"22");
        $fields = array(
            'registration_ids' =>$registation_ids ,
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//var_dump($fields);
		$xd=json_encode($fields);
		//var_dump($xd);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        //TODO: ADD control of error codes from gcm to avoid problems in the future
        $result = curl_exec($ch);
        $success = null;
        if ($result == false) {
            die('Curl failed: ' . curl_error($ch));
        }
		else {
			$arrayResult = json_decode($result,true);
			if ($arrayResult["failure"]!="0"){
				$i=$arrayResult["results"];
				if ($i["error"]!=null){
					error_log($i["error"]);
				}
				if($i["message_id"]!=null){
					$new_gcm_regid = $i["registration_id"];
					$db1= new DB_GCM_Functions();
					$db1->updateUserGCMid($registation_ids, $new_gcm_regid);
				}
				$success = false;
			}
			else{
				$success = true;
			}
		}
        // Close connection
        curl_close($ch);
        return  $success;
    }

 	/**
     * Sending Push Notification without extra data
     */
    public function send_sync_notification($registatoin_ids) {
        // include config
        $root=dirname(dirname(__FILE__));
		require_once($root.'/utils/config.php'); 

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registatoin_ids,
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        echo $result;
    }
    
}

?>

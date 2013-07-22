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
		require_once ($root.'/dbQuery/db_GCM_functions.php');

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
      	
        $fields = array(
            'registration_ids' =>$registation_ids,
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
        $result = curl_exec($ch);
        $success = null;
        if ($result == false) {
            die('Curl failed: ' . curl_error($ch));
        }
		else {
			//Control the response looking for failures or canonicals id to handle the errors
			$gcmResponse = json_decode($result,true);
			if ($gcmResponse["failure"]!="0" || $gcmResponse["canonical_ids"]!="0"){
				$gcmResults=$gcmResponse["results"];
				for ($i=0; $i<count($gcmResults); $i++){
					
					$arrayAux=$gcmResults[$i];
					if ($arrayAux["error"]!=null){
						error_log("Error during a gcm notification|reg_id:".$registation_ids[$i]."|error:".$arrayAux["error"]);
						$success = false;
					}
					if($arrayAux["message_id"]!=null && $arrayAux["registration_id"]!=null){
						$new_gcm_regid = $arrayAux["registration_id"];
						$db1= new DB_GCM_Functions();
						$db1->updateUserGCMid($registation_ids[$i], $new_gcm_regid);
						$success = true;
					}
				}
				
				
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

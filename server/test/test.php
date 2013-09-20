<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ERROR);
set_time_limit(0);
$root=dirname(dirname(__FILE__));
require_once($root.'/utils/config.php');
require_once($root.'/dbQuery/db_traces_functions.php');
require_once($root.'/dbQuery/db_pendingTweets_functions.php');
require_once($root.'/utils/utilsLog.php');
require_once($root.'/utils/GCM.php');
require_once($root.'/dbQuery/db_GCM_functions.php');

if(isset($_GET['trace']) && $_GET['trace']!=null){

	$id_trace = $_GET['trace'];
	$id_gpsFile =$_GET['gpsFile'];
	$gcm = new GCM();
	$log = new log();
	$dbGCM = new DB_GCM_Functions();
	//meterlo por un parametro de config file
	$result = $dbGCM->getLastUserInfo();
	$reg_id= mysqli_fetch_assoc($result);

	//create object to use pendingTweets functions
	$dbPendingTweetsFunctions = new DB_pendingTweets_Functions();
	$dbTracesFunctions = new DB_Traces_Functions();
	//Load trace to run the test
	$trace = $dbTracesFunctions->getTraceOfTweets($id_trace);
	$Trace_nmbTweets = mysqli_num_rows($trace);

	//If the notification_trace is empty or has les than 10 samples stop
	if ($Trace_nmbTweets <10){
		error_log("Error loading notifications_trace number ".$id_trace." to start the test");
		echo header("HTTP/1.1 404 Not Found");
		exit;
	}
	//Set initial "sleep_time" and variables
	$sleep_time = 0.0;
	$i=0;
	$tweet=null;
	$CurrentTwtId= null;
	$counter=0;
	//START
	error_log("TEST.PHP|START|Test of notification_trace number ".$id_trace." start");


	//Load each tweet of the trace is being used to run the test
	while ($tweet = $trace->fetch_assoc())
	{
		//wait sleep_time and then continue the loop
		//first time sleep_time is =0.0 and the loop does not sleep
		time_sleep_until(time()+$sleep_time);

		//send tweet to gcm with the size of the tweet attached
		$registration_ids= array ($reg_id["gcm_regid"]);
		$message= array("message"=> $tweet['id_twt'] ,"size"=>"".filesize($root.'/assets/send/file.txt')."", "gpsFile"=>"BusPath".$id_gpsFile.".txt");//$tweet['size']
		$success=$gcm->send_notification($registration_ids, $message);

		//If the response is true, the notification was successfully delivered and we can store it in the pending queue
		//Otherwise we stop the test
		if (!$success){
			//store tweet in pending tweets queue
			//$dbPendingTweetsFunctions->putPendingTweet($tweet['id_twt']);
			//Imposible to finish test
			error_log("TEST.PHP|Unable to finish the notification_trace number: ".$id_trace);
			echo header("HTTP/1.1 404 Not Found");
			exit;
		}
		else{
			$counter++;
			error_log("TEST.PHP| notification_trace".$id_trace." progress...".$counter."/".$Trace_nmbTweets);
		}

		//load next sleep_time
		$sleep_time = $tweet['time_to_next'];
		$CurrentTwtId=$tweet['id_twt'];
		$i++;
	}

	//Release memory of the trace and response OK
	$trace->free();
	echo header("HTTP/1.0 200 OK");
	error_log("TEST.PHP|Test of notification_trace number ".$id_trace." has finished");
	exit;
}
else {
	//Imposible to start test
	echo header("HTTP/1.1 404 Not Found");
	error_log("TEST.PHP|Unable to start test of notification_trace number ".$id_trace." has finished");
	exit;
}
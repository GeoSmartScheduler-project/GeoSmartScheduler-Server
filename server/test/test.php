<?php
ini_set('display_errors', 1);
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
$gcm = new GCM();
$log = new log();
$dbGCM = new DB_GCM_Functions();
//meterlo por un parametro de config file
$result = $dbGCM->getLastUserInfo();
$reg_id= mysqli_fetch_assoc($result);

//create object to use pendingTweets functions
$dbPendingTweetsFunctions = new DB_pendingTweets_Functions();
//Load trace to run the test
$dbTracesFunctions = new DB_Traces_Functions();
$trace = $dbTracesFunctions->getTraceOfTweets($id_trace);
$Trace_nmbTweets = mysqli_num_rows($trace);

//Set last tweet id "LTweetId"
//$LTweetId = $dbTracesFunctions->getLastIdTweet_TraceOfTweets($id_trace);

//initialating  "sleep_time" and variables
$sleep_time = 0.0;
$i=0;
$tweet=null;
$CurrentTwtId= null;
//Load each tweet of the trace is being used to run the test
while ($tweet = $trace->fetch_assoc())
{
	//wait sleep_time and then remain the loop
	//first time sleep_time is =0.0 and the loop does not sleep
	time_sleep_until(time()+$sleep_time);
	
	//send tweet to gcm with the size of the tweet attached  
	$registration_ids= array ($reg_id["gcm_regid"]);
	$message= array("message"=> $tweet['id_twt'] ,"size"=>$tweet['size']);
	$success=$gcm->send_notification($registration_ids, $message);
	//If the response is true, the notification was successfully delivered and we can store it in the pending queue
	//Otherwise we stop the test
	if ($success){
		//store tweet in pending tweets queue
		$dbPendingTweetsFunctions->putPendingTweet($tweet['id_twt']);
		//Log action of put in pending list
		$log->user("Trace nº".$id_trace."| Tweet has been sent and stored in pending queue | tweet_id = ".$tweet['id_twt'], "Alberto");		
	}
	else{
		//Imposible to start test
		error_log("Unable to finish the trace number: "+$id_trace);
		echo header("HTTP/1.1 404 Not Found");
		exit(-1);
	}
	//load next sleep_time
	$sleep_time = $tweet['time_to_next'];
	$CurrentTwtId=$tweet['id_twt'];
	$i++;
}	
//Release memory of the trace and response OK
$trace->free();
echo header("HTTP/1.0 200 OK");
}
else {
	//Imposible to start test
	echo header("HTTP/1.1 404 Not Found");
}
?>
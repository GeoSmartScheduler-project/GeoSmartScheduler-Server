<?php
ini_set('display_errors', 1);
set_time_limit(0);
$root=dirname(dirname(__FILE__));
require_once($root.'/utils/config.php'); 
require_once($root.'/utils/TimeFunctions.php');
require_once($root.'/dbQuery/db_traces_functions.php');


$idtwt = null;
$idnxt_twt=null;
$time1 = null;
$time2 = null;
$size1=null;
$period=0;
$num_rows=null;
$tweet_trace = array ('id_twt'=>null, 'idnxt_twt'=>null, 'trace'=>0, 'size'=>null, 'time_to_next'=>null);
$DBtraces = new DB_Traces_Functions();

$result=$DBtraces->getStartTime();
$row = $result->fetch_array();
$datein= $row[0];
$result=$DBtraces->getEndTime();
$row = $result->fetch_array();
$enddate=$row[0];
$trace=30;

while ($trace<31){

//load samples to create the notifications trace from tweet feed
$SetOfTweets =$DBtraces->getTraceOfTweets_byinterval($datein,"9");
//If there are no more than 10 notifications discard that trace 
$NumRows=mysqli_num_rows($SetOfTweets);
if ($NumRows<10&&$NumRows>90){
	if($NumRows==0){
		$date=date_sub(new DateTime($datein), new DateInterval('PT9M'));
		$datein= $date->format('Y-m-d H:i:s');
	}
	else{
	if ($NumRows==1){
		$tweet = $SetOfTweets->fetch_assoc();
		$datein=$tweet["created_at"];
		$idtwt = $tweet["id_twt"];
		$DBtraces->deleteRowTraceOfTweets($idtwt);
	}
	else{
			$tweet = $SetOfTweets->fetch_assoc();
			$datein=$tweet["created_at"];
			$idtwt = $tweet["id_twt"];				
	}
	}
}
else{
	//Generate table to store notifications trace	
$DBtraces->create_table_trace($trace);
while ($tweet = $SetOfTweets->fetch_assoc())
{
	
	if ($time1 == null)
	{
		//inizialize parameters for first tweet of the trace
		$time1 = $tweet["created_at"];
		$idtwt = $tweet["id_twt"];
		$size1 = $tweet["size"];
		
	}
	else
	{
		//get parameters for  next tweet of the trace
		$time2 = $tweet["created_at"];
		$idtwt_nxt = $tweet["id_twt"];
		//calculate period between tweets in seconds 
		$period = DateInterval_to_seconds(date_diff(date_create($time1), date_create($time2)));
		
		//storage values in array to insert in the table trace
		$tweet_trace["id_twt"]= $idtwt;
		$tweet_trace["idnxt_twt"]=$idtwt_nxt;
		$tweet_trace["size"]= $size1;
		$tweet_trace["time_to_next"]= $period;
		//Store tweet in the trace	
		$succes=$DBtraces->putTweetinTrace($trace, $tweet_trace["id_twt"], $tweet_trace["idnxt_twt"], $tweet_trace["time_to_next"] , $tweet_trace["size"], $tweet_trace["trace"]);
		if ($succes)
		{
		//echo  "Trace n�".$trace."| Tweet almacenado en trace | tweet_id = ".$tweet_trace["id_twt"]."| time_to_next =".$tweet_trace["time_to_next"]."\n";
		
		}
		// keep values for next round
		$time1 = $tweet["created_at"];
		$idtwt = $tweet["id_twt"];
		$size1 = $tweet["size"];
	}
	
}
$date=date_sub(new DateTime($datein), new DateInterval('PT9M'));
$datein= $date->format('Y-m-d H:i:s');
if ($enddate>$datein){
exit;}
$trace++;
}
//Release memory of the set of tweets 
$SetOfTweets->free();

}
?>


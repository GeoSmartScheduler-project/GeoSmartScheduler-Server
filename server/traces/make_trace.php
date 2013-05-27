<?php
ini_set('display_errors', 1);
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/utils/config.php'); 
require_once(__ROOT__.'/utils/TimeFunctions.php');
require_once (__ROOT__.'/dbQuery/db_traces_functions.php');


$idtwt = null;
$idnxt_twt=null;
$time1 = null;
$time2 = null;
$size1=null;
$period=0;
$num_rows=null;
$tweet_trace = array ('id_twt'=>null, 'idnxt_twt'=>null, 'trace'=>0, 'size'=>null, 'time_to_next'=>null);
//$tweet = array ('created_at'=>null, 'id_twt'=>null, 'text'=>null, 'size'=>null, 'time_to_next'=>null);
$DBtraces = new DB_Traces_Functions();
$datein = "2013-04-25 23:59:59";
$SetOfTweets =$DBtraces->getTraceOfTweets_byDate($datein);

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
		$succes=$DBtraces->putTweetinTrace($tweet_trace["trace"], $tweet_trace["id_twt"], $tweet_trace["idnxt_twt"], $tweet_trace["time_to_next"] , $tweet_trace["size"], $tweet_trace["trace"]);
		if ($succes)
		{
		echo  "Trace nº".$tweet_trace["trace"]."| Tweet almacenado en trace | tweet_id = ".$tweet_trace["id_twt"]."| time_to_next =".$tweet_trace["time_to_next"];
		}
		// keep values for next round
		$time1 = $tweet["created_at"];
		$idtwt = $tweet["id_twt"];
		$size1 = $tweet["size"];
	}
	
}
//Release memory of the set of tweets 
$SetOfTweets->free();

?>


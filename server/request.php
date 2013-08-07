<?php  
$root=dirname(__FILE__);
require_once ($root.'/utils/config.php'); 
require_once ($root.'/dbQuery/db_pendingTweets_functions.php');
require_once ($root.'/utils/utilsLog.php');

$array_id_twt=array();
$NumTweets=null;
$dbPending = new DB_pendingTweets_Functions();

if(isset($_GET['id_twt']) ) {
 
  $array_id_twt = $_GET['id_twt']; 
  $NumTweets=1;
  error_log("GET de request.php");

}
else {
	
	if (isset($_POST))
	{
		//Obtain the ids of the tweets as an associative array		
		$fp = fopen('php://input', 'r');
		$rawData = stream_get_contents($fp);
		$post=json_decode($rawData,true);
		$array_id_twt = $post['array_id_twt'];
		$NumTweets=$post['num_tweets'];
		error_log("valores recividos en el POST de request.php|number_tweets:".var_export($NumTweets,true));
	}
	
}

// Get tweet requested
  $result=$dbPending->getArrayPendingTweet($NumTweets,$array_id_twt);
  $tweets;
  $Arraytweets;
  $NumRows=mysqli_num_rows($result);
  if($NumRows) {
    while($tweets = mysqli_fetch_assoc($result)) {
      $Arraytweets[] = array('tweet_response'=>$tweets);
    }
  }
  mysqli_free_result($result);
  //create output to be sent in json to device
 if (!headers_sent($filename, $linenum))
 {
  if($NumRows) {
  	header('HTTP/1.1 200 OK');
    header('Content-type: application/json');
    echo json_encode($Arraytweets);
  }
  else {
    echo header("HTTP/1.1 404 Not Found");
  }
 }
 else{
 	echo "Headers already sent in $filename on line $linenum\n" .
 	exit;
 } 

?>
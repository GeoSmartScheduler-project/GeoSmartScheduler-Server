<?php  
/* require the user as the parameter */
//http://localhost:8080/sample1/webservice1.php?user=1
ini_set('display_errors', 1);
//$root= dirname(__FILE__);
$root=dirname(__FILE__);
require_once ($root.'/utils/config.php'); 
require_once ($root.'/dbQuery/db_pendingTweets_functions.php');
require_once ($root.'/utils/utilsLog.php');


$dbPending = new DB_pendingTweets_Functions();
//isset($_GET['id_twt'])
if(isset($_GET['id_twt']) ) {
  
  //$format = strtolower($_GET['format']) == 'json' ? 'json' : 'xml'; //xml is the default
  //TODO:usar un array the id_twt para soportar peticiones multiples
  $id_twt = $_GET['id_twt']; 
  // Get tweet requested
  $result=$dbPending->getPendingTweet($id_twt);
  /* create  array of the records */
  $tweets = array();
  $NumTweets=mysqli_num_rows($result);
  if($NumTweets) {
    while($tweets = mysqli_fetch_assoc($result)) {
      $Arraytweets[] = array('tweet_response'=>$tweets);
    }
  }
  //TODO:create output to be sent in json to device
  //algorithm START
 if (!headers_sent($filename, $linenum))
 {
  if($NumTweets) {
  	header('HTTP/1.1 200 OK');
    header('Content-type: application/json');
    echo json_encode(array('tweet'=>$Arraytweets));
  }
  else {
    echo header("HTTP/1.1 404 Not Found");
  }
 }
 else{
 	echo "Headers already sent in $filename on line $linenum\n" .
 	exit;
 }
  //algorithm END
  
  //TODO:send the info to the device
  
  
}
?>
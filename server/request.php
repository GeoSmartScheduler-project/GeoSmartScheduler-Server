<?php  
/* require the user as the parameter */
//http://localhost:8080/sample1/webservice1.php?user=1
ini_set('display_errors', 1);
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/utils/config.php'); 
require_once (__ROOT__.'/dbQuery/db_pendingTweets_functions.php');
require_once (__ROOT__.'/utils/utilsLog.php');


$dbPending = new DB_pendingTweets_Functions();

if(isset($_GET['id_twt']) ) {
  
  //$format = strtolower($_GET['format']) == 'json' ? 'json' : 'xml'; //xml is the default
  //TODO:usar un array the id_twt para soportar peticiones multiples
  $id_twt = intval($_GET['id_twt']); 
  // Get tweet requested
  $result=$dbPending->getPendingTweet($id_twt);
  /* create  array of the records */
  $tweets = array();
  $NumTweets=mysqli_num_rows($result);
  if($NumTweets) {
    while($tweets = mysqli_fetch_assoc($result)) {
      $Arraytweets[] = array('tweet_response'=>$tweet);
    }
  }
  //TODO:create output to be sent in json to device
  //algorithm START
  if($NumTweets) {
  	header('HTTP/1.1 200 OK');
    header('Content-type: application/json');
    echo json_encode(array('tweet'=>$Arraytweets));
  }
  else {
    echo header("HTTP/1.1 404 Not Found");
  }
  //algorithm END
  
  //TODO:send the info to the device
  
  
}
?>
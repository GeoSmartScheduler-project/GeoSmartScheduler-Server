<?php
//require_once '../dbQuery/db_connect.php';
/*$x = new DB_Connect();
$bdcon=$x->connect();
$x->close($bdcon);

*/

//require_once(__ROOT__.'/utils/config.php');
ini_set('display_errors', 1);
$root=dirname(dirname(__FILE__));
require_once($root.'/dbQuery/db_pendingTweets_functions.php');
$dbPending = new DB_pendingTweets_Functions();
$id_twt=intval(327476318285484032,10);
$result=$dbPending->getPendingTweet((string)$id_twt);
$NumTweets=mysqli_num_rows($result);
if($NumTweets) {
	while($tweets = mysqli_fetch_assoc($result)) {
		echo 'tweet_response'.$tweets ;
	}
}
?>
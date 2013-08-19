<?php
ini_set('display_errors', 1);
$root=dirname(dirname(__FILE__));
require_once($root.'/utils/config.php');
require_once($root.'/utils/TwitterAPIExchange.php');
require_once($root.'/dbQuery/db_twitter_functions.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => OAUTH_ACCESS_TOKEN,
    'oauth_access_token_secret' => OAUTH_ACCESS_TOKEN_SECRET,
    'consumer_key' => CONSUMER_KEY,
    'consumer_secret' => CONSUMER_SECRET
);
/*
 * Create variables
 */
$dbtweet = array(
'created_at'=>null, 'id'=>null, 'text'=>null);
$max_id = MAX_ID;
//$since_id = SINCE_ID;
$json_response = array();
/** Perform a GET request and store the response **/
/** Note: Set the GET field BEFORE calling buildOauth(); **/
/** Note: The parameter COUNT_TWEETS is used to set the number of requested tweets to the API **/
//$getfield = '?count='+COUNT_TWEETS+'&max_id='+$max_id+'&trim_user=1&exclude_replies=1&contributor_details=1';
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';
$dbTwitterFunctions = new DB_Twitter_Functions();
$dbTwitterFunctions->createTable();
/*
 * Collect tweets from the Twitter API
 */
for ($i=0; $i<5; $i++)
{
	/* Set the options of the request to the API*/
	$getfield = '?q=%23empleo&count=100';
	/*
	 * Add the option max_id to the request to retrieve tweets from the last tweet stored before
	 */
	if ($max_id != null && $max_id != 0)
	{
		/*
		 * Susbtracts 1 from the last max_id to avoid redundant tweets
		 */
		$max_id = (int) $max_id-1;
		$getfield = $getfield.'&max_id='.$max_id;
	}
	/*else {
	 if ($since_id != null && $since_id != 0)
	 {
		$getfield = $getfield.'&since_id='.$since_id;
		}
		}*/

	$twitter = new TwitterAPIExchange($settings);
	$twitter->setGetfield($getfield);
	$twitter->buildOauth($url, $requestMethod);

	try {
		$json_response=$twitter->performRequest();

	} catch (Exception $e) {
		error_log("PHP custome error:".$e->getMessage());
	}

	/*
	 * Store the obtained tweets feed in the database
	 * If the response is empty then the script is stoped
	 */
	$array_tweets = json_decode($json_response, true);
	if (!empty($array_tweets["statuses"]))
	{


		foreach ($array_tweets["statuses"] as $tweet)
		{
			$dbtweet["created_at"]= date("Y-m-d H:i:s",strtotime($tweet["created_at"]));
			$dbtweet["id"]= (int) $tweet["id"];
			$dbtweet["text"]=$tweet["text"];

			$result=$dbTwitterFunctions->storeTweet($dbtweet["created_at"], (int) $dbtweet["id"], $dbtweet["text"], strlen($dbtweet["text"]));
			/*
			 * If some error ocurred, we log it in the erro.log of the server
			 */
			if (!$result)
			error_log("PHP custome error: Impossible to store the tweet in the database, id_twt =".$dbtweet["id"]);

		}
		/*
		 * $max_id is a parameter used to request the Twitter API tweets from the last tweet stored
		 */
		$max_id =(int) $dbtweet["id"];

	}
	else {
		error_log("The tweets feed was empty, last max_id =".$max_id."+1");
		break;
	}

}

?>
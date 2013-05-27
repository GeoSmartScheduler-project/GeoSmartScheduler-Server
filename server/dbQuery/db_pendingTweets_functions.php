<?php
class DB_pendingTweets_Functions {
	
 private $db;
 private $dblink;

    //put your code here
    // constructor
    function __construct() {
        define('__ROOT__', dirname(dirname(__FILE__)));
        include_once (__ROOT__.'/dbQuery/db_connect.php');
        // connecting to database
        $this->db = new DB_Connect();
        $this->dblink=$this->db->connect();
    }

    // destructor
    function __destruct() {
        $this->db->close($this->dblink);
    }

 	/**
     * Get tweets from pending tweets queue
     * 
     * @param string $id_twt id of the tweet to be get from the pendings tweets
     * @return Return set of tweets from pending tweets if it succes or "false" if it fails
     */
    public function getPendingTweet($id_twt) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "SELECT * FROM `pending_tweets` WHERE `id_twt` ='$id_twt'")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
    /**
     * Delete a tweet from pending tweets queue
     * 
     * @param  string $id_twt id of the tweet to be get from the pendings tweets
     * @return Return "true" if succes or "false" if it fails
     */
	public function deletePendingTweet($id_twt) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "DELETE FROM `pending_tweets` WHERE `id_twt`= '$id_twt'")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    //TODO: que reciva un array tweet *sobrecargar metodo

    /**
     * 
     * Put a tweet into the pending tweets queue 
     * @param string $id_twt id of the tweet to be get from the pendings tweets
     * @return Return "true" if it succes or "false" if it fails
     */
    public function putPendingTweet( $id_twt ) {
    	//insert a row into the database
        $result = mysqli_query($this->dblink, "INSERT INTO `pending_tweets`(`created_at`, `id_twt`, `text`) VALUES ((SELECT `created_at` FROM `twitter_trace` WHERE `id_twt`=  '$id_twt'), '$id_twt', (SELECT `text` FROM `twitter_trace` WHERE `id_twt`='$id_twt'))")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    

    
    
}
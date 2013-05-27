<?php
class DB_Traces_Functions {
	
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
     * Get trace by id_trace
     * @return Return set of rows from twitter_trace if it succes or "false" if it fails
     */
    public function getTraceOfTweets($id_trace) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "SELECT * FROM `trace'$id_trace'` ORDER BY  `created_at` ASC")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
	/**
     * Get the tweet id (id_twt) of the last tweet in the trace given as argument
     * @return Return the fields created_at and id_twt of the last tweet of the trace from twitter_trace if it succes or "false" if it fails
     */
    public function getLastIdTweet_TraceOfTweets($id_trace) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "SELECT MAX(`created_at`),`id_twt` FROM `trace'$id_trace'` ORDER BY  created_at ASC")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
    
/**
     * Get trace by date
     * @param $datein Is a date with the format yyyy-mm-dd
     * @return Return set of rows from twitter_trace if it succes or "false" if it fails
     */
    public function getTraceOfTweets_byDate($datein) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "SELECT * FROM `twitter_trace` WHERE `created_at`<= '$datein'  AND DAY(`created_at`) >  (DAY('$datein')-1) ORDER BY `created_at` ASC")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
/**
     * Put tweet into a trace
     * @param $num  Parameter is the number of the trace where the tweet is inserted 
     * @param $idtwt  Parameter is the id of the tweet inserted
     * @param $idnxt_twt  Parameter is the id of the next tweet in the trace
     * @param $period  Parameter is the time between tweet in the trace
     * @param $size  Parameter is the size of the tweet with $idtwt
     * @return Return set of rows from twitter_trace if it succes or "false" if it fails
     */
    public function putTweetinTrace($num, $idtwt, $idnxt_twt, $period, $size) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "INSERT INTO `trace$num` (`id_twt`,`idnxt_twt` ,`time_to_next`, `size`,`id_trace`) VALUES ('$idtwt',' $idnxt_twt',' $period','$size','$num');")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
    
}
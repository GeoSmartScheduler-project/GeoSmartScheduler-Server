<?php
class DB_Traces_Functions {
	
 private $db;
 private $dblink;

    //put your code here
    // constructor
    function __construct() {
        $root=dirname(dirname(__FILE__));
        require_once ($root.'/dbQuery/db_connect.php');
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
        $result = mysqli_query($this->dblink, "SELECT * FROM `trace".$id_trace."`")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
	/**
     * Get the tweet id (id_twt) of the last tweet in the trace given as argument
     * @return Return the fields created_at and id_twt of the last tweet of the trace from twitter_trace if it succes or "false" if it fails
     */
    public function getLastIdTweet_TraceOfTweets($id_trace) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "SELECT MAX(`created_at`),`id_twt` FROM `trace".$id_trace."` ORDER BY  created_at ASC")
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
        $result = mysqli_query($this->dblink, "SELECT * FROM `".TWITTER_TRACE."` WHERE `created_at`<= '$datein'  AND DAY(`created_at`) >  (DAY('$datein')-1) ORDER BY `created_at` ASC")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    /*
     * SELECT * FROM `twitter_trace1` WHERE `created_at`>=DATE_SUB('2013-08-09 00:41:06', INTERVAL 20 MINUTE) AND `created_at`<='2013-08-09 00:41:06' ORDER BY `created_at` ASC
     */
    
public function getTraceOfTweets_byinterval($datein,$interval) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "SELECT * FROM `".TWITTER_TRACE."` WHERE `created_at`>DATE_SUB('".$datein."', INTERVAL ".$interval." MINUTE)  AND `created_at`<='".$datein."' ORDER BY `created_at` ASC")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
public function deleteRowTraceOfTweets($idtwt) {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "DELETE FROM `".TWITTER_TRACE."` WHERE `id_twt`=".$idtwt)
        or die(mysqli_error($this->dblink));
        return $result;
    }
   /*
    * SELECT max(`created_at`) FROM `twitter_trace1` 
    */
    
public function getStartTime() {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "SELECT max(`created_at`) FROM `".TWITTER_TRACE."`")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
    /*
     * 
     */
    
    public function getEndTime() {
    	//get a set of rows from the database
        $result = mysqli_query($this->dblink, "SELECT min(`created_at`) FROM `".TWITTER_TRACE."`")
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
        $result = mysqli_query($this->dblink, "INSERT INTO `notifications_trace".$num."` (`id_twt`,`idnxt_twt` ,`time_to_next`, `size`,`id_trace`) VALUES ('$idtwt',' $idnxt_twt',' $period','$size','$num');")
        or die(mysqli_error($this->dblink));
        return $result;
    }
    
public function create_table_trace($trace) {
    	//create table for trace
        $result = mysqli_query($this->dblink, "CREATE TABLE IF NOT EXISTS `notifications_trace".$trace."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_twt` bigint(20) NOT NULL,
  `idnxt_twt` bigint(20) NOT NULL,
  `id_trace` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `time_to_next` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;")
        or die(mysqli_error($this->dblink));
        return $result;
    } 
}
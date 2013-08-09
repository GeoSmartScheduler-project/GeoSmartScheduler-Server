<?php
$root=dirname(dirname(__FILE__));
include_once ($root.'/utils/config.php');
class DB_Twitter_Functions {
	
    private $db;
	private $dblink;

    //put your code here
    // constructor
    function __construct() {
        $root=dirname(dirname(__FILE__));
        include_once ($root.'/dbQuery/db_connect.php');
        // connecting to database
        $this->db = new DB_Connect();
        $this->dblink=$this->db->connect();
    }

	// destructor
    function __destruct() {
        $this->db->close($this->dblink);
    }

    /**
     * Storing new tweet in the table "twitter_trace"
     * @return Returns "true" if it succes and "false" if it fails
     */
    /*public function storeTweet($created_at, $id_twt, $text, $size) {
        // insert tweet into database
        return mysqli_query($this->dblink, "INSERT INTO ".TWITTER_TRACE." (created_at, id_twt, text, size) VALUES('".$created_at."', '".$id_twt."', '".utf8_encode($text) ."', '".$size."')")
        or die(mysqli_error($this->dblink));
        
    }*/
public function storeTweet($created_at, $id_twt, $text, $size) {
        // insert tweet into database
        return mysqli_query($this->dblink, "INSERT INTO ".TWITTER_TRACE." (created_at, id_twt, size) VALUES('".$created_at."', '".$id_twt."', '".$size."')")
        or die(mysqli_error($this->dblink));
        
    }
  

}

?>
<?php

class DB_Twitter_Functions {

    private $db;
	private $dblink;

    //put your code here
    // constructor
    function __construct() {
        $root=dirname(dirname(__FILE__));
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
     * Storing new tweet in the table "twitter_trace"
     * @return Returns "true" if it succes and "false" if it fails
     */
    public function storeTweet($created_at, $id_twt, $text, $size) {
        // insert tweet into database
        return mysqli_query($this->dblink, "INSERT INTO twitter_trace (created_at, id_twt, text, size) VALUES('$created_at', '$id_twt', '$text', '$size')")
        or die(mysqli_error($this->dblink));
        
    }

  

}

?>
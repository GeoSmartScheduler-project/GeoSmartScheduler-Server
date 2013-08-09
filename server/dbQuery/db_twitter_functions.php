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
  /*
   * Create table to store trace
   */


public function createTable() {
        // insert tweet into database
        return mysqli_query($this->dblink, "CREATE TABLE IF NOT EXISTS `".TWITTER_TRACE."` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id of the row in the table',
  `created_at` datetime NOT NULL COMMENT 'timestamp of the tweet',
  `id_twt` bigint(20) NOT NULL COMMENT 'id of the tweet in twitter',
  `text` text CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL COMMENT 'text posted in twitter',
  `size` int(11) NOT NULL COMMENT 'size of the data posted',
  `id_trace` int(11) NOT NULL DEFAULT '1' COMMENT 'id of trace where is used',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='traces of tweets' AUTO_INCREMENT=0 ")
        or die(mysqli_error($this->dblink));
        
    }

}

?>
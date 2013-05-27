<?php
 
class DB_Connect {
 
	
	
    // constructor
    function __construct() {
 
    }
 
    // destructor
    function __destruct() {
        
    }
 
    // Connecting to database
    public function connect() {
    	define('__ROOT__', dirname(dirname(__FILE__)));
        require_once (__ROOT__.'/utils/config.php');
        /*
        // connecting to mysql
        $con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        // selecting database
        mysql_select_db(DB_DATABASE);
 
        // return database handler
        return $con;
        */
        
        // mysqli
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	return $mysqli;
	//$result = $mysqli->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
	//$row = $result->fetch_assoc();
	//echo htmlentities($row['_message']);
    }
 
    // Closing database connection
    public function close($bdcon) {
        //mysql_close();
        mysqli_close($bdcon);
    }
 
} 
?>
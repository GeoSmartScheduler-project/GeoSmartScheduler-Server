<?php

class DB_GCM_Functions {

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
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $gcm_regid) {
        // insert user into database
        $result = mysqli_query($this->dblink, "INSERT INTO gcm_users(name, email, gcm_regid, created_at) VALUES('$name', '$email', '$gcm_regid', NOW())");
        // check for successful store
        if ($result) {
            // get user details
            $id = mysqli_insert_id($this->dblink); // last inserted id
            $result = mysqli_query($this->dblink, "SELECT * FROM gcm_users WHERE id = $id") or die(mysqli_error($this->dblink));
            // return user details
            if (mysqli_num_rows($result) > 0) {
                return mysqli_fetch_array($result);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Update user register in the DB
     */
    //TODO: try new db function
    public function updateUserGCMid ($old_gcm_regid, $new_gcm_regid){
    	
    	$result = mysqli_query($this->dblink, "UDATE gcm_users SET gcm_regid='$new_gcm_regid' WHERE gcm_regid= '$old_gcm_regid'");
    	
    }
    
    /**
     * Get user by email 
     */
    public function getUserByEmail($email) {
        $result = mysqli_query($this->dblink, "SELECT * FROM gcm_users WHERE email = '$email' LIMIT 1");
        return $result;
    }
    

    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = mysqli_query($this->dblink, "select * FROM gcm_users");
        return $result;
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $result = mysqli_query($this->dblink, "SELECT email from gcm_users WHERE email = '$email'");
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed
            return true;
        } else {
            // user not existed
            return false;
        }
    }
    
    
    /**
     * Get last user added in the database
     */
    public function getLastUserInfo() {
    	$result = mysqli_query($this->dblink, "SELECT `gcm_regid` FROM `gcm_users` WHERE `created_at` = (SELECT max(`created_at`) FROM `gcm_users`)");
    	return $result;
    }

}

?>
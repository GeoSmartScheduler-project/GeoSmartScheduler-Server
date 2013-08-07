
<?php

/**
 * 
 * Multiline error log class
 * For break use "\n" instead '\n'
 * @author ersin güvenç 2008 eguvenc@gmail.com
 *
 */

/*
 * Example of use this class
 * $log = new log();
 * $log->user($msg,$username); //use for user errors
 * $log->general($msg); //use for general errors
 */

Class log {
  //path of the log
  const USER_ERROR_DIR = '/Logs/ServerLog/testTrace.log';
  const GENERAL_ERROR_DIR = '/Logs/ServerLog/BWServer.log';

 /*
  * User Errors
  */
    public function user($msg,$username)
    {
    $date = date('d.m.Y h:i:s');
    $log =$date."|".time()."|". $msg."\n";
    error_log($log, 3, self::USER_ERROR_DIR);
    }
 /*
  * General Errors
  */
    public function general($msg)
    {
    $date = date('d.m.Y h:i:s');
    $log = $date."|".time()."|".$msg."\n";
    error_log($log, 3, self::GENERAL_ERROR_DIR);
    }

}

?>

<?php
ini_set('display_errors', 1);
set_time_limit(0);
$root=dirname(dirname(__FILE__));
require_once($root.'/utils/utilsLog.php');
require_once($root.'/test/bandwidth.php');
$Num_Test=1;
$trace=0;
$log=new log();
//loop to make several test about 7200 seconds of execution of the script
while ($Num_Test < 2)
{


try {
	//Open the BW file  to change the bandwidth using ipfw
	// when we call this particular command, the rest of the script 
    // will keep executing, not waiting for a response
	$cmd = "php ".$root."/test/bandwidth.php ".escapeshellarg($Num_Test)." > /dev/null 2>/dev/null &";
    shell_exec($cmd);
    
	$response = http_get("http://192.168.1.6/server/test/test.php?trace=".$trace,array('time_out'=>7200),$info);
    if ($info["response_code"] == 200) {
    	error_log("Test number ".$Num_Test." has finished correctly");   	
    }
    else {
    	error_log("Unable to perform test number ".$Num_Test." with response code".$info["response_code"]);
    	exit(-1);
    }
} catch (HttpException $ex) {
    echo $ex;
    exit(-1);
}
//Write logs from android device

//Change updates trace to be load by the test script and increase counter of test
$trace++;
$Num_Test++;

}
?>
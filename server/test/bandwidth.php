<?php
$Num_Test=null;
if ( $argc > 1 ){
$Num_Test=$argv[1];

$root=dirname(dirname(__FILE__));
require_once($root.'/utils/utilsLog.php');
$log=new log();
//Create log file for server bandwidth
$logFile = '/Logs/ServerLog/BWServer'.$Num_Test.'.log';
if (!file_exists($logFile)){
	fclose(fopen($logFile, "w+"));
}
//Open the BW file  to change the bandwidth using ipfw
$filename=$root."/Bus_BW/BW".$Num_Test.".log";
$ArrayBW=file($filename,  FILE_IGNORE_NEW_LINES |FILE_SKIP_EMPTY_LINES );
exec("sudo ipfw flush");
exec("sudo ipfw pipe delete 1");
exec("sudo ipfw add pipe 1 ip from 192.168.1.0/20 to any out");

foreach ( $ArrayBW as $bw){
		exec("sudo ipfw pipe 1 config bw ".$bw."Kbit/s");
		sleep(1);
		//log bw to user log
		$log->general($logFile,$Num_Test."|".$bw);
	}
	exec("sudo ipfw pipe 1 config bw 1bit/s");
	$log->general($logFile,$Num_Test."|FIN");
}
else{
error_log("Unable to execute bandwidth.php missed argument to execute");
}
exit();

?>
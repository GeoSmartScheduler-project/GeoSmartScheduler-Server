<?php
set_time_limit(0);
$Num_Test=null;
if ( $argc > 2 ){
	$Num_Test=$argv[1];
	$size=$argv[2];
	$root=dirname(dirname(__FILE__));
	require_once($root.'/utils/utilsLog.php');
	$log=new log();
	//Create log file for server bandwidth
	$logFile = '/Logs/ServerLog/TestNaive/BWServer'.$size.'_'.$Num_Test.'.log';
	if (!file_exists($logFile)){
		fclose(fopen($logFile, "w+"));
	}
	//Open the BW file  to change the bandwidth using ipfw
	$filename=$root."/Ferry_BW/BW".$Num_Test.".log";
	$ArrayBW=file($filename,  FILE_IGNORE_NEW_LINES |FILE_SKIP_EMPTY_LINES );
	exec("sudo ipfw flush");
	exec("sudo ipfw pipe flush");
	exec("sudo ipfw add pipe 1 all from 192.168.1.6/25 to 192.168.1.2/25 out");
	exec("sudo ipfw add pipe 2 all from 192.168.1.2/25 to 192.168.1.6/25 in");

	foreach ( $ArrayBW as $bw){
		exec("sudo ipfw pipe 1 config bw ".$bw."Kbit/s");
		exec("sudo ipfw pipe 2 config bw ".$bw."Kbit/s");
		sleep(1);
		//log bw to user log
		$log->generalToFile($logFile,$Num_Test."|".$bw);
	}
	exec("sudo ipfw pipe 1 config bw 10bit/s");
	$log->generalToFile($logFile,$Num_Test."|FIN");
}
elseif ( $argc > 1 ){
	$Num_Test=$argv[1];

	$root=dirname(dirname(__FILE__));
	require_once($root.'/utils/utilsLog.php');
	$log=new log();
	//Create log file for server bandwidth
	$logFile = '/Logs/ServerLog/Naive100m/3M/BWServer'.$Num_Test.'.log';
	if (!file_exists($logFile)){
		fclose(fopen($logFile, "w+"));
	}
	//Open the BW file  to change the bandwidth using ipfw
	$filename=$root."/Ferry_BW/BW".$Num_Test.".log";
	$ArrayBW=file($filename,  FILE_IGNORE_NEW_LINES |FILE_SKIP_EMPTY_LINES );
	exec("sudo ipfw flush");
	exec("sudo ipfw pipe flush");
	exec("sudo ipfw add pipe 1 all from 192.168.1.6/25 to 192.168.1.2/25 out");
	exec("sudo ipfw add pipe 2 all from 192.168.1.2/25 to 192.168.1.6/25 in");

	foreach ( $ArrayBW as $bw){
		exec("sudo ipfw pipe 1 config bw ".$bw."Kbit/s");
		exec("sudo ipfw pipe 2 config bw ".$bw."Kbit/s");
		sleep(1);
		//log bw to user log
		$log->generalToFile($logFile,$Num_Test."|".$bw);
	}
	exec("sudo ipfw pipe 1 config bw 1bit/s");
	$log->generalToFile($logFile,$Num_Test."|FIN");
}
else{
	error_log("Unable to execute bandwidth.php missed argument to execute");
}
exit();

?>
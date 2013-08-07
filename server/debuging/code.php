<?php
ini_set('display_errors', 1);
set_time_limit(0);
$root=dirname(dirname(__FILE__));
require_once($root.'/utils/utilsLog.php');
$Num_Test=1;
$trace=0;
$log=new log();
//loop to make several test about 7200 seconds of execution of the script
while ($Num_Test < 2)
{
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
	$log->general($Num_Test."|".$bw);
}



try {
	$response = http_get("http://192.168.1.6/server/test/test.php?trace=".$trace,array('time_out'=>7200),$info);
    //$r->send();
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
//shell_exec('cd /home/alberto/adt-bundle-linux/sdk/platform-tools');
//exec("ls");
//exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -v raw   GSS-Naive-notifications:I *:S >/Logs/NotificationsLog/Naive/not_log'.$Num_Test.'.log',$output,$var);
//|/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -v raw  GSS-Naive-download:I *:S >/Logs/DownloadLog/Naive/dow_log'.$Num_Test.'.log 
//|/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -v raw GSS-Naive-download-location:I *:S >/Logs/DownloadLocLog/Naive/dowloc_log'.$Num_Test.'.log');
//shell_exec("kill $(ps aux | grep '[.]/adb logcat' | awk '{print $2}')");
shell_exec(" echo *all*908020* | sudo kill $(ps aux | grep '[.]/adb logcat' | awk '{print $2}')/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -v raw  GSS-Naive-download:I *:S >/Logs/DownloadLog/Naive/dow_log".$Num_Test.".log ");
//shell_exec(" echo *all*908020* | sudo kill $(ps aux | grep '[.]/adb logcat' | awk '{print $2}')");
//shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -v raw GSS-Naive-download-location:I *:S >/Logs/DownloadLocLog/Naive/dowloc_log'.$Num_Test.'.log');
//shell_exec("kill $(ps aux | grep '[.]/adb logcat' | awk '{print $2}')");
//shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -c');
//Change updates trace to be load by the test script and increase counter of test
$trace++;
$Num_Test++;


}
?>
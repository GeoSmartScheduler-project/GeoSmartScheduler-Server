<?php
// php values
ini_set('display_errors', 1);
ini_set('error_reporting', E_ERROR);
set_time_limit(0);
// imports
$root=dirname(dirname(__FILE__));
require_once($root.'/utils/utilsLog.php');
// variables
$Num_Test=1;
//$trace=0;
$log=new log();

//loop to make several test about 7200 seconds of execution of the script
while ($Num_Test < 2)
{
	try {
		// Open the BW file  to change the bandwidth using ipfw
		// When we call this particular command, the rest of the script  will keep executing, not waiting for a response
		//$cmd = "php ".$root."/test/bandwidth.php ".escapeshellarg($Num_Test)." > /dev/null 2>/dev/null &";
		//shell_exec($cmd);
		//Start test loading test.php
		$response = http_get("http://192.168.1.6/server/test/test.php?trace=".$Num_Test,array('time_out'=>7200),$info);

		if ($info["response_code"] == 200) {
			$log->info("Test number ".$Num_Test." has started|Bandwidth info: BW".$Num_Test.".log|Notifications trace: notification_trace".$Num_Test.".log|Sended file: rss.xml");
		}
		else {
			//error_log("Unable to perform test of notification_trace number ".$Num_Test." with response code".$info["response_code"]);
			exit;
		}
	} catch (HttpException $ex) {
		echo $ex;
		exit;
	}
	sleep(60*3);
	error_log("CODE.PHP|PROCCESSING...|Test number ".$Num_Test." has finished,  now post proccess the data generated");
	//Write logs from android device

	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -d -v raw GSS-download-location:I *:S >/Logs/DownloadLocLog/Naive/10k/dowLoc_log".$Num_Test.".log");
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -d -v raw GSS-notifications:I *:S >/Logs/NotificationsLog/Naive/10k/not_log".$Num_Test.".log");
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -d -v raw GSS-download-time:I *:S >/Logs/DownloadLog/Naive/10k/dow_log".$Num_Test.".log");
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -d -v raw GSS-http-throughput:I *:S >/Logs/ThroughputLog/Naive/10k/throughtput_log".$Num_Test.".log");
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb logcat -c");
	//Pull database from app to pc
	shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell "run-as com.alberto.GeoSmartScheduler cat /data/data/com.alberto.GeoSmartScheduler/databases/NetworkMap > /sdcard/database/NetworkMap'.$Num_Test.'.sqlite"');
	shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb pull /sdcard/database/NetworkMap'.$Num_Test.'.sqlite /Logs/DataBase/Naive/10k/');
	//Change updates trace to be load by the test script and increase counter of test
	//$trace++;
	$Num_Test++;

}

?>
<?php
// php values
ini_set('display_errors', 1);
ini_set('error_reporting', E_ERROR);
set_time_limit(0);
// imports
$root=dirname(dirname(__FILE__));
require_once($root.'/utils/utilsLog.php');
// variables
$Num_Test=23;
$error_recovering=null;
//$trace=0;
$log=new log();

//loop to make several test about 7200 seconds of execution of the script
while ($Num_Test < 31)
{
	$id_gpsFile= (($Num_Test-1)%5)+1;
	$error_recovering = false;
	try {
		// Open the BW file  to change the bandwidth using ipfw
		// When we call this particular command, the rest of the script  will keep executing, not waiting for a response
		$cmd = "php ".$root."/test/bandwidth.php ".escapeshellarg($Num_Test)." > /dev/null 2>/dev/null &";
		shell_exec($cmd);
		//Start test loading test.php
		$response = http_get("http://192.168.1.6/server/test/test.php?trace=".$Num_Test."&gpsFile=".$id_gpsFile,array('time_out'=>760),$info);

		if ($info["response_code"] == 200) {
			$log->info("Test number ".$Num_Test." has started|Bandwidth info: BW".$Num_Test.".log|Notifications trace: notification_trace".$Num_Test.".log|Sended file: file3M.txt");
		
		}
		else {
			error_log("Unable to perform test of notification_trace number ".$Num_Test." with response code ".$info["response_code"]);
			error_log("Trying again to perform test of notification_trace number ".$Num_Test);
			//kill banwidth php script and erase BWServer log of the trace
			shell_exec('kill $(ps aux | grep "[b]andwidth.php" | awk "{print $2}")');	
			shell_exec("rm /Logs/ServerLog/Naive100m/3M/BWServer".$Num_Test.".log");	
			//$Num_Test=$Num_Test-1;
			//Pull database from app to pc
			//kill app in device				
			shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb shell kill $(/home/alberto/adt-bundle-linux/sdk/platform-tools/adb shell ps | grep com.alberto.GeoSmartScheduler | awk '{ print $2 }')");
			//restore database in device
			shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell "run-as com.alberto.GeoSmartScheduler rm /data/data/com.alberto.GeoSmartScheduler/databases/NetworkMap"');
			shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell "run-as com.alberto.GeoSmartScheduler cat /sdcard/database/NetworkMap'.($Num_Test-1).'.sqlite > /data/data/com.alberto.GeoSmartScheduler/databases/NetworkMap"');
			
			shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/DOWNLOAD_LOCATION.txt');
			shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/NOTIFICATIONS.txt');
			shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/DOWNLOAD_TIME.txt');
			shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/HTTP_THROUGHPUT.txt');
			//set flag of recovering from error
			$error_recovering = true;
			//exit;
		}
	} catch (HttpException $ex) {
		echo $ex;
		exit;
	}
	
	if (!$error_recovering){
	sleep(60);
	error_log("CODE.PHP|PROCCESSING...|Test number ".$Num_Test." has finished,  now post proccess the data generated");
	//kill app
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb shell kill $(/home/alberto/adt-bundle-linux/sdk/platform-tools/adb shell ps | grep com.alberto.GeoSmartScheduler | awk '{ print $2 }')");
	//Write logs from android device
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/DOWNLOAD_LOCATION.txt /Logs/DownloadLocLog/Naive100m/3M/dowLoc_log".$Num_Test.".log");
	shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/DOWNLOAD_LOCATION.txt');
	
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/NOTIFICATIONS.txt /Logs/NotificationsLog/Naive100m/3M/not_log".$Num_Test.".log");
	shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/NOTIFICATIONS.txt');
	
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/DOWNLOAD_TIME.txt /Logs/DownloadLog/Naive100m/3M/dow_log".$Num_Test.".log");
	shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/DOWNLOAD_TIME.txt');
	
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/HTTP_THROUGHPUT.txt /Logs/ThroughputLog/Naive100m/3M/throughtput_log".$Num_Test.".log");
	shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/HTTP_THROUGHPUT.txt');
	
	
	//Pull database from app to pc
	shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell "run-as com.alberto.GeoSmartScheduler cat /data/data/com.alberto.GeoSmartScheduler/databases/NetworkMap > /sdcard/database/NetworkMap'.$Num_Test.'.sqlite"');
	shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/database/NetworkMap'.$Num_Test.'.sqlite /Logs/DataBase/Naive100m/3M/');
	//Advance test
	$Num_Test++;
	
	}
	
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d logcat -c");
	shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell am start -D -W -n com.alberto.GeoSmartScheduler/com.alberto.GeoSmartScheduler.MainActivity");
	sleep(30);

}

?>
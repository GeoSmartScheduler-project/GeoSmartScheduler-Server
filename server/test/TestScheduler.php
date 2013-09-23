<?php
// php values
ini_set('display_errors', 1);
ini_set('error_reporting', E_ERROR);
set_time_limit(0);
// imports
$root=dirname(dirname(__FILE__));
require_once($root.'/utils/utilsLog.php');
// variables
//$size=array("50k","400k","800k","3000k");
$size=array("200k","800k");
$array_Not_trace=array("1","8","15","17");
$Num_Test=0;
$i=1;
$error_recovering=null;
//$trace=0;
$log=new log();

//loop to make 4 file size scenario, 5 test each
//FILE SIZE LOOP
while($i<count($size)){
	//NOTIICATIONS TRACE LOOP
	shell_exec('sudo rm /root/git/GeoSmartScheduler-Server/server/assets/send/file.txt');
	shell_exec('sudo cp /root/git/GeoSmartScheduler-Server/server/assets/file'.$size[$i].'.txt /root/git/GeoSmartScheduler-Server/server/assets/send/file.txt');
	while ($Num_Test < count($array_Not_trace))
	{
		//Set gps file to be used
		$id_gpsFile= (($Num_Test)%5)+1;
		//Set banwidth trace to be used
		$bandwidthTrace=$array_Not_trace[$Num_Test];
		//Set notification trace to be used
		$Not_trace=$array_Not_trace[$Num_Test];
		$error_recovering = false;
		try {
			// Open the BW file  to change the bandwidth using ipfw
			// When we call this particular command, the rest of the script  will keep executing, not waiting for a response
			$cmd = "php ".$root."/test/bandwidth.php ".escapeshellarg($bandwidthTrace)." ".escapeshellarg($size[$i])."> /dev/null 2>/dev/null &";
			shell_exec($cmd);
			//Start test loading test.php
			$response = http_get("http://192.168.1.6/server/test/test.php?trace=".$Not_trace."&gpsFile=".$id_gpsFile,array('time_out'=>660),$info);

			if ($info["response_code"] == 200) {
				$log->info("Test ".$size[$i]." trace ".$Not_trace." has started|Bandwidth info: BW".$bandwidthTrace.".log|Notifications trace: notification_trace".$Not_trace.".log|Sended file: file".$size[$i].".txt");

				sleep(30);
				error_log("TESTSCHEDULER.PHP|PROCCESSING...|Test ".$size[$i]." has finished,  now post proccess the data generated");
				//kill app
				shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb shell kill $(/home/alberto/adt-bundle-linux/sdk/platform-tools/adb shell ps | grep com.alberto.GeoSmartScheduler | awk '{ print $2 }')");
				//Write logs from android device
				shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/DOWNLOAD_LOCATION.txt /Logs/DownloadLocLog/TestNaive/dowLoc_log".$size[$i]."_".$Num_Test.".log");
				shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/DOWNLOAD_LOCATION.txt');

				shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/NOTIFICATIONS.txt /Logs/NotificationsLog/TestNaive/not_log".$size[$i]."_".$Num_Test.".log");
				shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/NOTIFICATIONS.txt');

				shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/DOWNLOAD_TIME.txt /Logs/DownloadLog/TestNaive/dow_log".$size[$i]."_".$Num_Test.".log");
				shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/DOWNLOAD_TIME.txt');

				shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d pull /sdcard/HTTP_THROUGHPUT.txt /Logs/ThroughputLog/TestNaive/throughtput_log".$size[$i]."_".$Num_Test.".log");
				shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell rm /sdcard/HTTP_THROUGHPUT.txt');

				//restore database in device
				shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell "run-as com.alberto.GeoSmartScheduler rm /data/data/com.alberto.GeoSmartScheduler/databases/NetworkMap"');
				shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell "run-as com.alberto.GeoSmartScheduler cat /sdcard/NetworkMap30.sqlite > /data/data/com.alberto.GeoSmartScheduler/databases/NetworkMap"');

				//Advance test
				$Num_Test++;
			}
			else {
				error_log("Unable to perform test ".$size[$i]." with response code ".$info["response_code"]);
				error_log("Trying again to perform test ".$size[$i]);
				//kill banwidth php script and erase BWServer log of the trace
				shell_exec('kill $(ps aux | grep "[b]andwidth.php" | awk "{print $2}")');
				shell_exec("rm /Logs/ServerLog/TestNaive/BWServer".$size[$i]."_".$Num_Test.".log");
				//Pull database from app to pc
				//kill app in device
				shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb shell kill $(/home/alberto/adt-bundle-linux/sdk/platform-tools/adb shell ps | grep com.alberto.GeoSmartScheduler | awk '{ print $2 }')");
				//restore database in device
				shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell "run-as com.alberto.GeoSmartScheduler rm /data/data/com.alberto.GeoSmartScheduler/databases/NetworkMap"');
				shell_exec('/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell "run-as com.alberto.GeoSmartScheduler cat /sdcard/NetworkMap30.sqlite > /data/data/com.alberto.GeoSmartScheduler/databases/NetworkMap"');
					
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
			//exit;
		}

		//if (!$error_recovering){
			

		//}

		shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d logcat -c");
		shell_exec("/home/alberto/adt-bundle-linux/sdk/platform-tools/adb -d shell am start -D -W -n com.alberto.GeoSmartScheduler/com.alberto.GeoSmartScheduler.MainActivity");
		sleep(60);

	}
	//change file to be sent
	$i++;	
}
shell_exec('sudo cp /root/git/GeoSmartScheduler-Server/server/assets/file'.$size[0].'.txt /root/git/GeoSmartScheduler-Server/server/assets/send/file.txt');
?>
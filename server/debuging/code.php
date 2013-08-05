<?php
ini_set('display_errors', 1);
set_time_limit(0);
$root=dirname(dirname(__FILE__));
$Num_Test=1;
$trace=0;
//loop to make several test about 7200 seconds of execution of the script
while ($Num_Test < 10)
{
//Open the config file of module httpthrottle to change the bandwidth log to be read
$LogNumb=1;
$configBW = "{ 'match': 'bitrate=', 'logfile': '/var/www/server/Bus_BW/BW"+$LogNum+".log', 'fixed_bw': -1 }";
$filename='/var/www/server/httpthrottle.conf';
$handle=fopen($filename, 'w+');
if($handle){
	if(fwrite($handle, $configBW)<=0)
	{
		error_log('Unable to change the BW log in the file: '+$filename);
		fclose($handle);
		exit(-1);
	}
	else{
		fclose($handle);
		//If we have written in the config file, restart apache to load the config
		system('echo "*all*908020*" | sudo -u root -S service apache2 restart');
	}
}
else{
	error_log('Unable to open the BW log in the file: '+$filename);
	exit(-1);
}


//Load the test script using a Http get request to test.php
$r = new HttpRequest('http://192.168.1.8/server/test/test.php', HttpRequest::METH_GET);
$r->addQueryData(array('trace' => 0));
try {
    $r->send();
    if ($r->getResponseCode() == 200) {
    	error_log('Test number '+$Num_Test+' has finished correctly');
    	//Change updates trace to be load by the test script and increase counter of test
    	$trace++;
        $Num_Test++;
    }
    else {
    	error_log('Unable to perform test number '+$Num_Test+' with response code'+$r->getResponseCode());
    	exit(-1);
    }
} catch (HttpException $ex) {
    echo $ex;
    exit(-1);
}

}
?>
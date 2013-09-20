<?php
//change file to be sent
	shell_exec('sudo rm /root/git/GeoSmartScheduler-Server/server/assets/send/file.txt');
	shell_exec('sudo cp /root/git/GeoSmartScheduler-Server/server/assets/file100k.txt /root/git/GeoSmartScheduler-Server/server/assets/send/file.txt');
	
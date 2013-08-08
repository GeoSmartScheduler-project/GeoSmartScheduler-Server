<?php


	$size_in_bytes=5242880;
	$file_name=dirname(dirname(__FILE__)).'/assets/file.txt';
   	$data = str_repeat(rand(0,9), $size_in_bytes);
   	file_put_contents($file_name, $data); //writes $data in a file   
	exit;
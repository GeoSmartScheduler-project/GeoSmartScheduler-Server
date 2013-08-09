<?php

	$size_in_bytes=3145728;
	$file_name=dirname(dirname(__FILE__)).'/assets/file2.txt';
   	$data = str_repeat(rand(0,9), $size_in_bytes);
   	file_put_contents($file_name, $data); //writes $data in a file   
	exit;
<?php
	$content=json_encode($Arraytweets);
	header('HTTP/1.1 200 OK');
    header('Content-type: application/json');
    header("Content-Length: " .strlen($content));
    echo 
    exit;
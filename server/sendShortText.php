<?php
	$content=json_encode($Arraytweets);
	header('HTTP/1.1 200 OK');
    header('Content-type: application/json');
    header("Content-Length: " .strlen($content));
    echo $content;
    
    $type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
    exit;
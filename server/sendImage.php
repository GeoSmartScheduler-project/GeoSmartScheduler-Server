<?php
// open the file in a binary mode
$name = './img/ok.png';
$fp = fopen($name, 'rb');

// send the right headers
header('HTTP/1.1 200 OK');
header("Content-Type: image/png");
header("Content-Length: " . filesize($name));
// dump the picture and stop the script
fpassthru($fp);
exit;



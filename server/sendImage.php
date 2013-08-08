<?php
// open the file in a binary mode
$filename = dirname(__FILE__).'/assets/image.jpeg';
$fp = fopen($filename, 'rb');
if (file_exists($filename)) {
	// send the right headers
	header('HTTP/1.1 200 OK');
	header("Content-Type: image/jpeg");
	header("Content-Length: " . filesize($filename));
	header('Content-Disposition: attachment; filename='.basename($filename)); 
	// dump the picture and stop the script
	ob_clean();
	flush();
	fpassthru($fp);
}
exit;



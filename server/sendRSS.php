<?php
	$filename = dirname(__FILE__).'/assets/rss.xml';
	if (file_exists($filename)) {
	header('HTTP/1.1 200 OK');
    header('Content-type: application/rss+xml');
    header('Content-Length: ' .filesize($filename));
    header('Content-Disposition: attachment; filename='.basename($filename));
    ob_clean();
    flush();
	readfile($filename);
}
	exit;
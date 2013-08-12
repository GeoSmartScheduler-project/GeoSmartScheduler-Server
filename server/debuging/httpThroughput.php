<?php
if (isset($_GET) && $_GET["file"]!=null){
	
$file = dirname(__FILE__).'/assets/'.$_GET["file"];
if (file_exists($file)) {
	header('HTTP/1.1 200 OK');
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
}
}
    exit;
<?php

ini_set ('user_agent', $_SERVER['HTTP_USER_AGENT']);

$context = array(
    'http'=>array('max_redirects' => 991)
);

$context = stream_context_create($context);


if(isset($_GET['src'])){
show_source('xdom.php');
}
//header('Content-type: application/html');

// url to fetch
$geturl = $_GET['geturl'];


// get target content
$handle = fopen($geturl, "r",false,$context);

// if successfully connected go get the file
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        echo $buffer;
    }
    fclose($handle);
}
?>

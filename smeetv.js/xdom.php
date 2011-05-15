<?php
if(isset($_GET['src'])){
show_source('xdom.php');
}
//header('Content-type: application/html');

// url to fetch
$geturl = $_GET['geturl'];


// get target content
$handle = fopen($geturl, "r");

// if successfully connected go get the file
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        echo $buffer;
    }
    fclose($handle);
}
?>

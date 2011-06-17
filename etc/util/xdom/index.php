<?php
ini_set ('user_agent', $_SERVER['HTTP_USER_AGENT']);
$context = array(
    'http'=>array('max_redirects' => 10)
);

$arr=explode('?',$_SERVER['REQUEST_URI']);

$context = stream_context_create($context);
$handle = fopen($arr[1], "r",false,$context);

if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        echo $buffer;
    }
    fclose($handle);
}
?>

<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');

connect2db();


$query="select count(*) from twits_dump";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
echo $get[0];

$update="insert into stat (num,timestamp) values ('{$get[0]}','".time()."')";
echo $update;
$goupdate=mysql_query($update);


?>

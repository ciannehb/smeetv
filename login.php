<?
require_once('smeetv/func.php');
session_start();

if(isUserLoggedIn()==1){
    header("Location:index.php");
    return;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
drawHeader('Welcome ',$u,'1');


require_once($_SERVER['DOCUMENT_ROOT'].'/etc/util/_landing_content.txt');

?>









<?require_once('smeetv/footer.php');?>


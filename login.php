<?
require_once('smeetv/func.php');
session_start();

if(isUserLoggedIn()==1){
    header("Location:index.php");
    return;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
drawHeader('Welcome ',$u,'1');

connect2db();
$query="select id,content,link,date,timestamp from twits_dump order by id desc limit 0,10";
$go=mysql_query($query);
for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);

    //echo $get[0]."<br><hr>";


    echo displayTwit($get['id'],$get['content'],$get['link'],$get['date'],$get['timestamp'],0);

}

?>









<?require_once('smeetv/footer.php');?>


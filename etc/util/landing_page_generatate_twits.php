<?
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');

$smeetvdb=connect2db();
$query="select id,content,link,date,timestamp from twits_dump order by id desc limit 0,10";
$go=mysql_query($query);
$output="";
for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);

    //echo $get[0]."<br><hr>";



    $output.=displayTwit($get['id'],$get['content'],$get['link'],$get['date'],$get['timestamp'],0,1,0,1);

}


$f = fopen("_landing_content.txt", "w");
fwrite($f, $output);
fclose($f);
disconnectFromDb($smeetvdb);
//echo $_SERVER['PHP_SELF']." pushed\n";
?>

<? // in this script we check for new updates
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');
require_once($_SERVER['DOCUMENT_ROOT']."/smeetv/aquarium/aquarium/filter.php");
connect2db();


$qh="select keyword,counter from keywords order by counter desc limit 0,30";
$qh=mysql_query($qh);
$output="";
for($i=0;$i<mysql_num_rows($qh);$i++){
    $gh=mysql_fetch_array($qh);



    $wd=filter($gh['keyword']);


    $output.= "
         <span><a href=\"/stream/{$gh['keyword']}\" style=\"opacity: ".(($gh['counter']/10)+0.25).";font-size:".(($gh['counter']*2)+10)."px\" title=\"{$gh['keyword']}\" id=\"{$gh['keyword']}\">$wd[0]</a></span>
    ";
}
echo $output;




return;
?>

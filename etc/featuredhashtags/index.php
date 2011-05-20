<? // in this script we check for new updates
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');

connect2db();


$qh="select keyword,counter from keywords order by counter desc";
$qh=mysql_query($qh);
for($i=0;$i<mysql_num_rows($qh);$i++){
    $gh=mysql_fetch_array($qh);
    echo "
         <span><a href=\"\" style=\"font-size:".($gh['counter']+10)."px\" title=\"{$gh['keyword']}\" id=\"{$gh['keyword']}\">{$gh['keyword']}</a></span>
    ";
}



return;
?>

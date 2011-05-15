<?
require_once('func.php');
connect2db();
//$query="select id from accounts order by id desca";
$query=$_GET['query'];
$go=mysql_query($query);
for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    echo $get[0];
    if(!mysql_num_rows($go)==$i)
        echo ",";
}

?>

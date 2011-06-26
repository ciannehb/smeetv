<?
require_once('func.php');


echo "<h2>Cleaning twits table leaving 100 most recent twits for each user</h2>";


/*

for the future need to use enahnced query :

mysql> delete from twits where id in (select id from twits where uid=4 order by id asc limit 0,2)
    -> ;
ERROR 1235 (42000): This version of MySQL doesn't yet support 'LIMIT & IN/ALL/ANY/SOME subquery'


*/

connect2db();

$query="select id from accounts";
$go=mysql_query($query);
for($i=0;mysql_num_rows($go)>$i;$i++){
    $get=mysql_fetch_array($go);
    $query="select id from twits where uid='{$get[0]}' order by id desc limit 50,1";
    echo $query."<br>";
    $gg=mysql_query($query);
    $gg=mysql_fetch_array($gg);
    if($gg) {
        $ggquery="delete from twits where uid='{$get[0]}' AND id < {$gg[0]}";
        mysql_query($ggquery);
        echo $ggquery."<br>";
    }
    
}




?>



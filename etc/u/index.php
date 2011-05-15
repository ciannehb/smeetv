<? // in this script we check for new updates
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');

$_POST['h']=advancedClean(3,$_POST['h']);
$_POST['i']=advancedClean(3,$_POST['i']);
if(!isUserLoggedIn()==1){
    return;
}

connect2db();

$query="select id,timestamp from twits where uid='{$_POST['i']}' order by id desc limit 0,1;";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$new=md5($get['id']."".$get['timestamp']);
$compareto=$_POST['h'];


if($new == $compareto)
    echo "0";
else
    echo "1";
return;


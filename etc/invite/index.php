<? // in this script we check for new updates
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');

$_POST['i']=advancedClean(3,$_POST['i']);

connect2db();

$query="select id,strike from invite where code='{$_POST['i']}' and strike<2 limit 0,1";
$go=mysql_query($query);

if(mysql_num_rows($go)){
session_start();
$_SESSION['invite']=md5($_POST['i']);
mysql_query("update invite set strike=strike+1 where code='{$_POST['i']}'");
echo "1";}else{echo "0";}
return;


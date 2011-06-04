<?

require_once('func.php');
session_start();
connect2db();

require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
drawHeader('remote control',$u,'1');

$_GET['q']=advancedClean(3,$_GET['q']);


if($_GET['q']) {
    $query="select id,idhash from accounts where idhash like '%{$_GET['q']}%';";
    $go=mysql_query($query);
    if(mysql_num_rows($go)==1){
        $get=mysql_fetch_array($go);
        $query="update accounts set idhash='".substr_replace($get['idhash'],'','0','11')."' where id='{$get['id']}'";
        mysql_query($query);
        echo "<h2>Your account email has been activated.</h2>";

    }





}






?>

<?require_once('footer.php');?>



<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();
    $_POST['id']=advancedClean(3,$_POST['id']);
    $_POST['op']=advancedClean(3,$_POST['op']);
    if(isUserLoggedIn()==1){
        $u=true;
    }

    if($_SESSION['idhash']!="d820d0aa0b02f932465b1e84b98afbdd673bbdb7"){
        session_destroy();
        header("Location:/");
        return false;
    }


    connect2db();

$arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));
$transport=explode("?",$arr[count($arr)-1]);
$query="delete from twits_dump where id='".alphaID($transport[0],true)."'";
$go=mysql_query($query);


$msg="";

if($go) {
$msg.="<p>Deleted image {$transport[0]} from dump table.</p>";
} else {
$msg.="<p>Failed to delete image {$transport[0]} from dump table.</p>";
$error=1;
}


$query="delete from flagged where id='".$transport[0]."'";
$go=mysql_query($query);
if($go) {
$msg.="<p>Deleted image {$transport[0]} from flagged table.</p>";
} else {
$msg.="<p>Failed to delete image {$transport[0]} from flagged table.</p>";
$error=1;
}




    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
    drawHeader('Admin',$u);


?>
    <h2><?=$msg?></h2>














<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>

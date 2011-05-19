<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();
    $_POST['id']=advancedClean(3,$_POST['id']);
    $_POST['op']=advancedClean(3,$_POST['op']);
    if(isUserLoggedIn()==1){
        $u=true;
    }

    if($_SESSION['idhash']!="d820d0aa0b02f932465b1e84b98afbdd673bbdb7"){
        header("Location:/");
        return false;
    }
    connect2db();

$arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));
$transport=explode("?",$arr[count($arr)-1]);
$query="update twits_dump set flagged='0' where id='{$transport[0]}'";
$go=mysql_query($query);

if($go) {
$msg="Unflagged image {$transport[0]}";
} else {
$msg="Failed to unflag image {$transport[0]}";
$error=1;
}


    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
    drawHeader('Admin',$u);


?>
    <h2><?=$msg?></h2>














<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>

<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');


$_POST['section']=advancedClean(3,$_POST['section']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['ad']=advancedClean(3,$_POST['ad']);
$_POST['op']=advancedClean(3,$_POST['op']);

$_POST['smeetv_hashtags']=advancedClean(3,$_POST['smeetv_hashtags']);
$_POST['smeetv_speed']=advancedClean(3,$_POST['smeetv_speed']);
$_POST['smeetv_size']=advancedClean(3,$_POST['smeetv_size']);
$_POST['smeetv_text']=advancedClean(3,$_POST['smeetv_text']);
$_POST['smeetv_img_num']=advancedClean(3,$_POST['smeetv_img_num']);




if(!isUserLoggedIn()==1){
    //header("Location:/");
    return;
}




$username=$_SESSION['username'];
$id=$_SESSION['id'];

$_POST['var']=advancedClean(3,$_POST['var']);


$force='1';


connect2db();


if($_POST['section']=="settings"){
    if($_POST['ad']=="false"){
        $_POST['ad']='0';
    }else{
        $_POST['ad']='1';
    }
    $query="update accounts set ad='".$_POST['ad']."'";
    if($_POST['password']) {
        $query.=" ,password=password('".$_POST['password']."') ";
    }
}elseif($_POST['section']=="remotecontrol"){

    $query="update accounts set ";
    $query.=" smeetv_hashtags='".$_POST['smeetv_hashtags']."', ";
    $query.=" smeetv_speed='".$_POST['smeetv_speed']."', ";
    $query.=" smeetv_text='".$_POST['smeetv_text']."', ";
    $query.=" smeetv_img_num='".$_POST['smeetv_img_num']."' ";




}elseif($_POST['section']=="save_resize_r"){
    $query="update accounts set ";
    $query.=" smeetv_size = '".$_POST['smeetv_size']."' ";  
}else{
    $query="update accounts set smeetv_hashtags_force='".$_POST['smeetv_hashtags_force']."',ad='".$_POST['ad']."',smeetv_img_num='".$_POST['smeetv_img_num']."',smeetv_text='".$_POST['smeetv_text']."',smeetv_channels='".$_POST['smeetv_channels']."',smeetv_hashtags='".$_POST['smeetv_hashtags']."',smeetv_speed='".$_POST['smeetv_speed']."',force_update='$force'";
}








if($_POST['refetch']==1)
    $query.=",force_update='2'";
$query.=" where id='".$id."' and username='".$username."'  ";




$go=mysql_query($query);
//echo $query;


if($go) {
$result="success";
} else {
$result="failure";
$error=1;
}


if($error) {
    echo "Internal erorr";return;
} else {
    if(!$_POST['op']=='ajax') {header('Location:/');}
    else {echo "<html><head><meta name=\"ajr\" content=\"1\" /></head><body></body></html>";}
}



?>

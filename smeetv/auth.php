<?
require_once('func.php');

$_POST['username']=advancedClean(3,$_POST['username']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['process']=advancedClean(3,$_POST['process']);


session_start();


if($_POST['process']==1 && $_POST['username'] && $_POST['password']){
    connect2db();
    $query="select id,idhash from accounts where username='{$_POST['username']}' and password=password('{$_POST['password']}')";
    $go=mysql_query($query);
    $num=mysql_num_rows($go);
    if($num==1){
        $_SESSION['authenticated']=1;
        $_SESSION['username']=$_POST['username'];
        $get=mysql_fetch_array($go);
        if($get['idhash']=='NULL'){
            header("Location:/accountnotactive/");
            return;
        }
        $_SESSION['id']=$get['id'];
        $_SESSION['idhash']=$get['idhash'];
        $query="update accounts set last_ip='{$_SERVER['REMOTE_ADDR']}',last_time='".time()."' where id='{$get['id']}' ";
        $go=mysql_query($query);
    }
}




if(isUserLoggedIn()==1){
    header("Location:/");
    return;
} else {
    header("Location:/smeetv/login.php");
}



?>

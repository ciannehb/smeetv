<?
session_start();
require_once('func.php');


if(!isUserLoggedIn()==1){
    header("Location:/smeetv/smeetv/login.php");
    return;
}


$_POST['username']=advancedClean(3,$_POST['username']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['process']=advancedClean(3,$_POST['process']);


require_once('header.php');


/*
        connect2db();
        $query="select id from accounts where username='".$_POST['username']."'";
        $go=mysql_query($query);
        $num=mysql_num_rows($go);
*/

if($_POST['process']==1 && $_POST['username'] && $_POST['password']){
    connect2db();
    $query="select id from accounts where username='{$_POST['username']}' and password=password('{$_POST['password']}')";
    $go=mysql_query($query);
    $num=mysql_num_rows($go);
    if($num==1){
        $_SESSION['authenticated']=1;
        $_SESSION['username']=$_POST['username'];
    }
}



?>


<form method="post" action="/smeetv/auth.php">
    <input type="hidden" name="process" value="1">
    username: <input type="text" name="username" value=""><br>
    password: <input type="password" name="password" value="">
    <br>
    <input type="submit" value="Login">
</form>

<?require_once('footer.php');?>

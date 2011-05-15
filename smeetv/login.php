<?
require_once('func.php');
session_start();

if(isUserLoggedIn()==1){
    header("Location:index.php");
    return;
}


$_POST['username']=advancedClean(3,$_POST['username']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['process']=advancedClean(3,$_POST['process']);


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

require_once('header.php');
?>

<p>&nbsp;</p>

<form id="login" method="post" action="/smeetv/auth.php">
    <input type="hidden" name="process" value="1">
    <p><label class="fleft w100" for="username">username:</label> <input type="text" name="username" value="" id="username"></p>
    <p><label class="fleft w100" for="password">password:</label> <input type="password" name="password" value="" id="password"></p>
    <p><label class="fleft w100">&nbsp;</label><input type="submit" value="Login">
</form>
<?require_once('footer.php');?>

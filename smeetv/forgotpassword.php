<?
require_once('func.php');
session_start();
connect2db();
/*
if(isUserLoggedIn()==1){
    header("Location:index.php");
    return;
}
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
drawHeader('remote control',$u,'1');



$_POST['username']=advancedClean(3,$_POST['username']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['process']=advancedClean(3,$_POST['process']);
$_GET['q']=advancedClean(3,$_GET['q']);


require_once('header.php');

echo "<section class='grid_24' id='content'>";

if($_GET['q']){

$query="select id from accounts where tmp ='{$_GET['q']}'";
$go=mysql_query($query);
if(mysql_num_rows($go)==1){
    $newpass=md5(rand(78,99999));
    $newpass=substr($newpass,1,ceil((strlen($newpass)/4)));
    $query="update accounts set password=password('$newpass'),tmp='' where tmp='{$_GET['q']}'";
    mysql_query($query);
    echo "<h2>Your password has been reset to <span style=\"padding:.25em;font-size:1.1em;border:1px solid black\">$newpass</span></h2>";
}

}elseif($_POST['username']){

$query="select email from accounts where username='{$_POST['username']}'";
$go=mysql_query($query);
if(mysql_num_rows($go)==1) {
    $get=mysql_fetch_array($go);
    $tmp=md5(rand(100,999999999));
    mysql_query("update accounts set tmp='$tmp' where username='{$_POST['username']}'");
    $message="Dear {$_POST['username']},\n\nYou or someone on your behalf initiated password reset process for your smeetv.com account. If this was not you, you can safely ignore this email. If you are sure you want to reset password, please click or copy and paste into your browser the following link to finalize your password reset:\n\nhttp://smeetv.com/{$_SERVER['PHP_SELF']}?q=$tmp";

// Additional headers
$headers .= 'From: smeetvcom@gmail.com <smeetvcom@gmail.com>' . "\r\n";


    $gomail=mail($get[0],'Password reset for your smeetv.com account',$message,$headers);


} else {
    echo "<p class='error'>We could not locate username '{$_POST['username']}'</p>";
}


} else {
?>
<form id="forgotpassword" method="post" action="<?=$_SERVER['PHP_SELF']?>">
    <input type="hidden" name="process" value="1">
    <p>Please tell us your username so we can initiate password reset:<br><input type="text" name="username" value="" id="username"><br>
    <input type="submit" value="Go">
</form>
<?}?>

</section>


<?require_once('footer.php');?>

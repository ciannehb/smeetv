<?
require_once('header.php');
require_once('func.php');


    drawHeader('remote control',$u,'1');


$_POST['username']=advancedClean(3,$_POST['username']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['channels']=advancedClean(3,$_POST['channels']);
$_POST['email']=advancedClean(3,$_POST['email']);
$_POST['process']=advancedClean(3,$_POST['process']);

?>
<p style="color:red">Registration is closed at this moment.</p>
<p>One way to request invite is to reply to <a href="http://twitter.com/smeetv">@smeetv</a> on twitter and ask for the invite code. We'll direct message or reply back with your invite code.</p>
<?

if($_POST['process']==1) {

if(!validate_username($_POST['username'])==TRUE ||
   !$_POST['username'] ||
   !$_POST['password'] ||
   !$_POST['channels'] ||
   !$_POST['email']  
  ){
        echo "<span class=\"error\">Error while signing up, please review your form and try again</span>";
   } else {

?>
<?return;
        connect2db();
        $query="select id from accounts where username='".$_POST['username']."'";
        $go=mysql_query($query);
        $num=mysql_num_rows($go);
        if($num==0) {
            $query="insert into accounts (username,password,email,smeetv_channels,idhash) values ('{$_POST['username']}',password('{$_POST['password']}'),'{$_POST['email']}','{$_POST['channels']}','". hash('ripemd160', $_POST['username']) ."') ";
            $go=mysql_query($query);
        } else {
            echo "<span class=\"error\">This username is already taken, please choose another one.</span>";
        }

        echo "<span class=\"success\">Successfully registered.</span>";

   }
}

?>

<p>&nbsp;</p>

<form method="post" id="signup" action="<?=$_SERVER['PHP_SELF']?>">
    <input type="hidden" name="process" value="1">

    <p><label for="username" class="fleft w100">username:</label> <input type="text" name="username" id="username" value=""> <span class="aside">(alphanumeric charachters)</span></p>

    <p><label for="password" class="fleft w100">password:</label> <input type="password" name="password" id="password" value=""> <span class="aside">(alphanumeric charachters)</span></p>

    <p><label for="email" class="fleft w100">email:</label> <input type="text" name="email" id="email" value=""></p>

    <p><label for="channels" class="fleft w100">channels:</label> <input type="text" name="channels" id="channels" value=""> <span class="aside">(eg. shopping, obama, soccer)</span></p>

    <p><label class="fleft w100">&nbsp;</label><input type="submit" value="Sign up"></p>
</form>

<?require_once('footer.php');?>

<?
require_once('func.php');
session_start();

if(isUserLoggedIn()==1){
    header("Location:index.php");
    return;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
drawHeader('remote control',$u,'1','login');



$_POST['username']=advancedClean(3,$_POST['username']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['process']=advancedClean(3,$_POST['process']);
$_GET['login']=advancedClean(3,$_GET['login']);

if($_GET['loggedin']) {
echo '

<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>
We could not log you in. You can <a href="/smeetv/signup.php">sign up</a> or <a href="/smeetv/forgotpassword.php">reset your password</a>.
<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>

';
}




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

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

<script src="http://smeetv.com/js/smeetv.js/smeetv.js"></script>
<script type="text/javascript" src="http://fsdn2.somewhe.com/smeetv/js/jquery.cycle.all.2.72.js"></script>
<script type="text/javascript"> 
$(document).ready(function() {

    $('#recent article .t').click(function(){
        window.open($(this).find('a').attr('href'));
        return false;
    });
    $('#featuredhashtags_content').load('/etc/featuredhashtags/');
    $('#featuredhashtags').live('click',function(e){
            $('#featuredhashtags_content').toggle();
            e.preventDefault();
    });
    $('#recent').cycle({
		fx: 'uncover',
                timeout: 7500,
	});




           // imagify
           $('#recent > article').each(function(){
               var content=$(this).find('a').html(),
                   thisid=$(this).attr('id');
               imagify(content,thisid);
           });




});
</script> 
<section id="content" class="grid_24">
<section class="l grid_12">
<form id="login" method="post" action="/smeetv/auth.php" class="">
    <input type="hidden" name="process" value="1">
    <p class="grid_24"><label class="fleft w100" for="username">username:</label> <input type="text" name="username" value="" id="username"></p>
    <p class="grid_24"><label class="fleft w100" for="password">password:</label> <input type="password" name="password" value="" id="password"></p>
    <p class="grid_24"><label class="fleft w100">&nbsp;</label><input type="submit" value="Login"></p>
</form>
</section>
<section class="grid_12">
<ul class="passwordstuff">
<li>Don't have an account yet? <a href="http://smeetv.com/smeetv/signup.php">Proceed to register</a>, it only takes few seconds!</li>
<li>Cannot remember your password? <a href="http://smeetv.com/smeetv/forgotpassword.php">You can reset it</a>.</li>
<li><a href="#">What is this?</a></li>
</ul>

</section>
<hr class="clear clearfix">
<section class="synd grid_12">
<h2 class="grid_24 recent_photographs" >Recent photographs:</h2>
<section id="recent">
<?require_once($_SERVER['DOCUMENT_ROOT'].'/etc/util/_landing_content.txt');?>
</section>
</section>

<section class="synd ht grid_12">
<h2>Featured keywords:</h2>
<div id="featuredhashtags_content" style="position:relative;width:auto;display:block;border:0;top:.25em;margin-left:2em;"></div>
</section>

</section>



 
<?require_once('footer.php');?>

<?
session_start();
//unset($_SESSION['invite']);
require_once('header.php');
require_once('func.php');




$_POST['username']=advancedClean(3,$_POST['username']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['channels']=advancedClean(3,$_POST['channels']);
$_POST['email']=advancedClean(3,$_POST['email']);
$_POST['process']=advancedClean(3,$_POST['process']);

if($_POST['process']==1) {





if(!validate_username($_POST['username'])==TRUE) {
    $error="User name is not valid";
    $reg_err = TRUE;
} elseif (!$_POST['username'] || !$_POST['password']) {
    $error="Make sure you fill out all the fields";
    $reg_err = TRUE;
} elseif (!validate_email($_POST['email'])==TRUE) {
    $error="Email address you specified is not valid";
    $reg_err = TRUE;
} elseif (strlen($_POST['password'])<4) {
    $error="Your password must be at least 4 characters";
    $reg_err = TRUE;
}




  if($reg_err==TRUE){
        echo "<span class=\"error\">Error while signing up, please review your form and try again ($error)</span>";
   } else {


?>
<?
        connect2db();
        $query="select id from accounts where username='".$_POST['username']."'";
        $go=mysql_query($query);
        $num=mysql_num_rows($go);
        if($num==0) {
            $query="insert into accounts (username,password,email,idhash,smeetv_hashtags,smeetv_speed,smeetv_text) values ('{$_POST['username']}',password('{$_POST['password']}'),'{$_POST['email']}','unverified-". hash('ripemd160', $_POST['username']) ."','example, Japan, #iphone, @aplusk','10000','1') ";
            $go=mysql_query($query);
            unset($_SESSION['invite']);

            $last_insert_id=mysql_insert_id();

            $query="select id,content,link,date from twits_dump where content like '%@aplusk%' or content like '%#iphone%' or content like '%Japan%' limit 0,6";
            $go=mysql_query($query);
            for($i=0;$i<mysql_num_rows($go);$i++){
                $get=mysql_fetch_array($go);
                $subquery="insert into twits (uid,content,timestamp,link,date,aid) values ('".$last_insert_id."','{$get['content']}','".time()."','{$get['link']}','{$get['date']}','{$get['id']}')";
                mysql_query($subquery);
            }



            $message="Dear {$_POST['username']},\n\nPlease verify your email by clicking or pasting into your browser the following link:\n\nhttp://smeetv.com/smeetv/verifyemail.php?q=".hash('ripemd160', $_POST['username']);
            $headers .= 'From: smeetvcom@gmail.com <smeetvcom@gmail.com>' . "\r\n";
            $gomail=mail($_POST['email'],'Please verify your email',$message,$headers);







        }
        header("Location:/thankyou/");
        echo "<p class=\"success\">Successfully registered.</p>";

   }
}

        drawHeader('remote control',$u,'1');
?>


<form method="post" id="signup" action="<?=$_SERVER['PHP_SELF']?>">
    <input type="hidden" name="process" value="1">


<?
if(!$_SESSION['invite']){?>

<p style="color:red">New registrations are accepted with personalized invite code only at the moment.</p>
<p>To request invite code please reply to <a href="http://twitter.com/smeetv">@smeetv</a> on twitter and ask for the invite code. We'll direct message or reply back with your invite code.</p>


    <p><label for="username" class="fleft w100">invite code:</label> <input type="text" name="i" id="invite_value" value=""> <span class="aside"><button class="fno" id="invite">Next</button></span></p>

<?}else{?>
    <p><label for="username" class="fleft w100">username:</label> <input type="text" name="username" id="username" value="<?=$_POST['username']?>"> <span class="aside">(alphanumeric charachters)</span></p>

    <p><label for="password" class="fleft w100">password:</label> <input type="password" name="password" id="password" value=""> <span class="aside">(alphanumeric charachters)</span></p>

    <p><label for="email" class="fleft w100">email:</label> <input type="text" name="email" id="email" value="<?=$_POST['email']?>"></p>

    <p><label class="fleft w100">&nbsp;</label><input type="submit" value="Sign up"></p>

<?}?>
</form>



<script src="/js/jquery.js"></script>
<script>
        $(document).ready(function(e){
            $('.destroy_notification').live('click',function(ev){
                $(this).closest('.notification').filter(':first').remove();
                ev.preventDefault();
            });

            $('#invite').live('click',function(){
                check_invite_code($("#invite_value").val());
                return false;
            });
        });


        function check_invite_code(id) {
                $.ajax({
                        type: 'POST',
                        url: '/etc/invite/'+id,
                        data: { 'op':'ajax', 'i':$("input[name=i]","#signup").val() },
                        dataType: 'html',
                        success: function(response, textStatus) {
                            if(response=="1"){
                                location.reload();
                            } else {
                                $('body').append('<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>The code you supplied is invalid or has already been used up.<a href="" class="destroy_notification"><span class="ui-icon close_small fright">&nbsp;</span></a></div>');
                            }
                        },
                        errr: function(xhr, textStatus, errorThrown) {
                            // errorr
                        }
                });
        }




</script>









<?require_once('footer.php');?>

<?
session_start();
require_once('func.php');

if(isUserLoggedIn()==1){
$u=true;
} else {
       header('Location:index.php');

}


require_once('header.php');

$_POST['username']=advancedClean(3,$_POST['username']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['channels']=advancedClean(3,$_POST['channels']);
$_POST['email']=advancedClean(3,$_POST['email']);
$_POST['process']=advancedClean(3,$_POST['process']);


connect2db();
$query="
select * from accounts where id='{$_SESSION['id']}' and username='{$_SESSION['username']}'
";
$go=mysql_query($query);
$get=mysql_fetch_array($go);


?>


<p>
username: <?=$_SESSION['username']?> (<?=$_SESSION['id']?>)<br>
email: <?=$get['email']?>
</p>
<form method="post" action="settings.php">
<label>
<br>Hahtags (separate by comma)<br>
<textarea name="smeetv_hashtags"><?=$get['smeetv_hashtags']?></textarea>
</label>

<label>
<br><input type="checkbox" name="smeetv_hashtags_force" value=1 <?if($get['smeetv_hashtags_force']==1){echo " checked "; }?>> Restrict search to hashtags only<br>
<small>if not checked, indexer will spider entire twits for search keywords</small>
</label>


<label>
<br>Size
<br>s<input type="radio" name="smeetv_img_size" value="s" <?if($get['smeetv_img_size']=='s'){?>checked<?}?>>  m<input type="radio" name="smeetv_img_size" value="m" <?if($get['smeetv_img_size']=='m'){?>checked<?}?>>  l<input type="radio" name="smeetv_img_size" value="l" <?if($get['smeetv_img_size']=='l'){?>checked<?}?>>
</label>

<label>
<br>Text
<br>on<input type="radio" name="smeetv_text" value="1" <?if($get['smeetv_text']=='1'){?>checked<?}?>> off<input name="smeetv_text" type="radio" value="0" <?if($get['smeetv_text']=='0'){?>checked<?}?>>
</label>

<label>
<br>No. of pics
<br>10<input type="radio" name="smeetv_img_num" value="10" <?if($get['smeetv_img_num']=='10'){?>checked<?}?>>  20<input type="radio" name="smeetv_img_num" value="20" <?if($get['smeetv_img_num']==20){?>checked<?}?>>  50<input type="radio" name="smeetv_img_num" value="50" <?if($get['smeetv_img_num']=='50'){?>checked<?}?>>
</label>

<label>
<br><input type="checkbox" name="ad" value="1" <?if($get['ad']==1) echo " checked "; ?>> Advertisements

</label>

<label>
<br>
<input type="checkbox" name="refetch" value="1">
Re-fetch twits without history-time limit 
</label>



    <input type="submit" value="Save">
</form>

<?require_once('footer.php');?>

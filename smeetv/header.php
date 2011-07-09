<?function drawHeader($title,$user_loggedin=0,$show_menu=0,$which_page="",$title_in_header=0,$user_info=0){?>
<!DOCTYPE html> 
<html lang="en"> 
<html> 
<head>
<title><?=trim(removeUrlFromString($title))?> | @smeetv</title>
<link type="text/css" rel="stylesheet" media="all" href="//fsdn1.somewhe.com/smeetv/c/grid.css" >
<link type="text/css" rel="stylesheet" id="light" media="all" href="//fsdn1.somewhe.com/smeetv/c/light.css" >
<link media="only screen and (max-device-width: 480px)" href="//fsdn1.somewhe.com/smeetv/c/iphone.css" type="text/css" rel="stylesheet">
<?if($_SESSION['idhash']){?>
<link rel="stylesheet" type="text/css" href="/rss/<?=$_SESSION['idhash']?>"> 
<?}?>
<?if($_SESSION['idhash']){?><link rel="alternate" type="application/rss+xml" title="your feed" href="/rss/<?=$_SESSION['idhash']?>"><?}?>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-22892692-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body class="<?=$which_page?>">
<header id="topnav">
<h1 class="fleft" title="smeetv -- tv window into twitter universe"><a href="/">smee<span>tv</span></a></h1>
<?if(!$title_in_header==0){?>
<h2 class='title_in_header'><?=$title_in_header?></h2>
<?}?>
<nav class="fleft">
<?if($user_loggedin && $show_menu==1){?>
<span class="dim ui-icon" id="dim_bg" title='Dim lights'></span>
<a class="tv" href="/" class="gotoremotecontrol gototrigger" rel="gotoremotecontrol">tv</a>
<a class="settings" href="/smeetv/profile.php" class="gotosettings gototrigger" rel="gotosettings">settings</a>
<a class="help"  href="" class="gotohelp gototrigger" rel="gotohelp">help</a>


<a class="featuredhashtags" id="featuredhashtags" href="">featured hashtags</a>


<?
}elseif($user_loggedin==0 && $show_menu==1){
?>

<a class="register" href="/smeetv/signup.php">register</a>


<a class="login" href="/smeetv/login.php">login</a> 




<?}?>
</nav>


<?
if($user_info) {
echo $user_info;
}
?>




</header>
<?}?>

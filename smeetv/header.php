<?function drawHeader($title,$user_loggedin=0,$show_menu=0){?>
<!DOCTYPE html> 
<html lang="en"> 
<html> 
<head>
<title><?=$title?> | @smeetv</title>
<link type="text/css" rel="stylesheet" media="all" href="/c/grid.css" >
<link type="text/css" rel="stylesheet" id="light" media="all" href="/c/light.css" >
<link rel="stylesheet" type="text/css" href="/rss/<?=$_SESSION['idhash']?>"> 
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
<body>
<header id="topnav">
<h1 title="smeetv -- tv window into twitter universe"><a href="/">smee<span>tv</span></a></h1><a href="http://smeetv.com/img/Ivid" title="Feature image -- David Cameron welcomes Hillary Clinton to Number 10 ahead of the Libya Conference"><sup title="alpha version -- unpredictable behavior expected">&alpha;</sup></a>
&mdash;
<nav>
<?if($user_loggedin && $show_menu==1){?>
<a href="/" class="gotoremotecontrol gototrigger" rel="gotoremotecontrol">tv</a> |
<a href="/smeetv/profile.php" class="gotosettings gototrigger" rel="gotosettings">settings</a> |
<a href="" class="gotohelp gototrigger" rel="gotohelp">help</a> |
<a href="/smeetv/logout.php">logout</a>
<a href="/smeetv/fetch_twits.php" title='Force flush user twits table'>&#9851;</a> <a href="status.php" title="Status data">&permil;</a>


<a id="featuredhashtags" href="">Featured hashtags</a>


<?
}elseif($user_loggedin==0 && $show_menu==1){
?>

<a href="/smeetv/signup.php">register</a> |


<a href="/smeetv/login.php">login</a>

<?}?>
</nav>

</header>
<?}?>

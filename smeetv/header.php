<?function drawHeader($title,$u=0,$m=0){?>
<!DOCTYPE html> 
<html lang="en"> 
<html> 
<head>
<title><?=$title?> | @smeetv</title>
<link type="text/css" rel="stylesheet" media="all" href="/smeetv/smeetv/grid.css" >
<link rel="stylesheet" type="text/css" href="/tv/rss/<?=$_SESSION['idhash']?>"> 
<?if($_SESSION['idhash']){?><link rel="alternate" type="application/rss+xml" title="your feed" href="/tv/rss/<?=$_SESSION['idhash']?>"><?}?>

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
<h1 title="smeetv -- tv window into twitter universe">smee<span>tv</span></h1><sup title="alpha version -- unpredictable behavior expected">&alpha;</sup>
&mdash;
<?if($u && $m==1){?>
<a href="/smeetv/smeetv/tv/" class="gotoremotecontrol gototrigger" rel="gotoremotecontrol">tv</a> |
<a href="/smeetv/smeetv/profile.php" class="gotosettings gototrigger" rel="gotosettings">settings</a> |
<a href="" class="gotohelp gototrigger" rel="gotohelp">help</a> |
<a href="/smeetv/smeetv/logout.php">logout</a>
<a href="http://fs.f1vlad.com/smeetv/smeetv/fetch_twits.php" title='Force flush user twits table'>&#9851;</a> <a href="status.php" title="Status data">&permil;</a>


<a id="featuredhashtags" href="">Featured hashtags</a>


<?
}elseif($u==0 && $m==1){
?>

<a href="/smeetv/smeetv/signup.php">register</a> |


<a href="/smeetv/smeetv//login.php">login</a>

<?}?>


</header>
<?}?>

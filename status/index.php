<?
    require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');
    connect2db();


    $arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));
    $op=$arr[count($arr)-1];
  
    $query="select count(*) from twits_dump";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    echo "<span class='fni'>".number_format($get[0])." photographs fetched <span class='details'>as of ";

    $query="select timestamp from twits_dump order by id desc limit 0,1";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $l=time()-$get[0];
    echo $l." seconds ago</span><span>";


if(!$op=="ajax"){
?><html>
<head>
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
<br>
<iframe src="/status/stat/index.php" style="border:none;overflow:hidden;width:580px;height:540px;"></iframe>
</body></html>
<?}?>

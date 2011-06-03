<?
include('./generate/_incl.txt');


    $arr=explode("/",$_SERVER['REQUEST_URI']);

if(!$arr[count($arr)-1]=="ajax"){
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

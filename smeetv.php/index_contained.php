<?php

/* DM SCHEME *//*
CREATE TABLE `twits_dump` (
    `tweetid` varchar(20) NOT NULL,
    `timestamp` int(20) NOT NULL,
    `tweet` varchar(160) NOT NULL,
    PRIMARY KEY  (`tweetid`)
);
*/

connect2db();
$doc = new DOMDocument();
$searchtype[]="yfrog.com";
$searchtype[]="tweetphoto.com";
$searchtype[]="twitgoo.com";
$searchtype[]="picktor.com";
$searchtype[]="flic.kr";
$cnt=count($feed);

$feedik='http://search.twitter.com/search.rss?lang=en&q='.$searchtype[rand(0,$cnt-1)].'&show_user=true&since_id='.getSinceId($searchtype[rand(0,$cnt-1)],$cnt);
$doc->load($feedik);
$arrFeeds = array();
function connect2db() {mysql_pconnect("localhost","root","");mysql_select_db("smeetv10");}
function advancedClean($level,$string){if($level==3){  /* POWER CLEAN */ $string=trim($string);$string=strip_tags($string);$string=htmlspecialchars($string);$string=addslashes($string);}elseif($level==2){   /* MEDIUM CLEAN */}elseif($level==1){   /* BASIC CLEAN*/$string==trim($string);$string=addslashes($string);}return $string;}
function getSinceId($st,$cn){$query="select tweetid from twits_dump where tweet like '%".$searchtype[rand(0,$cnt-1)]."%' order by tweetid desc limit 0,1 ";$go=mysql_query($query);$get=mysql_fetch_array($go);$since=$get[0];return $since;}

foreach ($doc->getElementsByTagName('item') as $node) {
        $a=substr(strstr($node->getElementsByTagName('link')->item(0)->nodeValue,'statuses/'),9,100);


        $b=advancedClean(3,$node->getElementsByTagName('title')->item(0)->nodeValue);
        $query="insert into twits_dump(tweetid,timestamp,tweet) values ('".$a."','".time()."','".$b."')";
        $go=mysql_query($query);
        $result.= $query."<br>";
}

?><!DOCTYPE html><html lang="en"><head>
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
</head><body><?echo "<pre>$result</pre>";?></body></html>

<?php
$doc = new DOMDocument();
//$doc->load('http://twitter.com/statuses/public_timeline.rss');
//$feed[]='http://search.twitter.com/search.rss?lang=en&q=twitpic';

//$feed[]='http://search.twitter.com/search.rss?lang=en&q=twitpic.%20OR%20plixi.%20OR%20flic.kr%20OR%20yfrog.%20OR%20tweetphoto.%20OR%20twitgoo.%20OR%20picktor.%20OR%20youtube.%20OR%20youtu.be%20OR%20twitvid.%20OR%20movapic.%20OR%20img.ly%20OR%20upic.me%20OR%20vimeo.%20OR%20fotki.yandex.ru%20OR%20imgur.com';

//$feed[]='http://search.twitter.com/search.rss?lang=en&q=twitpic.%20OR%20plixi.%20OR%20flic.kr%20OR%20yfrog.%20OR%20tweetphoto.%20OR%20twitgoo.%20OR%20picktor.%20OR%20twitvid.%20OR%20movapic.%20OR%20img.ly%20OR%20upic.me%20OR%20fotki.yandex.ru%20OR%20imgur.com%20OR%20lockerz.com';
$feed[]='http://search.twitter.com/search.rss?lang=en&q=twitpic.%20OR%20plixi.%20OR%20flic.kr%20OR%20yfrog.%20OR%20tweetphoto.%20OR%20twitgoo.%20OR%20picktor.%20OR%20twitvid.%20OR%20movapic.%20OR%20img.ly%20OR%20upic.me%20OR%20fotki.yandex.ru%20OR%20imgur.com%20OR%20lockerz.com%20OR%20picplz.com';


//echo $feed[0];


$cnt=count($feed);



//echo $feed[rand(1,$cnt)];
$doc->load($feed[rand(0,$cnt-1)]);



$arrFeeds = array();
require_once('../smeetv/func.php');
connect2db();
$result="";

foreach ($doc->getElementsByTagName('item') as $node) {

/*
    if(
    !strpos(!$node->getElementsByTagName('title')->item(0)->nodeValue,"twitpic") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"yfrog.com") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"tweetphoto.com") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"twitgoo.com") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"picktor.com") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"flic.kr") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"plixi.com") === false ||
    
    //!strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"twitvid") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"youtube.com/watch") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"youtu.be") === false
    ){
        $insert = 1;
    } else {
        $insert = false;
    }
*/
$insert = 1;


    $q="select id from twits_dump where aid='".base64_encode($node->getElementsByTagName('link')->item(0)->nodeValue)."'";
    $g=mysql_query($q);
    $isDuplicate=mysql_num_rows($g);

//echo $insert . ' -   ' . $isDuplicate."<br>";

    if(!$insert === false && $isDuplicate==0) {
	$itemRSS = array (
		'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
		'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
		'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
		);
	#array_push($arrFeeds, $itemRSS);
        $query="
            insert into twits_dump
            (content,link,date,timestamp,aid)
            values
            ('".advancedClean(3,$node->getElementsByTagName('title')->item(0)->nodeValue)."','".advancedClean(3,$node->getElementsByTagName('link')->item(0)->nodeValue)."','".advancedClean(3,$node->getElementsByTagName('pubDate')->item(0)->nodeValue)."','".time()."','".base64_encode($node->getElementsByTagName('link')->item(0)->nodeValue)."')
        ";
        $go=mysql_query($query);
        $result.= advancedClean(3,$node->getElementsByTagName('title')->item(0)->nodeValue)."<br> \n";

    }
}

#+-----------+--------------+------+-----+---------+----------------+
#| Field     | Type         | Null | Key | Default | Extra          |
#+-----------+--------------+------+-----+---------+----------------+
#| id        | int(11)      | NO   | PRI | NULL    | auto_increment | 
#| content   | varchar(255) | NO   |     |         |                | 
#| link      | varchar(255) | NO   |     |         |                | 
#| date      | varchar(255) | NO   |     |         |                | 
#| timestamp | varchar(255) | NO   |     |         |                | 
#+-----------+--------------+------+-----+---------+----------------+

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="refresh" content="24;url=http://fs.f1vlad.com/smeetv/smeetv.php/index.onequery.php">
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
<?
echo "<pre>";
//print_r($arrFeeds);
echo $result;
echo "</pre>";
?>
</body></html>

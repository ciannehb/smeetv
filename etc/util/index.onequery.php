<?php
$doc = new DOMDocument();

$feed[]='http://search.twitter.com/search.rss?lang=en&q=twitpic.%20OR%20plixi.%20OR%20flic.kr%20OR%20yfrog.%20OR%20tweetphoto.%20OR%20twitgoo.%20OR%20picktor.%20OR%20twitvid.%20OR%20movapic.%20OR%20img.ly%20OR%20dailybooth.com';
$feed[]='http://search.twitter.com/search.rss?lang=en&q=upic.me%20OR%20fotki.yandex.ru%20OR%20imgur.com%20OR%20lockerz.com%20OR%20picplz.com%20OR%20instagr.am%20OR%20tinypic.com%20OR%20lulzimg.com%20OR%20min.us%20OR%20pbckt.com';
$feed[]='http://search.twitter.com/search.rss?lang=en&q=photobucket.com%20OR%20imageshack.us%20OR%20fbcdn.net/hphotos%20OR%20http://imagehost.org';
$feed[]='http://search.twitter.com/search.rss?lang=en&q=deviantart.com%20OR%20etsy.me%20OR%20photozou.jp%20OR%20min.us%20OR%20iimmgg.com/image';


$doc->load($feed[$_GET['priority']]);


require_once('../../smeetv/func.php');
$smeetvdb=connect2db();
$qi="select id from twits_dump order by id desc limit 0,1";
$gi=mysql_query($qi);
$gi=mysql_fetch_array($gi);
$result="";
foreach ($doc->getElementsByTagName('item') as $node) {
    $insert = 1;
    //$q="select id from twits_dump where aid='".base64_encode($node->getElementsByTagName('link')->item(0)->nodeValue)."'";
    $q="select id from twits_dump where link='".$node->getElementsByTagName('link')->item(0)->nodeValue."' and id>".($gi[0]-4000);
//echo $q;
    $g=mysql_query($q);
    $isDuplicate=mysql_num_rows($g);

//$isDuplicate=0;



    if(!$insert === false && !$isDuplicate>0) {


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

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="refresh" content="999999;url=http://fs.f1vlad.com/smeetv/smeetv.php/index.onequery.php">
</head>
<body>
<?
echo "<pre>";
//print_r($arrFeeds);
echo $result;
echo "</pre>";
disconnectFromDb($smeetvdb);
?>
</body></html>

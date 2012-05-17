<?php
$doc = new DOMDocument();

/*
$feed[]='http://search.twitter.com/search.rss?lang=all&q=filter%3Alinks%20twitpic.%20OR%20filter%3Alinks%20plixi.%20OR%20filter%3Alinks%20flic.kr%20OR%20filter%3Alinks%20yfrog.%20OR%20filter%3Alinks%20tweetphoto.%20OR%20filter%3Alinks%20twitgoo.%20OR%20filter%3Alinks%20picktor.%20OR%20filter%3Alinks%20movapic.%20OR%20filter%3Alinks%20img.ly%20OR%20filter%3Alinks%20dailybooth.com';
$feed[]='http://search.twitter.com/search.rss?lang=all&q=filter%3Alinks%20upic.me%20OR%20filter%3Alinks%20fotki.yandex.ru%20OR%20filter%3Alinks%20imgur.com%20OR%20filter%3Alinks%20lockerz.com%20OR%20filter%3Alinks%20picplz.com%20OR%20filter%3Alinks%20instagr.am%20OR%20filter%3Alinks%20tinypic.com%20OR%20filter%3Alinks%20lulzimg.com%20OR%20filter%3Alinks%20pbckt.com';
$feed[]='http://search.twitter.com/search.rss?lang=all&q=filter%3Alinks%20photobucket.com%20OR%20filter%3Alinks%20imageshack.us%20OR%20filter%3Alinks%20fbcdn.net/hphotos%20OR%20filter%3Alinks%20imagehost.org';

$feed[]='http://search.twitter.com/search.rss?lang=all&q=filter%3Alinks%20deviantart.com%20OR%20filter%3Alinks%20photozou.jp%20OR%20filter%3Alinks%20min.us%20OR%20filter%3Alinks%20iimmgg.com/image';

$feed[]='http://search.twitter.com/search.rss?lang=all&q=filter%3Alinks%20.png%20OR%20filter%3Alinks%20.gif%20OR%20filter%3Alinks%20.jpg%20OR%20filter%3Alinks%20.jpeg';
*/

$feed[]='http://search.twitter.com/search.rss?lang=all&q=%20twitpic.%20OR%20%20plixi.%20OR%20%20flic.kr%20OR%20%20yfrog.%20OR%20%20tweetphoto.%20OR%20%20twitgoo.%20OR%20%20picktor.%20OR%20%20movapic.%20OR%20%20img.ly%20OR%20%20dailybooth.com';
$feed[]='http://search.twitter.com/search.rss?lang=all&q=%20upic.me%20OR%20%20fotki.yandex.ru%20OR%20%20imgur.com%20OR%20%20lockerz.com%20OR%20%20picplz.com%20OR%20%20instagr.am%20OR%20%20tinypic.com%20OR%20%20lulzimg.com%20OR%20%20pbckt.com';
$feed[]='http://search.twitter.com/search.rss?lang=all&q=%20photobucket.com%20OR%20%20imageshack.us%20OR%20%20fbcdn.net/hphotos%20OR%20%20imagehost.org%20OR%20%20photobzz.com/photo%20OR%20%20pinterest.com%2Fpin';

$feed[]='http://search.twitter.com/search.rss?lang=all&q=%20deviantart.com%20OR%20%20photozou.jp%20OR%20%20min.us%20OR%20%20iimmgg.com/image%20OR%20%20cinemagr.am%2Fshow%20OR%20%20gifpal.com%20OR%20%20say.ly%20OR%20%204sq%20%5Bpic%5D';

$feed[]='http://search.twitter.com/search.rss?lang=all&q=%20.png%20OR%20%20.gif%20OR%20%20.jpg%20OR%20%20.jpeg%20OR%20%20gifboom.com%20OR%20%20imgclean.com%20OR%20%20quickmeme.com%20OR%20%20qkme.me';



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
//echo $_SERVER['PHP_SELF']." pushed\n";

disconnectFromDb($smeetvdb);
?>
</body></html>

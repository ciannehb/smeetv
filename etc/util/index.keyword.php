<?php
$doc = new DOMDocument();
//$doc->load('http://twitter.com/statuses/public_timeline.rss');
//$feed[]='http://search.twitter.com/search.rss?lang=en&q=twitpic';
require_once('../../smeetv/func.php');
    $smeetvdb=connect2db();








if($_GET['priority']) {
    $opt=$_GET['priority'];
    $query="select keyword,counter from keywords order by counter desc limit ".($_GET['priority']-1).",1";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $opt=$get[0];
    $feed[]="http://search.twitter.com/search.rss?lang=en&q=".$opt."%20twitpic.%20OR%20".$opt."%20plixi.com%20OR%20".$opt."%20yfrog.com%20OR%20".$opt."%20instagr.am";

} else {
    $opt=$_GET['opt'];
    $feed[]="http://search.twitter.com/search.rss?lang=en&q=".$opt."%20twitpic.%20OR%20".$opt."%20plixi.com%20OR%20".$opt."%20yfrog.com%20OR%20".$opt."%20instagr.am";
}


//$feed[]='http://search.twitter.com/search.rss?lang=en&q='.$opt.' twitpic.com%20OR%20'.$opt.' plixi.com%20OR%20'.$opt.' yfrog.com';

echo $feed[0];


$cnt=count($feed);



echo $feed[rand(1,$cnt)];
$doc->load($feed[rand(0,$cnt-1)]);



$arrFeeds = array();
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

echo $insert . ' -   ' . $isDuplicate."<br>";

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
        $result.= $query."<br>";

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
<meta http-equiv="refresh" content="9999999999999;url=http://fs.f1vlad.com/smeetv/smeetv.php/index.keyword.php?opt=<?=$_GET['opt']?>">
</head>
<body>
<?
echo "<pre>";
print_r($arrFeeds);
echo "</pre>";
//echo $_SERVER['PHP_SELF']." pushed\n";

disconnectFromDb($smeetvdb);

?>
</body></html>

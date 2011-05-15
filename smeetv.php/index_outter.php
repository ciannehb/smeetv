<?php


$doc = new DOMDocument();
//$doc->load('http://twitter.com/statuses/public_timeline.rss');
$feed[]='http://search.twitter.com/search.rss?lang=en&q=yfrog';
$feed[]='http://search.twitter.com/search.rss?lang=en&q=tweetphoto.com';
$feed[]='http://search.twitter.com/search.rss?lang=en&q=twitgoo.com';
$feed[]='http://search.twitter.com/search.rss?lang=en&q=picktor.com';
$feed[]='http://search.twitter.com/search.rss?lang=en&q=flic.kr';

$cnt=count($feed);


echo $feed[rand(1,$cnt)];
$doc->load($feed[rand(0,$cnt)]);



//$doc->load($feed[rand(1,$cnt)]);












$arrFeeds = array();

function connect2db() {
    mysql_pconnect("173.201.217.70","smeetv10","sm3310TV");
    mysql_select_db("smeetv10");
}

function advancedClean($level,$string){
                if($level==3){  // POWER CLEAN
                        $string=trim($string);
                        $string=strip_tags($string);
                        $string=htmlspecialchars($string);
                        $string=addslashes($string);
        }elseif($level==2){   // MEDIUM CLEAN

                }elseif($level==1){   // BASIC CLEAN
                        $string==trim($string);
                        $string=addslashes($string);
                }

                return $string;
}



connect2db();
$result="";

foreach ($doc->getElementsByTagName('item') as $node) {
    //$insert = strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"twitpic");


    if(
   

    !strpos(!$node->getElementsByTagName('title')->item(0)->nodeValue,"twitpic") === false ||


    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"yfrog.com") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"tweetphoto.com") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"twitgoo.com") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"picktor.com") === false ||
    !strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"flic.kr") === false
    
/*
    strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"img.ly") ||
    strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"imgur.com") ||
    strpos($node->getElementsByTagName('title')->item(0)->nodeValue,"flic.kr")
*/
    ){
        $insert = 1;
    } else {
        $insert = false;
    }



    $q="select id from twits_dump_cl where aid='".base64_encode($node->getElementsByTagName('link')->item(0)->nodeValue)."'";
    $g=mysql_query($q);
    $isDuplicate=mysql_num_rows($g);

echo "debug: ".$insert. " ". $isDuplicate. " ".$node->getElementsByTagName('title')->item(0)->nodeValue;

    if(!$insert === false && $isDuplicate==0) {
	$itemRSS = array (
		'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
		'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
		'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
		);
	#array_push($arrFeeds, $itemRSS);
        $query="
            insert into twits_dump_cl
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
<meta http-equiv="refresh" content="24;url=http://fs.f1vlad.com/smeetv.php/">
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

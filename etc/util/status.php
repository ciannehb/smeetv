<?
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');

    connect2db();
 
















   
    $query="select count(*) from twits_dump";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    echo "<small>total twits fetched: ".$get[0]."</small><br>";

    $query="select timestamp from twits_dump order by id desc limit 0,1";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $l=time()-$get[0];
    echo "<small>last fetched twit: ".$l." seconds ago</small>";


    echo "<pre>";
    if($_GET['hashtag']){
        echo "<br><hr>";
        echo "<h3>Showing twits with hashtag '{$_GET['hashtag']}'</h3>";
        $query="select count(*) from twits_dump where content like '%#{$_GET['hashtag']}%' and content like '%twitpi%'";
        $go=mysql_query($query);
        for($i=0;$i<mysql_num_rows($go);$i++){
            $get=mysql_fetch_array($go);
            echo $get[0]."<br>";;
            
        }
    }
    echo "</pre>";




$query="select count(*) from twits_dump where content like '%twitpic%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitpic=$get[0];

$query="select count(*) from twits_dump where content like '%yfrog%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$yfrog=$get[0];

$query="select count(*) from twits_dump where content like '%tweetphoto%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$tweetphoto=$get[0];

$query="select count(*) from twits_dump where content like '%twitgoo%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitgoo=$get[0];

$query="select count(*) from twits_dump where content like '%picktor%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$picktor=$get[0];

$query="select count(*) from twits_dump where content like '%flickr.com%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$flickr0=$get[0];



$query="select count(*) from twits_dump where content like '%flic.kr%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$flickr=$get[0]+$flickr0;

$query="select count(*) from twits_dump where content like '%twitvid%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitvid=$get[0];

$query="select count(*) from twits_dump where content like '%youtube.com%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$youtube1=$get[0];

$query="select count(*) from twits_dump where content like '%youtu.be%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$youtube2=$get[0];

$query="select count(*) from twits_dump where content like '%plixi.com%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$plixi=$get[0];

$query="select count(*) from twits_dump where content like '%movapic.com%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$movapic=$get[0];

$query="select count(*) from twits_dump where content like '%img.ly%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$imgly=$get[0];

$query="select count(*) from twits_dump where content like '%upic.me%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$upickme=$get[0];

$query="select count(*) from twits_dump where content like '%fotki.yandex.ru%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$fotkiyandexru=$get[0];

$query="select count(*) from twits_dump where content like '%vimeo.%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$vimeo=$get[0];

$query="select count(*) from twits_dump where content like '%imgur.%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$imgur=$get[0];


$query="select count(*) from twits_dump where content like '%lockerz.com%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$lockerz=$get[0];

$query="select count(*) from twits_dump where content like '%picplz.com%'";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$picplz=$get[0];




$total_img = $twitpic + $yfrog + $tweetphoto + $twitgoo + $picktor + $flickr + $plixi + $movapic + $imgly + $upickme + $fotkiyandexru + $imgur + $lockerz + $picplz;  
$total_vid = $twitvid + $youtube1 + $youtube2 + $vimeo;



?>
<h4>Distribution of image hostings</h4>
<img src='http://chart.apis.google.com/chart?chco=FF0000|00FF00|0000FF&cht=p3&chs=350x150&chd=t:<?=$twitpic?>,<?=$yfrog?>,<?=$tweetphoto?>,<?=$twitgoo?>,<?=$picktor?>,<?=$flickr?>,<?=$twitvid?>,<?=$youtube1+$youtube2?>,<?=$plixi?>,<?=$movapic?>,<?=$imgly?>,<?=$upickme?>,<?=$fotkiyandexru?>,<?=$vimeo?>,<?=$imgur ?>,<?=$lockerz?>,<?=$picplz?>&chl=twitpic|yfrog|tweetphoto|twitgoo|picktor|flic.kr|twitvid|youtube|plixi|movapic|imgly|upic.me|fotki.yandex.ru|vimeo|imgur|lockerz|picplz'">
<?










$l12=time()-34200;

$query="select count(*) from twits_dump where content like '%twitpic%' and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitpic=$get[0];

$query="select count(*) from twits_dump where content like '%yfrog%' and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$yfrog=$get[0];

$query="select count(*) from twits_dump where content like '%tweetphoto%' and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$tweetphoto=$get[0];

$query="select count(*) from twits_dump where content like '%twitgoo%' and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitgoo=$get[0];

$query="select count(*) from twits_dump where content like '%picktor%' and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$picktor=$get[0];


$query="select count(*) from twits_dump where content like '%flickr.com%' and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$flickr0=$get[0];



$query="select count(*) from twits_dump where content like '%flic.kr%' and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$flickr=$get[0]+$flickr0;

$query="select count(*) from twits_dump where content like '%youtub%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$youtube1=$get[0];
$query="select count(*) from twits_dump where content like '%youtu.be%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$youtube2=$get[0];


$query="select count(*) from twits_dump where content like '%twitvid%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitvid=$get[0];

$query="select count(*) from twits_dump where content like '%plixi%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$plixi=$get[0];

$query="select count(*) from twits_dump where content like '%movapic.com%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$movapic=$get[0];

$query="select count(*) from twits_dump where content like '%img.ly%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$imgly=$get[0];

$query="select count(*) from twits_dump where content like '%upic.me%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$upicme=$get[0];

$query="select count(*) from twits_dump where content like '%fotki.yandex.ru%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$fotkiyandexru=$get[0];

$query="select count(*) from twits_dump where content like '%vimeo.%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$vimeo=$get[0];

$query="select count(*) from twits_dump where content like '%imgur.%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$imgur=$get[0];


$query="select count(*) from twits_dump where content like '%lockerz.com%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$lockerz=$get[0];

$query="select count(*) from twits_dump where content like '%picplz.com%'  and timestamp > $l12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$picplz=$get[0];














?>
<h4>Distribution of image hostings last 12 hours</h4>
<img src='http://chart.apis.google.com/chart?cht=p3&chs=350x150&chd=t:<?=$twitpic?>,<?=$yfrog?>,<?=$tweetphoto?>,<?=$twitgoo?>,<?=$picktor?>,<?=$flickr?>,<?=$youtube1+$youtube2?>,<?=$twitvid?>,<?=$plixi?>,<?=$movapic?>,<?=$imgly?>,<?=$upicme?>,<?=$fotkiyandexru?>,<?=$vimeo?>,<?=$imgur?>,<?=$lockerz?>,<?=$picplz?>&chl=twitpic|yfrog|tweetphoto|twitgoo|picktor|flic.kr|youtube|twitvid|plixi|movapic|imgly|upic.me|fotki.yandex.ru|vimeo|imgur|lockerz|picplz'">
<?








$l1=time()-3600;

$query="select count(*) from twits_dump where content like '%twitpic%' and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitpic=$get[0];

$query="select count(*) from twits_dump where content like '%yfrog%' and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$yfrog=$get[0];

$query="select count(*) from twits_dump where content like '%tweetphoto%' and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$tweetphoto=$get[0];

$query="select count(*) from twits_dump where content like '%twitgoo%' and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitgoo=$get[0];

$query="select count(*) from twits_dump where content like '%picktor%' and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$picktor=$get[0];

$query="select count(*) from twits_dump where content like '%flickr.com%' and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$flickr0=$get[0];



$query="select count(*) from twits_dump where content like '%flic.kr%' and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$flickr=$get[0]+$flickr0;


$query="select count(*) from twits_dump where content like '%youtub%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$youtube1=$get[0];
$query="select count(*) from twits_dump where content like '%youtu.be%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$youtube1=$get[0];


$query="select count(*) from twits_dump where content like '%twitvid%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$twitvid=$get[0];

$query="select count(*) from twits_dump where content like '%plixi%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$plixi=$get[0];

$query="select count(*) from twits_dump where content like '%movapic%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$movapic=$get[0];

$query="select count(*) from twits_dump where content like '%imgly%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$imgly=$get[0];

$query="select count(*) from twits_dump where content like '%upic.me%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$upicme=$get[0];

$query="select count(*) from twits_dump where content like '%fotki.yandex.ru%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$fotkiyandexru=$get[0];

$query="select count(*) from twits_dump where content like '%vimeo.%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$vimeo=$get[0];

$query="select count(*) from twits_dump where content like '%imgur.%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$imgur=$get[0];


$query="select count(*) from twits_dump where content like '%lockerz.%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$lockerz=$get[0];

$query="select count(*) from twits_dump where content like '%picplz.com%'  and timestamp > $l1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$picplz=$get[0];










?>
<h4>Distribution of image hostings last 1 hour</h4>
<img src='http://chart.apis.google.com/chart?cht=p3&chs=350x150&chd=t:<?=$twitpic?>,<?=$yfrog?>,<?=$tweetphoto?>,<?=$twitgoo?>,<?=$picktor?>,<?=$flickr?>,<?=$twitvid?>,<?=$youtube1+$youtube2?>,<?=$plixi?>,<?=$movapic?>,<?=$imgly?>,<?=$upicme?>,<?=$fotkiyandexru?>,<?=$vimeo?>,<?=$imgur?>,<?=$lockerz?>,<?=$picplz?>&chl=twitpic|yfrog|tweetphoto|twitgoo|picktor|flic.kr|twitvid|youtube|plixi|movapic|imgly|upicme|fotki.yandex.ru|vimeo|imgur|lockerz|picplz'">

<?




?>
<h4>Images vs Videos</h4>



<img src="http://chart.apis.google.com/chart?cht=p3&chs=350x150&chd=t:<?=$total_img?>,<?=$total_vid?>&chl=images|videos">
<?







$t_24hrs_ago=time()-86400;
$t_48hrs_ago=time()-172800;
$t_72hrs_ago=time()-259200;

$query="select count(*) from twits_dump where timestamp > $t_24hrs_ago";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$t24=$get[0];

$query="select count(*) from twits_dump where timestamp > $t_48hrs_ago and timestamp < $t_24hrs_ago";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$t48=$get[0];

$query="select count(*) from twits_dump where timestamp > $t_72hrs_ago and timestamp < $t_48hrs_ago";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$t72=$get[0];


$query="select count(*) from twits_dump where timestamp > $t_72hrs_ago";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$t_last_3=$get[0];


$t00=ceil($t24+$t48+$t72/1.75);



?>
<br>
<h4>Twits with pics fetched last three days: <?=$t_last_3?></h4>

<img src="
http://chart.apis.google.com/chart?cht=bvg&chs=350x300&chd=t:<?=$t24?>,<?=$t48?>,<?=$t72?>&chxr=1,0,<?=$t00?>&chds=0,<?=$t00?>&chco=ff0000|ffa000|00ff00&chbh=65,0,35&chxt=x,y,x&chxl=0:|24hrs|-48\+24hrs|-72\+24hrs|2:||||&chxs=2,000000,12&&chts=000000,20&chg=0,25,5,5
">




<?
/*
echo "
<br>
<h4>Volume of incoming twits with pics in last 24 hours.</h4>
";







$now=time();

$m1=$now-3600;
$m2=$m1-3600;
$m3=$m2-3600;
$m4=$m3-3600;
$m5=$m4-3600;
$m6=$m5-3600;
$m7=$m6-3600;
$m8=$m7-3600;
$m9=$m8-3600;
$m10=$m9-3600;
$m11=$m10-3600;
$m12=$m11-3600;
$m13=$m12-3600;
$m14=$m13-3600;
$m15=$m14-3600;
$m16=$m15-3600;
$m17=$m16-3600;
$m18=$m17-3600;
$m19=$m18-3600;
$m20=$m19-3600;
$m21=$m20-3600;
$m22=$m21-3600;
$m23=$m22-3600;
$m24=$m23-3600;



$query="select count(*) from twits_dump where timestamp > $m1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h1=$get[0];

$query="select count(*) from twits_dump where timestamp > $m2 and timestamp < $m1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h2=$get[0];

$query="select count(*) from twits_dump where timestamp > $m3 and timestamp < $m2";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h3=$get[0];


$query="select count(*) from twits_dump where timestamp > $m4 and timestamp < $m3";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h4=$get[0];


$query="select count(*) from twits_dump where timestamp > $m5 and timestamp < $m4";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h5=$get[0];


$query="select count(*) from twits_dump where timestamp > $m6 and timestamp < $m5";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h6=$get[0];


$query="select count(*) from twits_dump where timestamp > $m7 and timestamp < $m6";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h7=$get[0];


$query="select count(*) from twits_dump where timestamp > $m8 and timestamp < $m7";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h8=$get[0];


$query="select count(*) from twits_dump where timestamp > $m9 and timestamp < $m8";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h9=$get[0];


$query="select count(*) from twits_dump where timestamp > $m10 and timestamp < $m9";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h10=$get[0];


$query="select count(*) from twits_dump where timestamp > $m11 and timestamp < $m10";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h11=$get[0];


$query="select count(*) from twits_dump where timestamp > $m12 and timestamp < $m11";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h12=$get[0];


$query="select count(*) from twits_dump where timestamp > $m13 and timestamp < $m12";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h13=$get[0];


$query="select count(*) from twits_dump where timestamp > $m14 and timestamp < $m13";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h14=$get[0];


$query="select count(*) from twits_dump where timestamp > $m15 and timestamp < $m14";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h15=$get[0];


$query="select count(*) from twits_dump where timestamp > $m16 and timestamp < $m15";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h16=$get[0];


$query="select count(*) from twits_dump where timestamp > $m17 and timestamp < $m16";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h17=$get[0];


$query="select count(*) from twits_dump where timestamp > $m18 and timestamp < $m17";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h18=$get[0];


$query="select count(*) from twits_dump where timestamp > $m19 and timestamp < $m18";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h19=$get[0];


$query="select count(*) from twits_dump where timestamp > $m20 and timestamp < $m19";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h20=$get[0];


$query="select count(*) from twits_dump where timestamp > $m21 and timestamp < $m20";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h21=$get[0];


$query="select count(*) from twits_dump where timestamp > $m22 and timestamp < $m21";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h22=$get[0];


$query="select count(*) from twits_dump where timestamp > $m23 and timestamp < $m22";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h23=$get[0];


$query="select count(*) from twits_dump where timestamp > $m24 and timestamp < $m23";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h24=$get[0];


$query="select count(*) from twits_dump where timestamp > $m24 and timestamp < $m1";
$go=mysql_query($query);
$get=mysql_fetch_array($go);
$h00=$get[0]/10;




echo "<img src=\"http://chart.apis.google.com/chart?cht=lc&chd=t:
$h1,
$h2,
$h3,
$h4,
$h5,
$h6,
$h7,
$h8,
$h9,
$h10,
$h11,
$h12,
$h13,
$h14,
$h15,
$h16,
$h17,
$h18,
$h19,
$h20,
$h21,
$h22,
$h23,
$h24
&chco=FF0000&chls=

4,3,0

&chxt=x,y&&chs=300x150&chf=c,lg,45,ffffff,0,76A4FB,0.75|bg,s,EFEFEF&&chds=0,$h00&&chxr=1,0,$h00&chxl=0:|1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|2:||
\">";



*/







?>






































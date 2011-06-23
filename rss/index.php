<rss version="2.0">
<channel>
<title>undisclosed user rss feed @smeetv</title>
<link>http://www.smeetv.com</link>
<description>
You are looking at someone's rss feed @smeetv, for privacy reasons, identity of the owner of this feed is hidden
</description>
<language>en-us</language>
<copyright>Copyright 2011 smeetv.com
All Rights Reserved.</copyright>
<lastBuildDate>Tue, 12 Apr 2010 14:21:32 EST</lastBuildDate>
<ttl>240</ttl>
<image>
<url>http://a1.twimg.com/profile_images/1312636109/Screen_shot_2011-04-15_at_12.58.36_PM_reasonably_small.png</url>
<title>undisclosed user rss feed @smeetv</title>
<link>http://www.smeetv.com</link>
</image>

<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    //session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }

$smeetvdb=connect2db();


    $arr=explode("/",$_SERVER['REQUEST_URI']);
    $id=advancedClean(3,$arr[count($arr)-1]);
    $query="select id from accounts where idhash='".$id."'";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $query="select * from twits where uid='".$get[0]."' order by id desc limit 0,20";
    $go=mysql_query($query);


?>

<?
for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    $twusr=explode("/",$get['link']);
?>



<item>
<title>
@smeetv picture &sect;<?=alphaID($get['id'])?>
</title>
<link>http://smeetv.com/img/<?=alphaID($get['aid'])?></link>
<description>
<?=f1init_makeClickableLinks($get['content'])?>...<a href="http://smeetv.com/img/<?=alphaID($get['aid'])?>">&rarr;</a>
</description>
<pubDate><?=ceil(f1init_ago($get['timestamp'])/60)?> minutes ago</pubDate>
</item>
<?
}
disconnectFromDb($smeetvdb);
?>



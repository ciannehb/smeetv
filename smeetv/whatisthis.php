<?
require_once('func.php');
session_start();
connect2db();
/*
if(isUserLoggedIn()==1){
    header("Location:index.php");
    return;
}
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
drawHeader('remote control',$u,'1','whatisthis');
echo "<section id='content' class='grid_24'><section class='wrap'>";
?>


<h3>What is this?</h3>
<p>Smeetv is a fun explorer of photographs that people post on twitter. Each second, hundreds of photos are shared on twitter, most of which come with a story. Smeetv finds and delivers these mini-stories to you.</p>


<h3>How does it work?</h3>
<p><a href="/smeetv/signup.php">Sign up</a> for a smeetv account and you will be able to input your own <a href="http://en.wikipedia.org/wiki/Tag_(metadata)#Hashtags">hashtags</a>, <a href="http://en.wikipedia.org/wiki/Keyword_(linguistics)">keywords</a>, or complex phrases. Then, sit back and wait. Sometimes, within seconds, our crawler will be delivering photographs to you.</p>

<h3>What else?</h3>
<p>With smeetv account, even after you log off, our crawler monitors your search criteria 24/7. You can even graab <a href="http://en.wikipedia.org/wiki/RSS">rss feed</a> and monitor these photographs in your favorite rss reader. Through your "remote control" interface you'll be able to find more ways to explore.</p>



<?
echo "</section></section>";



require_once('footer.php');?>

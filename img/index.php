<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }

    $arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));

    if(strpos($arr[count($arr)-1],"?")) { /* addme widget leaves some garbage at the end of query string eg. http://fs.f1vlad.com/smeetv/smeetv/tv/img/2628?sms_ss=twitter&at_xt=4dc6c8054cd5144b,0 */
        $transport=explode("?",$arr[count($arr)-1]);
        $transport=$transport[0];
    } else {
        $transport=$arr[count($arr)-1];
    }

    //$id=advancedClean(3,$arr[count($arr)-1]);
    $id=$transport;


    if(strlen($id)==0) {
        header("HTTP/1.0 404 Not Found");
        return;
    }

    $smeetvdb=connect2db();
    $query="select id,content,timestamp,link,date from twits_dump where id=".alphaID($id,true);
    $go=mysql_query($query);

    if(mysql_num_rows($go)==0){
        $query="select id,content,timestamp,link,date from twits_dump_1 where id=".alphaID($id,true);
        $go=mysql_query($query);
    }
    
    if(mysql_num_rows($go)==0){
        $query="select id,content,timestamp,link,date from twits_dump_2 where id=".alphaID($id,true);
        $go=mysql_query($query);
    }
    

    $get=mysql_fetch_array($go);

    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');

    $title=$get['content'];

    //drawHeader(trim(substr($get['content'],stripos($get['content']," "),75)),$u);




if($u==true) {

$userinfo='';
$userinfo.='<section class="um"><nav role="usermenu"><a class="username" href="/u/'.$_SESSION["username"].'"><span class="ui-icon"></span> '.$_SESSION["username"].'</a></nav>';
$userinfo.='<nav role="usersecondarymenu">';
if($unverified) {
$userinfo.='
    <span title="Please verify your email to unlock these features." class="strike">(<abbr>anonpub</abbr>: <a href="#">channel</a>, <a href="#">rss</a>)</span>
';
}else{
$userinfo.='
<ul>
    <li><abbr title="anonpub: anonymous public channel and rss feed is your images stream, you can share publicly, it does not display your userid or username.">anonpub</abbr>:</li>
        <ul>
            <li><a href="/chan/'.$_SESSION['idhash'].'">channel</a></li>
            <li><a href="/rss/'.$_SESSION['idhash'].'">rss</a></li>
        </ul>
';
}
$userinfo.='<li><a class="logout" href="/smeetv/logout.php">logout</a></li>';
$userinfo.= '</ul></nav><section>';



}

$is_flagged=is_flagged($id);

    drawHeader(trim($title),$u,0,'','Picture &sect;<span class="id">'.$id.'<span>',$userinfo);
    echo "<section id='content' class='grid_24'><section class='wrap'>";
    if($is_flagged==1) 
        echo '<div class="notification error ontop"><span class="ui-icon exclamation">&nbsp;</span>Beware, this photograph was flagged by our users. For your safety, we have delayed displaying it by 30 seconds.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>';


?>




<script src="//smeetv.com/js/jquery-1.7.2.js"></script>
<script src="//smeetv.com/js/smeetv.js/smeetv.js"></script>
<script>
$(document).ready(function(){
    $('.destroy_notification').live('click',function(e){
        $(this).closest('.notification').filter(':first').remove();
        e.preventDefault();
    });

    $('article.twit').each(function(){
        var thisid=$(this).attr('id');
        // Sample content FOR TESTING
        // var content="testing http://t.co/doSdVNJT  http://t.co/rDZRqakz various urls http://twitpic.com/9ds0du yeah";
        //var content="d � Photobzz http://photobzz.com/photo/2478";
        var content = "<?php echo eregi_replace("\n"," ",$title) ?>";    
        $("#"+thisid).append(extractUrls2(content));// extracting urls and adding their path into new <img src="...">
        // push iterator
        gogo(thisid);
        // if $is_flagged==1 -- delay imagification
    });
});
</script>



<?
echo displayTwit($get['id'],$get['content'],$get['link'],$get['date'],$get['timestamp'],1,0);
?>

</section>
</section>

<?disconnectFromDb($smeetvdb);?>
<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>









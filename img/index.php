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

    drawHeader(trim($title),$u,0,'','Picture &sect;'.$id,$userinfo);
    echo "<section id='content' class='grid_24'><section class='wrap'>";
    if($is_flagged==1) 
        echo '<div class="notification error ontop"><span class="ui-icon exclamation">&nbsp;</span>Beware, this photograph was flagged by our users. For your safety, we have delayed displaying it by 30 seconds.<a href="" class="destroy_notification"><span class="ui-icon close_small">&nbsp;</span></a></div>';


?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script src="//fsdn1.somewhe.com/smeetv/js/smeetv.js/jquery.rotate.js"></script>
<script src="//fsdn3.somewhe.com/smeetv/js/jquery.ui.core.js"></script>
<script src="//fsdn3.somewhe.com/smeetv/js/jquery.ui.widget.js"></script>
<script src="//fsdn3.somewhe.com/smeetv/js/jquery.ui.mouse.js"></script>
<script src="//fsdn3.somewhe.com/smeetv/js/jquery.ui.draggable.js"></script>
<script src="//fsdn3.somewhe.com/smeetv/js/jquery.ui.resizable.js"></script> 
<script src="//smeetv.com/js/smeetv.js/smeetv.js"></script>



<script>
    $(document).ready(function(){
        $('.destroy_notification').live('click',function(e){
            $(this).closest('.notification').filter(':first').remove();
            e.preventDefault();
        });

        $( "#mainimg" ).resizable();





           // imagify
           $('#mainimg.squares.mainimg > article').each(function(){
               var content=$(this).html(),
                   thisid=$(this).attr('id');



    //if($get['flagged']==1) 

           <?if($is_flagged==1){?>setTimeout(function(){<?}?>
           e=imagify(content,thisid);
           <?if($is_flagged==1){?>},30000);<?}?>


               //imagify(content,thisid);
           });







        $('.rotate_left').click(function(){
            $('#mainimg img').rotate(-90);
            return false;
        });
        $('.rotate_right').click(function(){
            $('#mainimg img').rotate(90);
            return false;
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









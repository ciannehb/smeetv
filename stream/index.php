<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }

    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
    $smeetvdb=connect2db();

    $arr=explode("/",$_SERVER['REQUEST_URI']);
    $id=advancedClean(3,$arr[count($arr)-1]);


    if($u==true){

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


    drawHeader('Streaming search combination '.$id.'"',$u,'','','Streaming search combination "'.$id.'"',$userinfo);


    echo "<section id='content' class='grid_24'><section class='wrap'>";

    $id=eregi_replace('_hh_','#',$id);

    $query="select id from keywords where keyword=LOWER('".$id."')";
    $go=mysql_query($query);
    if(mysql_num_rows($go)==0 && !$u==true) {
        echo "<i>Due to server load, \"".$id."\" is restricted at the moment, <a href=\"https://twitter.com/#!/search/%23".$id."\">please check it directly on twitter for now</a></i>";
        return;
    }


$atkey=strpos('~'.$id,"@")+1;
$hashkey=strpos('-'.$id,"#")+1;
if($atkey==2){
    $strict=1;
} elseif($hashkey==2) {
    $strict=1;
}




    $query="select * from twits_dump where content REGEXP '";
        $query.="$".$id."$";
/*
        $query.="|";
        $query.="$".$id;
        $query.="|";
        $query.=$id;
        $query.=" ";
*/
    $query.="' order by id desc limit 0,30";

/*
echo $query;
return;
*/

    $go=mysql_query($query);





  
echo "<section class='stream'>";
for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    $twusr=explode("/",$get['link']);
    echo displayTwit($get['id'],$get['content'],$get['link'],$get['date'],$get['timestamp'],0,1,0,1);
}
echo "</section>";
?>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script src="//smeetv.com/js/smeetv.js/smeetv.js"></script>
<script>
    $(document).ready(function(){

        $('article.twit').live('click',function(){
            var goto=$(this).find('a[rel=smeetv]').attr('href');
            window.location = goto;
        });


        $('.destroy_notification').live('click',function(e){
            $(this).closest('.notification').filter(':first').remove();
            e.preventDefault();
        });

           // imagify
           $('.stream > article').each(function(){
               var content=$(this).find('.thumbnail').html(),
                   thisid=$(this).attr('id');


               imagify(content,thisid);

           });


    });
</script>
</section></section>
<?
disconnectFromDb($smeetvdb);
require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>

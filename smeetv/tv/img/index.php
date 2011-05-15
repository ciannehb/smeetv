<?
    include('../../func.php');
    session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }

    connect2db();


    $arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));

    if(strpos($arr[count($arr)-1],"?")) { /* addme widget leaves some garbage at the end of query string eg. http://fs.f1vlad.com/smeetv/smeetv/tv/img/2628?sms_ss=twitter&at_xt=4dc6c8054cd5144b,0 */
        $transport=explode("?",$arr[count($arr)-1]);
        $transport=$transport[0];
    } else {
        $transport=$arr[count($arr)-1];
    }

    //$id=advancedClean(3,$arr[count($arr)-1]);
    $id=$transport;
    $query="select id,content,timestamp,link,date from twits where id=".$id;

    $go=mysql_query($query);

    $get=mysql_fetch_array($go);
    $twusr=explode("/",$get['link']);

    require_once('../../header.php');
/*
    if(strlen($get['content'])>75){
        $title=substr($get['content'],stripos($get['content']," "),75);
        //$title=trim($title)."...";
    }
*/

    $title=$get['content'];

    //drawHeader(trim(substr($get['content'],stripos($get['content']," "),75)),$u);
    drawHeader(trim($title),$u);


?>

    <h2>Picture &sect;<?=$id?></h2>
    <article class="twit"><?=f1init_makeClickableLinks($get['content'])?>
    <a href="<?=$get['link']?>">
    <footer>
    posted by <?=$twusr[3]?>
    <time id="<?=$id?>" datetime="<?=$get['date']?>"><?=f1init_ago($get['timestamp'])?> seconds ago</time></a>
    <a href="./report/<?=$id?>">report this image</a>
    </footer>
    <aside>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_twitter"></a>
<a class="addthis_button_facebook"></a>
<a class="addthis_button_email"></a>

<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4dbc63166fcdf6f9"></script>
<!-- AddThis Button END -->



    </aside>
    </article>



<?require_once('../../footer.php');?>

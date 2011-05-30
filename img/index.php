<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
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

    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');

    $title=$get['content'];

    //drawHeader(trim(substr($get['content'],stripos($get['content']," "),75)),$u);
    drawHeader(trim($title),$u,0);

?>
<script src="/js/jquery.js"></script>
<script src="/js/smeetv.js/smeetv.js"></script>

<script>
    $(document).ready(function(){
        $('#mainimg.squares.mainimg > article').each(function(){
           var content=$(this).html();
           imagify(content,'#mainimg.squares.mainimg > article');
        });

    }); 
</script>




    <h2>Picture &sect;<?=$id?></h2>
    <article class="twit">
    <div class="t"><?=f1init_makeClickableLinks($get['content'])?><span class="slant"></span>
    <footer>
    posted 

    <a href="<?=$get['link']?>">by <?=$twusr[3]?></a>,
    found this image <time id="<?=$id?>" datetime="<?=$get['date']?>"><?=f1init_ago($get['timestamp'])?> seconds ago</time>,
    <a href="./report/<?=$id?>">report this image</a>
    </footer></div>
    <aside>
        <section id="mainimg" class="squares mainimg" >

            <article id="<?=$get['id']?>" rel="<?=$get['link']?>"><?=$get['content']?></article>

        </section>
        <section class="squares share">




<div style="clear:both">
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_twitter"></a>
<!--<a class="addthis_button_facebook"></a>-->
<a class="addthis_button_email"></a>

<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4dbc63166fcdf6f9"></script>
<!-- AddThis Button END -->
<iframe src="https://www.facebook.com/plugins/like.php?&href=http://smeetv.com<?=$_SERVER['REQUEST_URI']?>"
        scrolling="no" frameborder="0"
        style="border:none; width:450px; height:80px"></iframe>
</div>



<script type="text/javascript"><!--
google_ad_client = "ca-pub-1221828368307550";
/* smeetv_injected_slide */
google_ad_slot = "2446448564";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
        </section>
        <br class="clear">
        <section class="squares ymlw">
            <h3>You may also like:</h3>



<?

function getWordSuggestion($var){
// get contents of a file into a string
$filename = "http://google.com/complete/search?output=toolbar&q=$var";

$handle = fopen($filename, "rb");$contents = '';
while (!feof($handle)) {
  $contents .= fread($handle, 8192);
}
fclose($handle);


$x=xml2array($contents);
$offset=count($x[toplevel][CompleteSuggestion]);
$kwd=$x[toplevel][CompleteSuggestion][rand(0,$offset-1)][suggestion_attr][data];

return $kwd;
}


?>

<p id="yml1" class="yml"><iframe src="/etc/suggest/img/<?=getWordSuggestion("bar")?>"></iframe></p>
<p id="yml2" class="yml"><iframe src="/etc/suggest/img/<?=getWordSuggestion("bar")?>"></iframe></p>
<p id="yml3" class="yml"><iframe src="/etc/suggest/img/<?=getWordSuggestion("bar")?>"></iframe></p>



        </section>
        <section class="squares">
       
        </section>
    </aside>
    </article>




<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>









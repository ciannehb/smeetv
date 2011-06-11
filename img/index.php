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
    //$query="select id,content,timestamp,link,date from twits where id=".alphaID($id,true);
    $query="select id,content,timestamp,link,date,flagged from twits_dump where id=".alphaID($id,true);

    $go=mysql_query($query);

    $get=mysql_fetch_array($go);

    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');

    $title=$get['content'];

    //drawHeader(trim(substr($get['content'],stripos($get['content']," "),75)),$u);
    drawHeader(trim($title),$u,0);
    if($get['flagged']==1) 
        echo '<div class="notification error"><span class="ui-icon exclamation">&nbsp;</span>Beware, this photograph was flagged by our users. For your safety, we have delayed displaying it by 30 seconds.</div>';


?>
<script src="/js/jquery.js"></script>
<script src="/js/smeetv.js/smeetv.js"></script>
<script>
    $(document).ready(function(){
        $('#mainimg.squares.mainimg > article').each(function(){
           var content=$(this).html();
           <?if($get['flagged']==1){?>setTimeout(function(){<?}?>
           e=imagify(content,'#mainimg.squares.mainimg > article');
           <?if($get['flagged']==1){?>},30000);<?}?>
        });

    }); 
</script>




    <h2>Picture &sect;<?=$id?></h2>
<?
echo displayTwit($get['id'],$get['content'],$get['link'],$get['date'],$get['timestamp'],1);
?>

<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>









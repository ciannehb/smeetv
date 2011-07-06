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
    $query="select id from accounts where idhash='$id'";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $query="select * from twits where uid='".$get[0]."' order by id desc limit 0,30";
    $go=mysql_query($query);

    drawHeader('channel '.$id,$u,'','','Anonymous stream &sect;'.$id);






echo "<section id='content' class='grid_24'><section class='wrap'>";






  
echo "<section class='channel'>";
for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    $twusr=explode("/",$get['link']);
    echo displayTwit($get['aid'],$get['content'],$get['link'],$get['date'],$get['timestamp'],0,1,0,1);
}
echo "</section>";
?>

<p><small>This public channel is anonymous for privacy reasons.</small></p>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script src="/js/smeetv.js/smeetv.js"></script>
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
           $('.channel > article').each(function(){
               var content=$(this).find('.thumbnail').html(),
                   thisid=$(this).attr('id');
               imagify(content,thisid);
           });


    });
</script>
</section>
</section>
<?
disconnectFromDb($smeetvdb);
?>

<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>

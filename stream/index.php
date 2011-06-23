<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }

    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
    connect2db();

    $arr=explode("/",$_SERVER['REQUEST_URI']);
    $id=advancedClean(3,$arr[count($arr)-1]);

    drawHeader('Streaming search combination "'.$id.'"',$u);


    $id=eregi_replace('_hh_','#',$id);

    $query="select id from keywords where keyword=LOWER('".$id."')";
    $go=mysql_query($query);
    if(mysql_num_rows($go)==0 && !$u==true) {
        echo "<i>Due to server load #".$id." is restricted at the moment, <a href=\"https://twitter.com/#!/search/%23".$id."\">please check it directly on twitter for now</a></i>";
        return;
    }

/*
    $query="select id from accounts where idhash='$id'";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
*/


$atkey=strpos('~'.$id,"@")+1;
$hashkey=strpos('-'.$id,"#")+1;
if($atkey==2){
    $strict=1;
} elseif($hashkey==2) {
    $strict=1;
}




    $query="select * from twits_dump where content REGEXP '";
        $query.=$id."$";
        $query.="|";
        $query.="$".$id;
        $query.="|";
        $query.=$id;
        $query.=" ";
    $query.="' order by id desc limit 0,30";


    $go=mysql_query($query);


    echo "<h2>Streaming search combination \"".$id."\"</h2>";



  
echo "<section class='stream'>";
for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    $twusr=explode("/",$get['link']);
    echo displayTwit($get['id'],$get['content'],$get['link'],$get['date'],$get['timestamp'],0,1,0,1);
}
echo "</section>";
?>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script src="/js/smeetv.js/smeetv.js"></script>
<script>
    $(document).ready(function(){

        $('article.twit').live('click',function(){
            var goto=$(this).find('a[rel=smeetv]').attr('href');
            window.location.replace(goto);
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


<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>

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
    $query="select id from accounts where idhash='$id'";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $query="select * from twits where uid='".$get[0]."' order by id desc limit 0,20";
    $go=mysql_query($query);

    drawHeader('channel '.$id,$u);









echo "<h2>&sect;$id</h2>";



  

for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    $twusr=explode("/",$get['link']);
?>

    <article class="twit"><?=f1init_makeClickableLinks($get['content'])?>
    <footer>
    <a href="<?=$get['link']?>">
    posted by <?=$twusr[3]?></a>,
    <a href="http://smeetv.com/img/<?=alphaID($get['aid'])?>">discovered <time id="<?=$id?>" datetime="<?=$get['date']?>"><?=ceil(f1init_ago($get['timestamp'])/60)?> minutes ago</time></a>
    
    </footer>
    </article>

<?
}
?>

    <p><small>This public channel is anonymous for privacy reasons.</small></p>

<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>

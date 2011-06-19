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

    drawHeader('streaming #'.$id,$u);

    $query="select id from keywords where keyword=LOWER('".$id."')";
    $go=mysql_query($query);
    if(mysql_num_rows($go)==0) {
        echo "<i>Due to server load #".$id." is restricted at the moment, <a href=\"https://twitter.com/#!/search/%23".$id."\">please check it directly on twitter for now</a></i>";
        return;
    }

    $query="select id from accounts where idhash='$id'";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $query="select * from twits_dump where content REGEXP '#".$id."$|#".$id." ' order by id desc limit 0,30";
    $go=mysql_query($query);


    echo "<h2>#$id</h2>";



  

for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    $twusr=explode("/",$get['link']);
    echo displayTwit($get['id'],$get['content'],$get['link'],$get['date'],$get['timestamp'],0,1);
}
?>


<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>

<?
    include($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');
    session_start();

    $_POST['id']=advancedClean(3,$_POST['id']);
    $_POST['op']=advancedClean(3,$_POST['op']);

    if(isUserLoggedIn()==1){
        $u=true;
    }
    connect2db();



if(!$_POST['op']=='ajax'){
    $arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));
    if(strpos($arr[count($arr)-1],"?")) { /* addme widget leaves some garbage at the end of query string eg. http://fs.f1vlad.com/smeetv/smeetv/tv/img/2628?sms_ss=twitter&at_xt=4dc6c8054cd5144b,0 */
        $transport=explode("?",$arr[count($arr)-1]);
        $transport=$transport[0];
    } else {
        $transport=$arr[count($arr)-1];
    }
    //$id=advancedClean(3,$arr[count($arr)-1]);
    $id=alphaID($transport,true);
}else{
    $id=$_POST['id'];
}





    $query="select link from twits where aid=".$id;


    $go=mysql_query($query);
    $get=mysql_fetch_array($go);

    if($u==true){
        $uquery="delete from twits where aid=".$id;
        $go=mysql_query($uquery);
        if(!$go) {
            $error=1;
        }
    }

    //$gquery="update twits_dump set flagged='1' where link='".$get[0]."'";
    $gquery="update twits_dump set flagged='1' where id=".$id;
    $go=mysql_query($gquery);
    if(!$go) {
        $error=1;
    }

//echo $go;return;

if($error) {
    echo "Internal erorr";return;
} else {
    if(!$_POST['op']=='ajax') {header('Location:/img/'.alphaID($id));}
    else {echo "<html><head><meta name=\"ajr\" content=\"1\" /></head><body></body></html>";}
}

return;



?>

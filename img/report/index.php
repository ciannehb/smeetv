<?
    include($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');
    session_start();

    $_POST['id']=advancedClean(3,$_POST['id']);
    $_POST['op']=advancedClean(3,$_POST['op']);

    if(isUserLoggedIn()==1){
        $u=true;
    }
    $smeetvdb=connect2db();



$arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));
if(strpos($arr[count($arr)-1],"?")) { /* addme widget leaves some garbage at the end of query string eg. http://fs.f1vlad.com/smeetv/smeetv/tv/img/2628?sms_ss=twitter&at_xt=4dc6c8054cd5144b,0 */
    $transport=explode("?",$arr[count($arr)-1]);
    $transport=$transport[0];
} else {
    $transport=$arr[count($arr)-1];
}



$aid=alphaID($transport);
$id=$transport;



    // add image to shared flagged table
    $query="insert into flagged (id) values ('".$aid."')";
echo $query;
    $go=mysql_query($query);
    if(!$go) {
        $error=1;
    }



    //$get=mysql_fetch_array($go);

    if($u==true){
        $uquery="delete from twits where aid=".$id;
//echo $uquery;return;
        $go=mysql_query($uquery);
        if(!$go) {
            $error=1;
        }
    }

if($error) {
    echo "Internal erorr";return;
} else {
    if(!$_POST['op']=='ajax') {header('Location:/img/'.alphaID($id));}
    else {echo "<html><head><meta name=\"ajr\" content=\"1\" /></head><body></body></html>";}
}
disconnectFromDb($smeetvdb);
return;



?>

<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');

if(!isUserLoggedIn()==1){
    //header("Location:/");
    return;
}

$_POST['section']=advancedClean(3,$_POST['section']);
$_POST['password']=advancedClean(3,$_POST['password']);
$_POST['ad']=advancedClean(3,$_POST['ad']);
$_POST['op']=advancedClean(3,$_POST['op']);

$_POST['smeetv_hashtags']=advancedClean(3,$_POST['smeetv_hashtags']);
$_POST['smeetv_hashtags_prev']=advancedClean(3,$_POST['smeetv_hashtags_prev']);
$_POST['smeetv_speed']=advancedClean(3,$_POST['smeetv_speed']);
$_POST['smeetv_size']=advancedClean(3,$_POST['smeetv_size']);
$_POST['smeetv_text']=advancedClean(3,$_POST['smeetv_text']);
$_POST['smeetv_img_num']=advancedClean(3,$_POST['smeetv_img_num']);


$username=$_SESSION['username'];
$id=$_SESSION['id'];

$_POST['var']=advancedClean(3,$_POST['var']);


/* ? remove? */
$force='1';


$smeetvdb=connect2db();



/* check if we need to wipe out old keywords */
if($_POST['smeetv_hashtags']!=$_POST['smeetv_hashtags_prev']){
    $fixl="gvd0a3f3401320gssdfs212d1f82f07u2hf";
    $post_smeetv_hashtags_altered = $_POST['smeetv_hashtags'];
    $_POST['smeetv_hashtags_prev']=$fixl.",".$_POST['smeetv_hashtags_prev'];
    $post_smeetv_hashtags_altered=$fixl.",".$post_smeetv_hashtags_altered;
    $ht_prev=explode(",",$_POST['smeetv_hashtags_prev']);
    $ht_prev_count=count($ht_prev);
    $clean_query="delete from twits where ";
    $hti=0;
    foreach($ht_prev as $h){
        $g=stripos($post_smeetv_hashtags_altered,$h);
        if($g==NULL && $h!=$fixl ){
            //$query="delete from twits where content like '%".strtolower($h)."%' and uid='{$_SESSION['id']}'";
            $clean_query.=" content like '%".trim(strtolower($h))."%'";
            if($hti<($ht_prev_count-1)) $clean_query.=" OR ";
            //echo $query;
            //$go=mysql_query($query);
            //if(!$go) $error=1;
            $need_to_clean=TRUE;
        }
        $hti++;
    }

    if($need_to_clean){
    $clean_query.=" AND uid='{$_SESSION['id']}'";
        $clean_query=str_replace("OR  AND"," AND",$clean_query);
        $go=mysql_query($clean_query);
        if(!$go) $error=1;
        //echo $clean_query;
    }
    $hash_tags_changed=1;
}


if($_POST['section']=="settings"){
    if($_POST['ad']=="false"){
        $_POST['ad']='0';
    }else{
        $_POST['ad']='1';
    }
    $query="update accounts set ad='".$_POST['ad']."'";
    if($_POST['password']) {
        $query.=" ,password=password('".$_POST['password']."') ";
    }
}elseif($_POST['section']=="remotecontrol"){

    $query="update accounts set ";
    $query.=" smeetv_hashtags='".$_POST['smeetv_hashtags']."', ";
    $query.=" smeetv_speed='".$_POST['smeetv_speed']."', ";
    $query.=" smeetv_text='".$_POST['smeetv_text']."', ";
    $query.=" smeetv_img_num='".$_POST['smeetv_img_num']."' ";



    if($hash_tags_changed==1){
        $split_keywords=explode(',',$_POST['smeetv_hashtags']);
        for($i=0;$i<count($split_keywords);$i++){

            $subquery="select uids from keywords where keyword='".trim($split_keywords[$i])."' limit 0,1";
            $subgo=mysql_query($subquery);
            $subget=mysql_fetch_array($subgo);

            $splitsubget=split(',',$subget[0]);
            if(!array_search($_SESSION['id'],$splitsubget)){
                if(mysql_num_rows(mysql_query("select id from keywords where keyword='".trim($split_keywords[$i])."'"))==0){  // no suck keyword found, add it
                    mysql_query("insert into keywords (keyword,uids,counter) values ('".$split_keywords[$i]."','".$subget[0].", ".$_SESSION['id']."',1)");
                } else {
                    mysql_query("update keywords set counter=counter+1,uids='".$subget[0].",".$_SESSION['id']."' where keyword='".trim($split_keywords[$i])."'"); // such keyword exists, add up
                }
            } else {
                //echo "skipping";
            }
        }

        /* run very light query to add at least a few keywords for user to give him impression of immediate results */
        $rush_query = "select id,content,link,date from twits_dump where content like '% ".$split_keywords[0]." %' OR content like '% ".$split_keywords[count($split_keywords)-1]." %' and flagged=0 limit 0,6 ";
        $rush_go=mysql_query($rush_query);
        for($rush_i=0;$rush_i<mysql_num_rows($rush_go);$rush_i++){
            $rush_get=mysql_fetch_array($rush_go);
            mysql_query("insert into twits (aid,uid,content,link,date,timestamp) values (\"{$rush_get['id']}\",\"{$_SESSION['id']}\",\"{$rush_get['content']}\",\"{$rush_get['link']}\",\"{$rush_get['date']}\",\"".time()."\")");
        }

    }

}elseif($_POST['section']=="save_resize_r"){
    $query="update accounts set ";
    $query.=" smeetv_size = '".$_POST['smeetv_size']."' ";  
}else{
    $query="update accounts set smeetv_hashtags_force='".$_POST['smeetv_hashtags_force']."',ad='".$_POST['ad']."',smeetv_img_num='".$_POST['smeetv_img_num']."',smeetv_text='".$_POST['smeetv_text']."',smeetv_channels='".$_POST['smeetv_channels']."',smeetv_hashtags='".$_POST['smeetv_hashtags']."',smeetv_speed='".$_POST['smeetv_speed']."',force_update='$force'";
}








if($_POST['refetch']==1)
    $query.=",force_update='2'";
$query.=" where id='".$id."' and username='".$username."'  ";




$go=mysql_query($query);
//echo $query;


if($go) {
$result="success";
} else {
$result="failure";
$error=1;
}


if($error) {
    echo "Internal erorr";return;
} else {
    if(!$_POST['op']=='ajax') {header('Location:/');}
    else {echo "<html><head><meta name=\"ajr\" content=\"1\" /></head><body></body></html>";}
}
disconnectFromDb($smeetvdb);


?>

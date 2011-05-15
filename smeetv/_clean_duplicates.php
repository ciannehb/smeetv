<?
require_once('func.php');
?>
<h1>Cleaning duplicate twits from twits_dump and generating `aid` where it's missing</h1>
<?

connect2db();

$query="select id,aid,link from twits_dump";
$go=mysql_query($query);

for($i=0;$i<mysql_num_rows($go);$i++){
    $get=mysql_fetch_array($go);
    $query="select id from twits_dump where aid='{$get['aid']}'";
    $subgo=mysql_query($query);
    if($get['aid'] && mysql_num_rows($subgo)>=2){
        $query_delete="delete from twits_dump where id='{$get['id']}'";
        $go3=mysql_query($query_delete);
        echo "<br>Deleting duplicate {$get['id']}";
    }

    if(!$get['aid']){
        $aidgenerate="update twits_dump set aid='".base64_encode($get['link'])."' where id='".$get['id']."'";
        $go3=mysql_query($aidgenerate);
        echo "<br>Generating `aid` for {$get['id']} ";
    }

}






?>

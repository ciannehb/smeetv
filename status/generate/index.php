<?
    require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');
    $smeetvdb=connect2db();


    $arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));
    $op=$arr[count($arr)-1];
  
    $query="select count(*) from twits_dump";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $tot=$get[0]+2203503;
    $output = "<span class='fni'>".number_format($tot)." photographs fetched <span class='details'>as of ";

    $query="select timestamp from twits_dump order by id desc limit 0,1";
    $go=mysql_query($query);
    $get=mysql_fetch_array($go);
    $l=time()-$get[0];
    $output .=  date("H:i:s", $get[0])   ."</span><span>";
$smeetvdb=connect2db();
$f = fopen("_incl.txt", "w"); 
fwrite($f, $output); 
fclose($f); 

?>

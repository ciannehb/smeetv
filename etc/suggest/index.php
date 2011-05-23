<? // in this script we check for new updates
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');

connect2db();




$qh="select * from twits where content like '%obama%' and uid!='{$_SESSION['id']}' limit ".rand(1,14).",1";
$qh=mysql_query($qh);
$gh=mysql_fetch_array($qh);

echo "
<html><head></head><body>
<section>
<article onclick=\"window.location.href='/img/{$gh['id']}'\" id=\"{$gh['id']}\" rel=\"".$gh['link']."\">
".$gh['content']."</article>
</section>
";


?>
<script src="/js/jquery.js"></script>
<script src="/js/smeetv.js/smeetv.js"></script>

<script>
    $(document).ready(function(){
           $('section > article').each(function(){
               var content=$(this).html();
               imagify(content,'<?=$gh['id']?>');
           });
    });

</script>



</body></html>
<?
return;
?>


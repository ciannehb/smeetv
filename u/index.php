<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }

    $smeetvdb=connect2db();


    $arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));
    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
    drawHeader($arr[2],$u,'1');
    echo "<section id='content' class='grid_24'><section class='wrap'>";
?>
    <h2><?=$arr[2]?>'s page</h2>
    <p>Nothing to see here <em>yet</em>.</p>
<?
    echo "</setion></section>";
    disconnectFromDb($smeetvdb);
    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');
?>

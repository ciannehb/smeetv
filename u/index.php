<?
    include($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    session_start();

    if(isUserLoggedIn()==1){
        $u=true;
    }

    connect2db();


    $arr=explode("/",advancedClean(3,$_SERVER['REQUEST_URI']));
    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
    drawHeader($arr[2],$u,'1');
?>
    <h2><?=$arr[2]?>'s page</h2>
    <p>Nothign to see here <em>yet</em>.</p>
<?
    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');
?>

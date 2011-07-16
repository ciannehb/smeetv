<?
    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/func.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/header.php');
    session_start();

    drawHeader('privacy policy',$u);


?>

    <h2>Your account has been suspended</h2>
<?session_destroy()?>

<?require_once($_SERVER['DOCUMENT_ROOT'].'/smeetv/footer.php');?>

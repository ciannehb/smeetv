<?
session_start();
require_once('func.php');
if(logOut()==true){
//echo "Successfully logged out.";
}
header("Location:/");
?>

<?php
try
{
session_start();

$_SESSION=array();
if(isset($_COOKIE[session_name()])==true){
    setcookie(session_name(),'',time()-42000,'/');

}
session_destroy();

header('Location:user_login.php');
exit();
}
catch(Exception $e)
{
header('Location:/error/error.html');
exit();
}
?>

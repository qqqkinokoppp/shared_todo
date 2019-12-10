<?php
try
{
session_start();
session_regenerate_id(true);

require_once('../common/common.php');
$post=sanitize($_POST);

$user=$post['user'];
$pass=$post['password'];

//$pass=password_hash($pass,PASSWORD_DEFAULT);
        
$dsn='mysql:dbname=todo;host=localhost;charset=utf8';
$user1='root';
$password='';
$dbh=new PDO($dsn,$user1,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$sql='SELECT*FROM users WHERE user=? /*AND pass=?*/';
$stmt=$dbh->prepare($sql);
$data[]=$user;
//$data[]=$pass;
$stmt->execute($data);
//$stmt->debugDumpParams();

$dbh=null;

$rec=$stmt->fetch(PDO::FETCH_ASSOC);
//exit();

    if(password_verify($pass,$rec['pass'])==false){
        $_SESSION['login']=0;
        $_SESSION['errorMessage']='ユーザー名またはパスワードが違います。';
        header('Location:user_login.php');
        exit();
    }else{
        $_SESSION['login']=1;
        $_SESSION['family_name']=$rec['family_name'];
        $_SESSION['first_name']=$rec['first_name'];
        header('Location:../todo/todo_list.php');
        exit();
    }


}
catch(Exception $e)
{
header('Location:/error/error.html');
exit();
}
?>

<?php
try
{
session_start();
session_regenerate_id(true);

require_once('../common/common.php');
$post=sanitize($_POST);

$dsn='mysql:dbname=todo;host=localhost;charset=utf8';
$user1='root';
$password='';
$dbh=new PDO($dsn,$user1,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$sql='UPDATE todo_items SET finished_date=? WHERE id=?';
$stmt=$dbh->prepare($sql);
$data[]=date('y-m-d');
$data[]=$_POST['item_id'];
$stmt->execute($data);

$dbh=null;

header('Location:todo_list.php');
exit();

}catch(Exception $e)
{
header('Location:/error/error.html');
exit();
}
?>

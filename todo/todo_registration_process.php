<?php
try
{
session_start();
session_regenerate_id(true);

require_once('../common/common.php');
$post=sanitize($_POST);

$item_name=$post['item_name'];
$user_id=$post['user_id'];
$expire_date=$post['expire_date'];
if(isset($_POST['finished'])==true){
    $finished=1;
}else{
    $finished=0;
}

$year=mb_substr($expire_date,0,4);
$month=mb_substr($expire_date,5,2);
$day=mb_substr($expire_date,8,2);

$dsn='mysql:dbname=todo;host=localhost;charset=utf8';
$user1='root';
$password='';
$dbh=new PDO($dsn,$user1,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$sql='SELECT * FROM users WHERE id=?';//登録されている担当者のidをとってくる
$stmt=$dbh->prepare($sql);
$data[]=$user_id;
$stmt->execute($data);

$rec=$stmt->fetch(PDO::FETCH_ASSOC);

$dbh=null;

if(mb_strlen($item_name)>100){//項目名が100文字を超えていれば
    $_SESSION['registration']=0;
    $_SESSION['registration_errorMessage']='文字数が100文字を超えています。';
    header('Location:todo_registration.php');
    exit();
}


if($rec['user']==false){//登録されている担当者じゃなければ
    $_SESSION['registration']=0;
    $_SESSION['registration_errorMessage']='担当者が登録されていません。';
    header('Location:todo_registration.php');
    exit();
}


if((checkdate($month,$day,$year)==false)||(preg_match('/^[0-9]+$/',$month))==0||(preg_match('/^[0-9]+$/',$day))==0||(preg_match('/^[0-9]+$/',$year))==0){//日付が正しくなければ
    $_SESSION['registration']=0;
    $_SESSION['registration_errorMessage']='正しい日付を入力してください。';
    header('Location:todo_registration.php');
    exit();

}

$dsn='mysql:dbname=todo;host=localhost;charset=utf8';
$user1='root';
$password='';
$dbh=new PDO($dsn,$user1,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$sql="INSERT INTO todo_items (user_id,item_name,registration_date,expire_date,finished_date) VALUES (?,?,?,?,?)";//todoのDB登録
$stmt=$dbh->prepare($sql);

$data=array();
$data[]=$user_id;
$data[]=$item_name;
$data[]=date('Y-m-d');
$data[]=$expire_date;

if($finished==1){
    $data[]=date('Y-m-d');
}else{
    $data[]=null;
}

$stmt->execute($data);
//$stmt->debugDumpParams();

$dbh=null;

$_SESSION['registration']=1;
header('Location:todo_list.php');
exit();

}catch(Exception $e)
{
header('Location:/error/error.html');
exit();
}
?>

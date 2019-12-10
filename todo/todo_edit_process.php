<?php
try
{
session_start();
session_regenerate_id(true);

require_once('../common/common.php');
$post=sanitize($_POST);

$item_name=$post['item_name'];//項目名
$user_id=$post['user_id'];//担当者のid
$expire_date=$post['expire_date'];//期限日
$item_id=$post['item_id'];//todoのid


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
    $_SESSION['edit']=0;
    $_SESSION['edit_item_id']=$item_id;
    $_SESSION['edit_errorMessage']='文字数が100文字を超えています。';
    header('Location:todo_edit.php');
    exit();
}


if($rec['user']==false){//登録されている担当者じゃなければ
    $_SESSION['edit']=0;
    $_SESSION['edit_item_id']=$item_id;
    $_SESSION['edit_errorMessage']='担当者が登録されていません。';
    header('Location:todo_edit.php');
    exit();
}


if((checkdate($month,$day,$year)==false)||(preg_match('/^[0-9]+$/',$month))==0||(preg_match('/^[0-9]+$/',$day))==0||(preg_match('/^[0-9]+$/',$year))==0){//日付が正しくなければ
    $_SESSION['edit']=0;
    $_SESSION['edit_item_id']=$item_id;
    $_SESSION['edit_errorMessage']='正しい日付を入力してください。';
    header('Location:todo_edit.php');
    exit();

}


$dsn='mysql:dbname=todo;host=localhost;charset=utf8';
$user1='root';
$password='';
$dbh=new PDO($dsn,$user1,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$sql="UPDATE todo_items SET item_name=?, user_id=?, expire_date=?, finished_date=? WHERE id=? ";//todoのDB登録
$stmt=$dbh->prepare($sql);

$data=array();
$data[]=$item_name;
$data[]=$user_id;
$data[]=$expire_date;
if($finished==1){
    $data[]=date('Y-m-d');
}else{
    $data[]=null;
}
$data[]=$item_id;

$stmt->execute($data);

$dbh=null;
//$stmt->debugDumpParams();
//exit();

$_SESSION['edit']==1;
$_SESSION['edit_errorMessage']='';
header('Location:todo_list.php');
exit();

}catch(Exception $e)
{
    header('Location:/error/error.html');
exit();
}
?>

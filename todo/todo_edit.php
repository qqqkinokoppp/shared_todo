<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
    header('Location:../login/user_login.php');
}

$family_name=$_SESSION['family_name'];
$first_name=$_SESSION['first_name'];
if(isset($_SESSION['edit_item_id'])==true){
    $item_id=$_SESSION['edit_item_id'];
    }else{
        $item_id=$_POST['item_id'];
    }


/*$dsn='mysql:dbname=todo;host=localhost;charset=utf8';//担当者の初期設定をとってくる
        $user1='root';
        $password='';
        $dbh=new PDO($dsn,$user1,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $sql='SELECT users.id AS users_id,users.family_name AS users_family_name,users.first_name AS users_first_name,todo_items.id AS todo_items_id,item_name,expire_date FROM todo_items,users WHERE todo_items.user_id=users.id AND users.id=? ';
        //$sql='SELECT user_id,item_name,expire_date FROM todo_items WHERE id=?';
        $stmt=$dbh->prepare($sql);
        $data[]=$_POST['item_id'];
        $stmt->execute($data);

        $dbh=null;

        $rec1=$stmt->fetch(PDO::FETCH_ASSOC);

        $rec1['']*/


?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>作業更新</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
         <div class="title">
            <h1>作業更新</h1>
        </div>
        <div class="login_info">
            <ul>
                <li>ようこそ<?php print $family_name.' '.$first_name?>さん</li>
                <li>
                    <form>
                        <input type="button" value="ログアウト" onclick="location.href='../login/user_logout_process.php';">
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <main>
        <p class="error">
        <?php
            $errorMessage='';
            if((isset($_SESSION['edit'])==true)&&(isset($_SESSION['edit_errorMessage'])==true)){
                if($_SESSION['edit']==0){
                    $errorMessage=$_SESSION['edit_errorMessage'];
                }
            }else{
                $_SESSION['edit_errorMessage']='';
            }
            print $errorMessage;
            ?>
        </p>

        <form action="./todo_edit_process.php" method="post">
            <input type="hidden" name="item_id" value="<?php print $item_id;?>">
            <table class="list">
                <tr>
                    <th>項目名</th>
                    <td class="align-left">
                    <?php
                        $dsn='mysql:dbname=todo;host=localhost;charset=utf8';
                        $user1='root';
                        $password='';
                        $dbh=new PDO($dsn,$user1,$password);
                        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                        $sql='SELECT user_id,item_name,expire_date FROM todo_items WHERE id=?';//担当者、項目名、期限日をとってくる、記述場所ミス
                        $stmt=$dbh->prepare($sql);
                        $data[]=$item_id;
                        $stmt->execute($data);

                        $dbh=null;

                        $rec2=$stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <input type="text" name="item_name" id="item_name" class="item_name" value="<?php print $rec2['item_name'];?>">
                    </td>
                </tr>
                <tr>
                    <th>担当者</th>
                    <td class="align-left">
                        <select name="user_id" id="user_id" class="user_id">
                        <?php
                        $dsn='mysql:dbname=todo;host=localhost;charset=utf8';
                        $user1='root';
                        $password='';
                        $dbh=new PDO($dsn,$user1,$password);
                        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                        $sql='SELECT user,id,family_name,first_name FROM users WHERE 1';
                        $stmt=$dbh->prepare($sql);
                        $stmt->execute();

                        $dbh=null;

                            while(true){
                                $rec3=$stmt->fetch(PDO::FETCH_ASSOC);
                                if($rec3==false){
                                    break;
                                }
                                print '<option value="'.$rec3['id'].'" >'.$rec3['family_name'].$rec3['first_name'].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>期限</th>
                    <td class="align-left">
                        <input type="text" name="expire_date" id="expire_date" class="expire_date" value="<?php print $rec2['expire_date'];?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        完了
                    </th>
                    <td class="align-left">
                        <input type="checkbox" name="finished" id="finished" class="finished" value="1" size="8"> 完了
                    </td>
                </tr>
            </table>
            <input type="hidden" name="item_id" value="<?php print $item_id;?>" >
            <input type="submit" value="更新">
            <input type="button" value="キャンセル" onclick="location.href='./todo_list.php';">
        </form>


    </main>

    <footer>

    </footer>
</div>
</body>
</html>
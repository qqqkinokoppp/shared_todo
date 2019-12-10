<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
    header('Location:../login/user_login.php');
}

$family_name=$_SESSION['family_name'];
$first_name=$_SESSION['first_name'];

$item_id=$_POST['item_id'];

/*$dsn='mysql:dbname=todo;host=localhost;charset=utf8';
        $user1='root';
        $password='';
        $dbh=new PDO($dsn,$user1,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $sql='SELECT user_id,item_name,expire_date FROM todo_items WHERE id=?';//担当者、項目名、期限日をとってくる、記述場所ミス
        $stmt=$dbh->prepare($sql);
        $data[]=$item_id;
        $stmt->execute($data);

        $dbh=null;

        $rec1=$stmt->fetch(PDO::FETCH_ASSOC);*/


$dsn='mysql:dbname=todo;host=localhost;charset=utf8';//担当者の初期設定をとってくる
        $user1='root';
        $password='';
        $dbh=new PDO($dsn,$user1,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $sql='SELECT users.id AS users_id,users.family_name AS users_family_name,users.first_name AS users_first_name,todo_items.id AS todo_items_id,item_name,expire_date,finished_date FROM todo_items,users WHERE todo_items.user_id=users.id AND todo_items.id=?';
        //$sql='SELECT user_id,item_name,expire_date FROM todo_items WHERE id=?';
        $stmt=$dbh->prepare($sql);
        $data[]=$item_id;
        $stmt->execute($data);

        $dbh=null;

        $rec=$stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>削除確認</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
         <div class="title">
            <h1>削除確認</h1>
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
            <!--ここにエラーの内容を表示します。-->
        </p>

        <p>
            下記の項目を削除します。よろしいですか？
        </p>
        <form action="./todo_delete_process.php" method="post">
            <input type="hidden" name="item_id" value="<?php print $rec['todo_items_id'];?>">
            <table class="list">
                <tr>
                    <th>項目名</th>
                    <td class="align-left">
                        <?php print $rec['item_name'];?>
                    </td>
                </tr>
                <tr>
                    <th>担当者</th>
                    <td class="align-left">
                        <?php print $rec['users_family_name'].$rec['users_first_name'];?>
                    </td>
                </tr>
                <tr>
                    <th>期限</th>
                    <td class="align-left">
                        <?php print $rec['expire_date'];?>
                    </td>
                </tr>
                <tr>
                    <th>
                        完了         
                    </th>
                    <td class="align-left">
                    <?php
                        if($rec['finished_date']==null){
                            print '未完了';
                        }else{
                            print '完了';
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <input type="submit" value="削除">
            <input type="button" value="キャンセル" onclick="location.href='./todo_list.php';">
        </form>


    </main>

    <footer>

    </footer>
</div>
</body>
</html>
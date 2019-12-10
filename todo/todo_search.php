<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
    header('Location:../login/user_login.php');
}

require_once('../common/common.php');
$post=sanitize($_POST);


$family_name=$_SESSION['family_name'];
$first_name=$_SESSION['first_name'];

$search_word=$post['search-button'];

$dsn='mysql:dbname=todo;host=localhost;charset=utf8';
                    $user1='root';
                    $password='';
                    $dbh=new PDO($dsn,$user1,$password);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                    $sql='SELECT 
                            users.family_name AS users_family_name,
                            users.first_name AS users_first_name,
                            todo_items.id AS todo_items_id,
                            item_name,
                            todo_items.is_deleted AS todo_items_is_deleted,
                            registration_date,expire_date,
                            finished_date 
                        FROM 
                            todo_items,users 
                        WHERE 
                            todo_items.user_id=users.id
                        AND 
                            todo_items.is_deleted=0
                        AND 
                            CONCAT(item_name,users.first_name,users.family_name,registration_date,expire_date) LIKE "%'.$search_word.'%"
                        
                        ORDER BY 
                            expire_date';
                    $stmt=$dbh->prepare($sql);
                    $stmt->execute();

                    $dbh=null;

?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>検索結果</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
        <div class="title">
            <h1>検索結果</h1>
        </div>
        <div class="login_info">
            <ul>
                <li>ようこそ<?php print $family_name.' '.$first_name?>さん</li>
                <li>
                    <form>
                        <input type="button" value="ログアウト" onclick="location.href='../login/index.html';">
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <main>
        <div class="main-header">
            <form action="./todo_search.php" method="post">
                <div class="entry">
                    <input type="button" name="entry-button" id="entry-button" class="entry-button" value="作業登録" onclick="location.href='./todo_registration.php'">
                </div>
                <div class="search">
                    <input type="text" name="search-button" id="search-button" class="search-button">
                    <input type="submit" value="🔍検索">
                </div>
            </form>
        </div>

        <table class="list">
            <tr>
                <th>項目名</th>
                <th>担当者</th>
                <th>登録日</th>
                <th>期限日</th>
                <th>完了日</th>
                <th>操作</th>
            </tr>
            
            <?php
            for($i=0;;$i++){
                        //while(true){
                            $rec=$stmt->fetch(PDO::FETCH_ASSOC);
                            if($rec==false){
                                break;
                            }
                                
                //for($i=0;$i<count($rec);$i++){
                        if($i%2==0){
                            if(strtotime($rec['expire_date']) < strtotime(date('Y-m-d'))){
                                print '<tr class="warning">'; 
                            }else{
                                print '<tr class="even">';
                            }
                        print '<td class="align-left">';
                            print $rec['item_name'];
                        print '</td>';
                        print '<td class="align-left">';
                            print $rec['users_family_name'].$rec['users_first_name'];
                            print '</td>';
                            print '<td>';
                                print $rec['registration_date'];
                                print '</td>';
                                print '<td>';
                                print $rec['expire_date'];
                                print '</td>';
                                print '<td>';
                                if($rec['finished_date']==null){
                                    print '未';
                                }else{
                                print $rec['finished_date'];
                                }
                                print '</td>';
                                print '<td>';
                                print '<form action="todo_complete_process.php" method="post">';
                                print '<input type="hidden" name="item_id" value="'.$rec['todo_items_id'].'">';
                                print '<input type="submit" value="完了">';
                                print '</form>';
                                print '<form action="todo_edit.php" method="post">';
                                print '<input type="hidden" name="item_id" value="'.$rec['todo_items_id'].'">';
                                print '<input type="submit" value="更新">';
                                print '</form>';
                                print '<form action="todo_delete.php" method="post">';
                                print '<input type="hidden" name="item_id" value="'.$rec['todo_items_id'].'">';
                                print '<input type="submit" value="削除">';
                                print '</form>';
                                print '</td>';
                                print '</tr>';
                                
                        
                        } else{
                            if(strtotime($rec['expire_date']) < strtotime(date('Y-m-d'))){
                                print '<tr class="warning">'; 
                            }else{
                                print '<tr class="odd">';
                            }
                        print '<td class="align-left">';
                            print $rec['item_name'];
                        print '</td>';
                        print '<td class="align-left">';
                            print $rec['users_family_name'].$rec['users_first_name'];
                            print '</td>';
                            print '<td>';
                                print $rec['registration_date'];
                                print '</td>';
                                print '<td>';
                                print $rec['expire_date'];
                                print '</td>';
                                print '<td>';
                                if($rec['finished_date']==null){
                                    print '未';
                                }else{
                                print $rec['finished_date'];
                                }
                                print '</td>';
                                print '<td>';
                                print '<form action="todo_complete_process.php" method="post">';
                                print '<input type="hidden" name="item_id" value="'.$rec['todo_items_id'].'">';
                                print '<input type="submit" value="完了">';
                                print '</form>';
                                print '<form action="todo_edit.php" method="post">';
                                print '<input type="hidden" name="item_id" value="'.$rec['todo_items_id'].'">';
                                print '<input type="submit" value="更新">';
                                print '</form>';
                                print '<form action="todo_delete.php" method="post">';
                                print '<input type="hidden" name="item_id" value="'.$rec['todo_items_id'].'">';
                                print '<input type="submit" value="削除">';
                                print '</form>';
                                print '</td>';
                                print '</tr>';
                            
                        }
                    }
            ?>
            


            <!--<tr class="warning">
                <td class="align-left">
                    test1を実施する
                </td>
                <td class="align-left">
                    テスト1
                </td>
                <td>
                    2019-01-30
                </td>
                <td>
                    2019-01-30
                </td>
                <td>
                    未
                </td>
                <td>
                    <form action="#" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="完了">
                    </form>
                    <form action="edit.html" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="更新">
                    </form>
                    <form action="delete.html" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="削除">
                    </form>
                </td>
            </tr>
            <tr class="even">
                <td class="align-left">
                    test2の結果を報告する
                </td>
                <td class="align-left">
                    テスト2
                </td>
                <td>
                    2019-01-30
                </td>
                <td>
                    2019-05-10
                </td>
                <td>
                    2019-05-10
                </td>
                <td>
                    <form action="#" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="完了">
                    </form>
                    <form action="edit.html" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="更新">
                    </form>
                    <form action="delete.html" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="削除">
                    </form>
                </td>
            </tr>
            <tr class="odd">
                <td class="align-left">
                    test3はどうなっているのか尋ねる
                </td>
                <td class="align-left">
                    テスト3
                </td>
                <td>
                    2019-01-30
                </td>
                <td>
                    2019-05-10
                </td>
                <td>
                    2019-05-10
                </td>
                <td>
                    <form action="#" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="完了">
                    </form>
                    <form action="edit.html" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="更新">
                    </form>
                    <form action="delete.html" method="post">
                        <input type="hidden" name="item_id" value="1">
                        <input type="submit" value="削除">
                    </form>
                </td>
            </tr>
            -->
        </table>

        <div class="main-footer">
            <form>
                <div class="goback">
                    <input type="button" value="戻る" onclick="history.back()">
                </div>
            </form>
        </div>
    </main>

    <footer>

    </footer>
</div>
</body>
</html>
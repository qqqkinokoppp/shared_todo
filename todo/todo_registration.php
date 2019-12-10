<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
    header('Location:../login/user_login.php');
}

$family_name=$_SESSION['family_name'];
$first_name=$_SESSION['first_name'];
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>作業登録</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
         <div class="title">
            <h1>作業登録</h1>
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
            if((isset($_SESSION['registration'])==true)&&(isset($_SESSION['registration_errorMessage'])==true)){
                if($_SESSION['registration']==0){
                    $errorMessage=$_SESSION['registration_errorMessage'];
                }
            }else{
                $_SESSION['resistration_errorMessage']='';
            }
            print $errorMessage;
            ?>
        
        </p>

        <form action="./todo_registration_process.php" method="post">
            <input type="hidden" name="item_id" value="3">
            <table class="list">
                <tr>
                    <th>項目名</th>
                    <td class="align-left">
                        <input type="text" name="item_name" id="item_name" class="item_name" value="<?php '.$todo_content.'?>">
                    </td>
                </tr>
                <tr>
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

                            ?>
                    <th>担当者</th>
                    <td class="align-left">
                        <select name="user_id" id="user_id" class="user_id">
                            <?php
                            while(true){
                                $rec=$stmt->fetch(PDO::FETCH_ASSOC);
                                if($rec==false){
                                    break;
                                }
                                print '<option value="'.$rec['id'].'" >'.$rec['family_name'].$rec['first_name'].'</option>';
                            }
                            ?>
                            <!--<option value="1" >テスト1</option>-->
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>期限</th>
                    <td class="align-left">
                        <input type="text" name="expire_date" id="expire_date" class="expire_date" value="<?php print date('Y-m-d');?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        完了
                    </th>
                    <td class="align-left">
                        <input type="checkbox" name="finished" id="finished" class="finished" size="8"> 完了
                    </td>
                </tr>
            </table>

            <input type="submit" value="登録">
            <input type="button" value="キャンセル" onclick="location.href='./todo_list.php';">
        </form>


    </main>

    <footer>

    </footer>
</div>
</body>
</html>
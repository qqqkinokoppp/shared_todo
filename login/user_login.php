<?php
session_start();
session_regenerate_id(true);

$errorMessage='';
if(isset($_SESSION['login'])==true){
    if($_SESSION['login']==0){
    $errorMessage=$_SESSION['errorMessage'];
}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>ログイン</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
        <h1>ログイン</h1>
    </header>

    <main>
        <p class="error">
           <?php print $errorMessage;?>
        
        </p>
        <form action="../login/user_login_check.php" method="post">
            <table class="login">
                <tr>
                    <th class="login_field">
                        ユーザー名
                    </th>
                    <td class="login_field">
                        <input type="text" name="user" id="user" class="login_user" value="">
                    </td>
                </tr>
                <tr>
                    <th class="login_field">
                        パスワード
                    </th>
                    <td class="login_field">
                        <input type="password" name="password" id="password" class="login_pass" value="">
                    </td>
                </tr>
            </table>
            <input type="submit" value="ログイン" name="login" id="login">
        </form>


    </main>

    <footer>

    </footer>
</div>
</body>
</html>
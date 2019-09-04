<?php
session_start();
require('db.php');

if ($_POST) {
   $mail = $_POST['mail'];
   $pass = $_POST['pass'];
   $sql = "SELECT * FROM users WHERE mail=? AND pass=?";

   //ログイン機能
   if ($mail == '' || $pass == '') {
       header('Location: login.php');
       exit;
   }

   $login = $db->prepare($sql);
   $login->execute(array($mail, $pass));
   $member = $login->fetch();

   if ($member) {
       $_SESSION['id'] = $member['id'];
       $_SESSION['name'] = $member['name'];
       header('Location: post_list.php');
       exit;
   } else {
       echo 'メールアドレス、またはパスワードが間違えてます';
   }
}

?>
<!DOCTYPE html>
<html lang = "ja">
   <head>
       <meta charset = "utf-8">
       <title>課題18</title>
   </head>
   <body>
       <h1>掲示板</h1>
       <a href="registar.php">まず登録する</a>
       <h2>ログインしよう！</h2>
       <form action="" method="post">
           <h3>メールアドレス</h3>
           <input type="text" name="mail" placeholder="例）torou@gmail.com" required>
           <h3>パスワード</h3>
           <input type="text" name="pass" placeholder="※4～100文字の半角英数字" required>
           <p>
               <input type="submit" value="ログインする">
           </p>
       </form>
       <p>パスワードを忘れた方は<a href="pass_reset.php">こちら</a></p>
   </body>
</html>

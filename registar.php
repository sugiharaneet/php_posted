<?php
session_start();
require('db.php');

if ($_POST) {
   $name = $_POST['name'];
   $mail = $_POST['mail'];
   $pass = $_POST['pass'];
   $check_name = str_replace(array(" ", "　"), "", $name);

   if (preg_match('/\A[a-z\d]{4,100}+\z/i', $pass)) {
       if ($check_name !== '' && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
           $check_sql = 'SELECT COUNT(*) FROM users WHERE mail=?';
           $check_mail = $db->prepare($check_sql);
           $check_mail->execute(array($mail));
           $duplicate = $check_mail->fetchColumn();

           if ($duplicate == 0) {
               $sql = "INSERT INTO users(name, mail, pass) VALUES('$name', '$mail', '$pass')";
               $insert = $db->exec($sql);

               $members = $db->prepare('SELECT * FROM users WHERE mail=? AND pass=?');
               $members->execute(array($mail, $pass));
               $member = $members->fetch();

               $_SESSION['id'] = $member['id'];
               $_SESSION['name'] = $member['name'];
               header('Location: post_list.php');
               exit;
           } else {
               echo 'そのメールアドレスは既に登録されています';
           }
       } else {
           echo '名前、またはメールアドレスが正しく入力されていません';
       }
   } else {
       echo 'パスワードは4～100文字の半角英数字でご記入ください';
   }
}
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
       <meta charset="utf-8">
       <title>掲示板</title>
   </head>
   <body>
       <h1>掲示板</h1>
       <a href="login.php">ログインする</a>
       <h2>登録しよう！</h2>
       <form action="" method="post">
           <h3>名前</h3>
           <input type="text" name="name" placeholder="例）山田太郎" required>
           <h3>メールアドレス</h3>
           <input type="text" name="mail" placeholder="例）taro@gmail.com" required>
           <h3>パスワード</h3>
           <input type="text" name="pass" placeholder="※4～100文字の半角英数字" required>
           <p>
               <input type="submit" value="登録する">
           </p>
       </form>
   </body>
</html>

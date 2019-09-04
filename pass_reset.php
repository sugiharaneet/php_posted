<?php
require('db.php');

if (!empty($_POST['mail'])) {
   $mail = $_POST['mail'];
   if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
       $sql = 'SELECT COUNT(*) FROM users WHERE mail=?';
       $data = $db->prepare($sql);
       $data->execute(array($mail));
       $cnt = $data->fetchColumn();

       if ($cnt == 1) {
           mb_language('japanese');
           mb_internal_encoding('utf-8');

           $now = time();
           $time = $now + (60 * 30);
           $rand = mt_rand(1000, 9999);
           $mix = $mail . $rand;
           $email = base64_encode($mix);

           $sql = 'UPDATE users SET pass_edit_limit=? WHERE mail=?';
           $limit = $db->prepare($sql);
           $limit->execute(array($time, $mail));

           $to = "$mail";
           $subject = 'パスワード再設定';
           $message = "https://procir-study.site/sugihara/task19/pass_reset.php?mail=$email";
           $headers = 'From: post.list@gmail.com';

           mb_send_mail($to, $subject, $message, $headers);
           echo '再発行URLを送信しました';
           exit;
       } else {
           echo '再発行URLを送信しました';
           exit;
       }
   } else {
       $error = 'メールアドレスを正しく記入してください';
   }
}

if (!empty($_POST['pass'])) {
   $pass = $_POST['pass'];
   $now2 = time();
   $mixed = base64_decode($_GET['mail']);
   $get_mail = substr("$mixed", 0, -4);

   $sql = 'SELECT pass_edit_limit FROM users WHERE mail=?';
   $limited = $db->prepare($sql);
   $limited->execute(array($get_mail));
   $get_email = $limited->fetch();

   if(!intval($get_email['pass_edit_limit']) < $now2) {
       if (preg_match('/\A[a-z\d]{4,100}+\z/i', $pass)) {
           $sql = 'UPDATE users SET pass=? WHERE mail=?';
           $update = $db->prepare($sql);
           $update->execute(array($pass, $get_mail));

           $sql2 = 'UPDATE users SET pass_edit_limit=null WHERE mail=?';
           $reset = $db->prepare($sql2);
           $reset->execute(array($get_mail));
       } else {
           echo 'パスワードは4～100文字の半角英数字でご記入ください';
       }
   } else {
       echo '不正なアクセスです。再度お試しください。';
       echo '<a href="pass_reset.php">→やり直す</a>';
   }
}
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
       <meta charset="utf8">
       <title>課題19</title>
   </head>
   <body>
       <h1>パスワード再設定</h1>
       <?php if (!isset($update)): ?>
           <?php if (!isset($_GET['mail'])): ?>
               <form action="" type="" method="post">
                   <h2>メールアドレスを入力してください</h2>
                   <input type="email" name="mail" required>
                   <input type="submit" value="送信">
                   <?php if (isset($error)): ?>
                       <p><?php echo $error; ?></p>
                   <?php endif; ?>
                   <p><a href="login.php">戻る</a></p>
               </form>
           <?php else: ?>
               <form action="" type="" method="post">
                   <h2>新しいパスワードを入力してください</h2>
                   <input type="text" name="pass" required>
                   <input type="submit" value="保存">
               </form>
           <?php endif; ?>
       <?php else: ?>
           <p>更新完了しました</p>
           <a href="login.php">→ログインする</a>
       <?php endif; ?>
   </body>
</html>

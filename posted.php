<?php
session_start();
require('db.php');

if (!isset($_SESSION['id'])) {
   header('Location: registar.php');
   exit;
}

if ($_POST) {
   $title = $_POST['title'];
   $this_message = $_POST['message'];
   $check_title = str_replace(array(" ", "　"), "", $title);
   $check_message = str_replace(array(" ", "　"), "", $this_message);

   if ($check_title !== '' && $check_message !== '') {
       $message = $db->prepare('INSERT INTO posts SET title=?, message=?, user_id=?, delete_key=0, created=NOW()');
       $message->execute(array($title, $this_message, $_SESSION['id']));
       header('Location: post_list.php');
   } else {
       echo '空白のみの投稿はできません';
   }
}
?>
<!DOCTYPE html>
<html lang = "ja">
   <head>
       <meta charset = "utf-8">
       <title>課題17</title>
   </head>
   <body>
       <h1>掲示板</h1>
       <h2>投稿しよう！</h2>
       <?php echo $_SESSION['name'] . 'さん'; ?>
       <form action="" method="post">
           <input type="text" name="title" placeholder="例）あいさつ（タイトル）" required><br>
           <textarea name="message" cols="50" rows="10" placeholder="例）おはよ う。
こんにちは。こんばんは。（本文）" required></textarea><br>
           <input type="submit" value="投稿する">
       </form>
       <a href="post_list.php">戻る</a>
   </body>
</html>

<?php
session_start();
require('db.php');

if (isset($_SESSION['id'])) {
   $id = $_GET['id'];
   $edit_messages = $db->prepare('SELECT * FROM posts WHERE id=?');
   $edit_messages->execute(array($id));
   $edit_message = $edit_messages->fetch();
} else {
   header('Location: post_list.php');
   exit;
}

if ($edit_message['user_id'] !== $_SESSION['id']) {
   header('Location: post_list.php');
   exit;
}

if ($_POST) {
   if ($edit_message['id'] == $_REQUEST['id']) {
       $update_title = $_POST['update_title'];
       $update_message = $_POST['update_message'];
       $id = $edit_message['id'];
       $check_title = str_replace(array(" ", "　"), "", $update_title);
       $check_message = str_replace(array(" ", "　"), "", $update_message);

       if ($check_title !== '' && $check_message !== '') {
           $update = $db->prepare('UPDATE posts SET title=?,  message=? WHERE id=?');
           $update->execute(array($update_title, $update_message, $id));
           header('Location: post_list.php');
       } else {
           echo '空白のみの投稿はできません';
       }
   } else {
       header('Location: post_list.php');
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
           <h2>投稿しよう！</h2>
           <?php echo $_SESSION['name'] . 'さん'; ?>
           <form action="" method="post">
               <textarea name="update_title" required><?php echo $edit_message['title']; ?></textarea><br>
                   <textarea name="update_message" cols="50" rows="10" required><?php echo $edit_message['message']; ?></textarea><br>
                   <input type="submit" value="上書きする">
           </form>
           <a href="post_list.php">戻る</a>
   </body>
</html>

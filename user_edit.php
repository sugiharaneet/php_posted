<?php
session_start();
require('db.php');

if (!isset($_SESSION['id'])) {
   header('Location: post_list.php');
   exit;
}

if ($_POST) {
   $file_name = $_FILES['file_upload']['name'];
   if (!empty($file_name)) {
       $ext = substr($file_name, -3);
       $ext4 = substr($file_name, -4);
       if (strtolower($ext) == 'jpg' || strtolower($ext4) == 'jpeg' || strtolower($ext) == 'gif' || strtolower($ext) == 'png') {
           $date = new DateTimeImmutable();
           $date_now = $date->format('YmdHisu');
           $upload = base64_encode(mt_rand(1, 1000) . $date_now);
           if (move_uploaded_file($_FILES['file_upload']['tmp_name'], 'img/' . $upload)) {
               $comment = $_POST['comment'];
               $sql = 'UPDATE users SET image=?, comment=? WHERE id=?';

               $update = $db->prepare($sql);
               $update->execute(array($upload, $comment, $_SESSION['id']));

               header('Location: user_page.php');
               exit;
           } else {
               echo 'アップロード失敗';
           }
       } else {
           echo 'jpg、gif、png以外のファイルは指定できません';
       }
   } else {
       $comment = $_POST['comment'];
       $sql = 'UPDATE users SET comment=? WHERE id=?';

       $update = $db->prepare($sql);
       $update->execute(array($comment, $_SESSION['id']));
       header('Location: user_page.php');
       exit;
   }
}
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
       <meta charset="utf-8">
       <title>課題18</title>
   </head>
   <body>
       <h1>掲示板</h1>
       <h2>ユーザー情報編集</h2>
       <form action="" enctype="multipart/form-data" method="post">
           <h3>名前</h3>
           <?php echo $_SESSION['name']; ?>
           <h3>写真</h3>
           <input type="file" name="file_upload">
           <h3>一言コメント</h3>
           <textarea type="text" name="comment" cols="50" rows="10" required><?php echo $_SESSION['comment']; ?></textarea>
           <p>
               <input type="submit" value="保存する">
           </p>
           <a href="user_page.php">戻る</a>
       </form>
   </body>
</html>

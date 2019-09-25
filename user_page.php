<?php
session_start();
require('db.php');

if (isset($_SESSION['id'])) {
   $id = $_SESSION['id'];
   $sql = 'SELECT * FROM users WHERE id=?';
   $users = $db->prepare($sql);
   $users->execute(array($id));
   $user = $users->fetch();

   $_SESSION['comment'] = $user['comment'];
} else {
   header('Location: post_list.php');
   exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
       <meta charset='utf-8'>
       <title>掲示板</title>
   </head>
   <body>
       <h1>掲示板</h1>
       <h2>マイページ</h2>
       <h3>名前</h3>
       <?php echo $user['name']; ?>
       <h3>写真</h3>
       <?php if ($user['image'] !== NULL): ?>
           <img src="img/<?php echo $user['image']; ?>">
       <?php else: ?>
           <h4>未登録</h4>
       <?php endif; ?>
       <h3>メールアドレス</h3>
       <?php echo $user['mail']; ?>
       <h3>一言コメント</h3>
       <?php echo $user['comment']; ?>
       <p>
           <a href="user_edit.php">編集する</a><br />
           <a href="post_list.php">戻る</a>
       </p>
   </body>
</html>

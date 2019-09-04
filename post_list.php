<?php
session_start();
require('db.php');
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
       <meta charset = "utf-8">
       <title>課題18</title>
   </head>
   <body>
       <h1>掲示板</h1>
       <?php if (isset($_SESSION['id'])): ?>
           <a href="posted.php">投稿する</a>
       <?php else: ?>
           <a href="registar.php">投稿したい</a>
       <?php endif; ?>
       <h2>みんなの投稿</h2>
       <table border="1" width="80%">
           <thead>
               <th>ID</th>
               <th width="10%">名前</th>
               <th width="15%">タイトル</th>
               <th></th>
               <th width="50%">投稿内容</th>
               <th>投稿時間</th>
           </thead>
           <tbody>
               <?php $records = $db->query("SELECT p.*, u.name FROM posts p
JOIN users u ON p.user_id = u.id WHERE p.delete_key = FALSE ORDER BY p.id ASC"); ?>
               <?php while ($result = $records->fetch()): ?>
                   <tr>
                       <td><?php echo $result['id']; ?></td>
                       <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $result['user_id']): ?>
                           <td><a href="user_page.php"><?php echo $result['name']; ?></a></td>
                       <?php else: ?>
                           <td><?php echo $result['name']; ?></td>
                       <?php endif; ?>
                       <td style="word-wrap:break-word;"><?php echo $result['title']; ?></td>
                       <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $result['user_id']): ?>
                           <td><a href="update.php?id=<?php echo $result['id']; ?>">編集</a>/<a href="delete.php?id=<?php echo $result['id']; ?>">削除</a></td>
                       <?php else: ?>
                           <td></td>
                       <?php endif; ?>
                       <td style="word-wrap:break-word;"><?php echo $result['message']; ?></td>
                       <td><?php echo $result['created']; ?></td>
                   </tr>
               <?php endwhile; ?>
           </tbody>
       </table>
       <?php if (isset($_SESSION['id'])): ?>
           <a href="logout.php">ログアウトする</a>
       <?php endif; ?>
   </body>
</html>

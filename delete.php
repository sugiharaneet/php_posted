<?php
session_start();
require('db.php');

if (isset($_SESSION['id'])) {
   $id = $_REQUEST['id'];

   $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
   $messages->execute(array($id));
   $message = $messages->fetch();

   if ($message['user_id'] == $_SESSION['id']) {
       $delete = $db->prepare('UPDATE posts SET delete_key=1 WHERE id=?');
       $delete->execute(array($id));
   }
}

header('Location: post_list.php');
?>

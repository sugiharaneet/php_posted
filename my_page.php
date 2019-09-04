<?php
session_start();
require_once('db.php');
$mail = $_POST['mail'];
$pass = $_POST['pass'];
$sql = "SELECT * FROM users WHERE mail=? AND pass=?";

if ($mail == '' || $pass == '') {
   header('Location: login.php');
   exit;
}

//ログイン機能
$login = $db->prepare($sql);
$login->execute(array($mail, $pass));
$member = $login->fetch();

if ($member) {
   $_SESSION['id'] = $member['id'];
   $_SESSION['login'] = $member;
   $_SESSION['name'] = $member['name'];
} else {
   $name = $_POST['name'];
   if ($name !== '' && $mail !== '' && $pass !== '' && !ctype_space($name)) {
       $db->exec("INSERT INTO users(name, mail, pass) VALUES('$name', '$mail', '$pass')");
       $_SESSION['id'] = $member['id'];
       $_SESSION['login'] = $member;
       $_SESSION['name'] = $member['name'];
   }
}
?>
<!DOCTYPE html>
<html lang="ja">
   <head>
       <meta charset = "utf-8">
       <title>課題17</title>
   </head>
   <body>
       <h1>掲示板</h1>
       <h2>マイページ</h2>
       <?php $records = $db->query("SELECT * FROM users WHERE mail='$mail'"); ?>
       <?php while ($result = $records->fetch()): ?>
           <?php echo $result['name']; ?>
           <?php echo $result['mail']; ?>
       <?php endwhile; ?>
       <p><a href="post_list.php">掲示板を見に行こう！</p></a>
   </body>
</html>

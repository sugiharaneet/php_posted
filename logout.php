<?php
session_start();

session_destroy();

header('Location: post_list.php');
?>

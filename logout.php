<?php
  session_start();

  unset($_SESSION['auth']);
  unset($_SESSION['name']);
  header("Location: /index.php");
?>
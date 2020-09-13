<?php

  function db_connect($host, $login, $password, $db) {

    $connect = mysqli_connect($host, $login, $password, $db);
    mysqli_set_charset($connect, 'utf8');
    
    if (!$connect) {
        echo 'Ошибка подключения: '.mysqli_connect_error();
        die();
    }

    return $connect;
  }

?>
<?php
  session_start();
  require_once('helpers.php');
  require_once('functions.php');


  $connect = db_connect();


  $title = '404 Страница не найдена'; 


  $user_name = '';


  $is_auth = false;

  if(isset($_SESSION['name']) && isset($_SESSION['auth'])) {
      $user_name = $_SESSION['name'];
      $is_auth = $_SESSION['auth'];
  } 

  
  $categories = get_categories($connect);


  $page_content = include_template('error.php', ['categories' => $categories]);

  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth]);

  echo $layout_content;
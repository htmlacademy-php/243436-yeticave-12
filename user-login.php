<?php
  session_start();
  require_once('helpers.php');
  require_once('functions.php');

  // $is_auth = rand(0, 1);

  $user_name = '';

  $title = 'Вход';

  $connect = db_connect();

  $categories = get_categories($connect);


  $email = '';
  $password = '';


  $errors = [];
  

  if (
    isset($_POST['email']) && 
    isset($_POST['password'])
  ) {
    $email = $_POST['email'];
    $password = $_POST['password'];


    if (empty($_POST['email'])) {
      $errors['email'] = 'Введите email';
    } elseif(validateEmail('email')) {
      $errors['email'] = 'Введите корректный email';
    } elseif (!check_email($connect, $email)) {
      $errors['email'] = 'Данный email не зарегистрирован';
    } 


    if(validateFilled('password')) {
      $errors['password'] = 'Введите пароль';
    } elseif (!password_verify($_POST['password'], get_hash_password($connect, $_POST['email']))) {
      $errors['password'] = 'Вы ввели неверный пароль';
    }


    if (empty($errors)) {
      $user_name = get_name_user($connect, $_POST['email']);
      $user_id = get_id_user($connect, $_POST['email']);
      $_SESSION['auth'] = true;
      $_SESSION['name'] = $user_name;
      $_SESSION['id'] = $user_id;
      header("Location: index.php");
    }
  
  }

  $page_content = include_template('login.php', ['categories' => $categories, 'errors' => $errors, 'email' => $email, 'password' => $password]);
  
  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name]);

  echo $layout_content;

?>
<?php
  session_start();
  require_once('helpers.php');
  require_once('functions.php');

  // $is_auth = rand(0, 1);

  $user_name = '';

  if(isset($_SESSION['name'])) {
      $user_name = $_SESSION['name'];
  } 

  if (!isset($_SESSION['auth'])) {
    http_response_code(403);
    die();
  };

  $title = 'Добавление лота';

  $connect = db_connect();

  

  $categories = get_categories($connect);

  $lot_name = '';
  $select_category = '';
  $message = '';
  $lot_rate = '';
  $lot_step = '';
  $lot_date = '';
  $file_url = '';

  $errors = [];
  

  if (
    isset($_POST['lot-name']) && 
    isset($_POST['category']) && 
    isset($_POST['message']) && 
    isset($_POST['lot-rate']) && 
    isset($_POST['lot-step']) && 
    isset($_POST['lot-date'])
  ) {
    $lot_name = $_POST['lot-name'];
    $select_category = $_POST['category'];
    $message = $_POST['message'];
    $lot_rate = $_POST['lot-rate'];
    $lot_step = $_POST['lot-step'];
    $lot_date = $_POST['lot-date'];
  

    $rules = [
      'lot-name' => function() {
        return validateFilled('lot-name');
      },
      'message' => function() {
        return validateFilled('message');
      },
      'lot-rate' => function() {
        return validateFilled('lot-rate');
      },
      'lot-step' => function() {
        return validateFilled('lot-step');
      },
      'lot-date' => function() {
        return validateFilled('lot-date');
      }
    ];

    foreach ($_POST as $key => $value) {
      if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule();
      }
    }

    
    $errors = array_filter($errors);


    if (empty($_FILES['lot-img']['name'])) {
      $errors['lot-img'] = 'form--invalid';
    };

    if($_POST['category'] == 'Выберите категорию') {
      $errors['category'] = 'form--invalid';
    };


    $errors['lot-date']  = !is_date_valid($_POST['lot-date']) || (strtotime($_POST['lot-date']) < (time() + 86400)) ? 'form--invalid' : '';

    $errors = array_filter($errors);

    
    if(isset($_FILES['lot-img']) && !empty($_FILES['lot-img']['name'])) {
      $file_name = $_FILES['lot-img']['name'];
      $file_path = __DIR__ . '/uploads/';
      $file_url = 'uploads/' . $file_name;

      $mymetype = mime_content_type($_FILES['lot-img']['tmp_name']);
      $mymetype_value = ['image/jpeg', 'image/png'];

      if(!in_array($mymetype, $mymetype_value)) {
        $file_invalid = 'form__item--invalid';
        $errors['lot-img'] = 'form__item--invalid';
      }
      
      if (empty($errors)) {
        move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path.$file_name);
      }
    }


    $date_start = date('Y-m-d H:i:s', time());
    $user_id = $_SESSION['id'];


    $data = [$date_start, $lot_name, $message, $file_url, $lot_rate, $lot_date, $lot_step, $user_id, $select_category];

    if (empty($errors)) {  
      $lot_id = insert_lot($connect, $data);

      header("Location: lot.php?id=$lot_id");
    }    
    
  }

  $page_content = include_template('add-lot.php', ['categories' => $categories, 'lot_name' => $lot_name, 'select_category' => $select_category, 'message' => $message, 'lot_rate' => $lot_rate, 'lot_step' => $lot_step, 'lot_date' => $lot_date, 'errors' => $errors]);
  
  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name]);

  echo $layout_content;
  
?>
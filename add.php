<?php
  require_once('helpers.php');
  require_once('functions.php');

  $is_auth = rand(0, 1);

  $user_name = 'Добавление лота';

  $connect = db_connect();

  $categories = get_categories($connect);



  $errors = [];

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
  };

  $errors = array_filter($errors);

  $form_invalid = !empty($errors) && $_SERVER['REQUEST_METHOD'] == 'POST' ? 'form--invalid' : '';  



  foreach($errors as $key => $error) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $value_invalid[$key] = 'form__item--invalid';
    };
  };
 

  if($_POST['category'] == 'Выберите категорию') {
     $errors['category'] = 'form--invalid';
     $file_invalid = 'form__item--invalid'; 
  };


  if (empty($_FILES['lot-img']['name']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors['lot-img'] = 'form--invalid';
    $file_invalid = 'form__item--invalid';
  };

  

  if (
    isset($_POST['lot-name']) && 
    isset($_POST['category']) && 
    isset($_POST['message']) && 
    isset($_POST['lot-rate']) && 
    isset($_POST['lot-step']) && 
    isset($_POST['lot-date'])
  ) {
    $lot_name = mysqli_real_escape_string($connect, $_POST['lot-name']);
    $select_category = mysqli_real_escape_string($connect, $_POST['category']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);
    $lot_rate = intval($_POST['lot-rate']);
    $lot_step = intval($_POST['lot-step']);
    $lot_date = mysqli_real_escape_string($connect, $_POST['lot-date']);
  };




  $rate_invalid = invalid('lot-rate');

  $step_invalid = invalid('lot-step');

  $date_invalid = (strtotime($lot_date) < (time() + 86400)) && ($_SERVER['REQUEST_METHOD'] == 'POST') ? 'form__item--invalid' : $lot_date;  




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
      move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
    }
  };



  foreach($categories as $category) {
    if ($select_category == $category['name']) {
      $select_category = $category['id'];
    }    
  };

  $sql_lot = "INSERT INTO 
    lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
    VALUES
      (NOW(), '$lot_name', '$message', '$file_url', '$lot_rate', '$lot_date', '$lot_step', 1, '$select_category')";


  if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
    $result_lot = mysqli_query($connect, $sql_lot);

    if(!$result_lot) {
      $error = mysqli_error($connect);
      echo 'Ошибка MySQL: '.$error;
    }

    if($result_lot) {
      $result_id = mysqli_insert_id($connect);
      header("Location: lot.php?id=$result_id");
    }
  };


  $page_content = include_template('add-lot.php', ['categories' => $categories, 'form_invalid' => $form_invalid, 'file_invalid' => $file_invalid, 'value_invalid' => $value_invalid, 'rate_invalid' => $rate_invalid, 'step_invalid' => $step_invalid, 'date_invalid' => $date_invalid]);

  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $user_name]);

  echo $layout_content;
?>
<?php
  require_once('helpers.php');
  require_once('functions.php');

  $is_auth = rand(0, 1);

  $user_name = 'Павел';

  $title = 'Добавление лота';

  $connect = db_connect();

  $categories = get_categories($connect);



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
    }

 

    $form_invalid = !empty($errors) ? 'form--invalid' : '';
    
    $errors = array_filter($errors); 

    foreach($errors as $key => $error) {
      $value_invalid[$key] = 'form__item--invalid';
    };

    if (empty($_FILES['lot-img']['name'])) {
      $errors['lot-img'] = 'form--invalid';
      $file_invalid = 'form__item--invalid';
    };

    if($_POST['category'] == 'Выберите категорию') {
      $errors['category'] = 'form--invalid';
      $category_invalid = 'form__item--invalid'; 
    };

    $rate_invalid = validate_price('lot-rate');

    $step_invalid = validate_price('lot-step');

    $date_invalid = !is_date_valid($lot_date) && (strtotime($lot_date) < (time() + 86400)) ? 'form__item--invalid' : $lot_date;


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
    $user_id = 1;


    $data = [$date_start, $lot_name, $message, $file_url, $lot_rate, $lot_date, $lot_step, $user_id, $select_category];
    $sql_lot = "INSERT INTO lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";



    $prepare = db_get_prepare_stmt($connect, $sql_lot, $data);

    $result_id = get_result_id($prepare, $errors, $connect);

    if (empty($errors)) {
      header("Location: lot.php?id=$result_id");
    }    
    
  }
  

  $page_content = include_template('add-lot.php', ['categories' => $categories, 'form_invalid' => $form_invalid, 'file_invalid' => $file_invalid, 'value_invalid' => $value_invalid, 'rate_invalid' => $rate_invalid, 'step_invalid' => $step_invalid, 'date_invalid' => $date_invalid, 'category_invalid' => $category_invalid]);

  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title]);

  echo $layout_content;
?>
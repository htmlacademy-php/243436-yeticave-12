<?php

  session_start();
  require_once 'helpers.php';
  require_once 'functions.php';

  $connect = db_connect();

  $title = 'Добавление лота';

  $user_name = '';

  $lot_name = '';
  $select_category = '';
  $message = '';
  $lot_rate = '';
  $lot_step = '';
  $lot_date = '';
  $file_url = '';

  $errors = [];

  $is_auth = false;

  if (isset($_SESSION['name']) && isset($_SESSION['auth'])) {
      $user_name = htmlspecialchars($_SESSION['name']);
      $is_auth = $_SESSION['auth'];
  } else {
      http_response_code(403);
      header('Location: user-login.php');
      die();
  }

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
      $lot_rate = (int) $_POST['lot-rate'];
      $lot_step = (int) $_POST['lot-step'];
      $lot_date = $_POST['lot-date'];

      $rules = [
      'lot-name' => function () {
          return validateFilled('lot-name');
      },
      'message' => function () {
          return validateFilled('message');
      },
      'lot-rate' => function () {
          return validateFilled('lot-rate');
      },
      'lot-step' => function () {
          return validateFilled('lot-step');
      },
      'lot-date' => function () {
          return validateFilled('lot-date');
      },
    ];

      foreach ($_POST as $key => $value) {
          if (isset($rules[$key])) {
              $rule = $rules[$key];
              $errors[$key] = $rule();
          }
      }

      $errors['lot-name'] = show_errors('lot-name', 700, 'Введите наименование лота', 'Слишком длинное наименование');

      if (show_errors('lot-rate', 10, 'Введите начальную цену', 'Слишком большое число')) {
          $errors['lot-rate'] = show_errors('lot-rate', 10, 'Введите начальную цену', 'Слишком большое число');
      } elseif (validate_price('lot-rate') === 'form__item--invalid') {
          $errors['lot-rate'] = 'Введите корректное значение';
      }

      if (show_errors('lot-step', 10, 'Введите шаг ставки', 'Слишком большое число')) {
          $errors['lot-step'] = show_errors('lot-step', 10, 'Введите шаг ставки', 'Слишком большое число');
      } elseif (validate_price('lot-step') === 'form__item--invalid') {
          $errors['lot-step'] = 'Введите корректное значение';
      }

      $errors = array_filter($errors);

      if (empty($_FILES['lot-img']['name'])) {
          $errors['lot-img'] = 'form--invalid';
      }

      if ($_POST['category'] === 'Выберите категорию' || check_id_category($connect, $select_category)) {
          $errors['category'] = 'form--invalid';
      }

      $errors['lot-date'] = !is_date_valid($_POST['lot-date']) || (strtotime($_POST['lot-date']) - time()) < 0 ? 'form--invalid' : '';

      $errors = array_filter($errors);

      if (isset($_FILES['lot-img']) && !empty($_FILES['lot-img']['name'])) {
          $file_name = $_FILES['lot-img']['name'];
          $file_path = __DIR__.'/uploads/';
          $file_url = 'uploads/'.$file_name;

          $mymetype = mime_content_type($_FILES['lot-img']['tmp_name']);
          $mymetype_value = ['image/jpeg', 'image/png'];

          if (!in_array($mymetype, $mymetype_value)) {
              $file_invalid = 'form__item--invalid';
              $errors['lot-img'] = 'form__item--invalid';
          }

          if (empty($errors)) {
              move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path.$file_name);
          }
      }

      $date_start = date('Y-m-d H:i:s', time());
      $user_id = (int) $_SESSION['id'];

      $data = [$date_start, $lot_name, $message, $file_url, $lot_rate, $lot_date, $lot_step, $user_id, $select_category];

      if (empty($errors)) {
          $lot_id = insert_lot($connect, $data);

          header("Location: lot.php?id=$lot_id");
      }
  }

  $page_content = include_template('add-lot.php', ['categories' => $categories, 'lot_name' => $lot_name, 'select_category' => $select_category, 'message' => $message, 'lot_rate' => $lot_rate, 'lot_step' => $lot_step, 'lot_date' => $lot_date, 'errors' => $errors]);

  $layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $title, 'user_name' => $user_name, 'is_auth' => $is_auth]);

  echo $layout_content;

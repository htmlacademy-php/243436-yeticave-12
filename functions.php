<?php

  function db_connect() {

    $connect = mysqli_connect('localhost', 'root', 'root', 'yeticave');
    mysqli_set_charset($connect, 'utf8');
    
    if (!$connect) {
        echo 'Ошибка подключения: '.mysqli_connect_error();
        die();
    }

    return $connect;
  }



  function get_dt_range ($date) {
    $future_time = strtotime($date);
    $now_time = time();
    $result_time_hour = floor(($future_time - $now_time) / 3600);
    $result_time_min = floor((($future_time - $now_time) % 3600)/60);
  
    $result_time_min = str_pad($result_time_min, 2, "0", STR_PAD_LEFT);;
    $result_time_hour = str_pad($result_time_hour, 2, "0", STR_PAD_LEFT);;
  
    return [$result_time_hour, $result_time_min];
  };


  
  function get_sum ($cost) {
    
    ceil($cost);
  
    if ($cost >= 1000) {
      $cost = number_format($cost, 0, ',', ' ');
    }
    
    return $cost.' ₽';
  };

  
  
  function get_categories($connect) {
    $sql_categories = 'SELECT id, name, code FROM category'; 

    $result_categories = mysqli_query($connect, $sql_categories);

    if(!$result_categories) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: '.$error;
    }

    $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

    return $categories;
  }


  function validateFilled($name) {
    if (empty($_POST[$name])) {
      return "form--invalid";
    }
  }

  function invalid($price) {

    if($_SERVER['REQUEST_METHOD'] == 'POST') {    
      $invalid_price = !filter_var($_POST[$price], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ? 'form__item--invalid' : intval($_POST[$price]);
    };

    return $invalid_price;
  }

?>
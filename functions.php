<?php
  /**
  * mysqli Ресурс соединения
  */
  function db_connect() {

    $connect = mysqli_connect('localhost', 'root', 'root', 'yeticave');
    mysqli_set_charset($connect, 'utf8');
    
    if (!$connect) {
        echo 'Ошибка подключения: '.mysqli_connect_error();
        die();
    }

    return $connect;
  }


  /**
  * Вовращает остаток времени до будущей даты и добавляет к строке 0, если остаток часов или минут меньше 10
  *
  * @param $date будущая дата в формате '2020-10-15'
  */
  function get_dt_range ($date) {
    $future_time = strtotime($date);
    $now_time = time();
    $result_time_hour = floor(($future_time - $now_time) / 3600);
    $result_time_min = floor((($future_time - $now_time) % 3600)/60);
  
    $result_time_min = str_pad($result_time_min, 2, "0", STR_PAD_LEFT);;
    $result_time_hour = str_pad($result_time_hour, 2, "0", STR_PAD_LEFT);;
  
    return [$result_time_hour, $result_time_min];
  };

  /**
  * Форматирует число с разделением групп и добавляет занак '₽' к сумме
  *
  * Пример использования:
  * get_sum(10000); // 10 000 ₽
  * 
  * @param $cost число для форматирования
  */  
  function get_sum ($cost) {
    
    ceil($cost);
  
    if ($cost >= 1000) {
      $cost = number_format($cost, 0, ',', ' ');
    }
    
    return $cost.' ₽';
  };
  
  /**
  * Возвращает массив со всеми данным таблицы category из БД
  * 
  * @param $connect mysqli Ресурс соединения
  */  
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

  /**
  * Валидация на пустое значение
  * 
  * @param $name проверяемое значение
  */  
  function validateFilled($name) {
    if (empty($_POST[$name])) {
      return "form--invalid";
    }
  }

  /**
  * Валидация на целое, число которое больше 0
  * 
  * @param $price проверяемое значение
  */  
  function invalid($price) { 
    $invalid_price = !filter_var($_POST[$price], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ? 'form__item--invalid' : $_POST[$price];

    return $invalid_price;
  }


?>
<?php
  /**
   * @return mysqli Ресурс соединения
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
   * @param string $date будущая дата в формате '2020-10-15'
   *
   * @return array остаток часов и минут
   */
  function get_dt_range (string $date) {
    $future_time = strtotime($date);
    $now_time = time();
    $result_time_hour = floor(($future_time - $now_time) / 3600);
    $result_time_min = floor((($future_time - $now_time) % 3600)/60);
    $result_time_sec = floor((($future_time - $now_time) % 3600)%60);
  
    $result_time_min = str_pad($result_time_min, 2, "0", STR_PAD_LEFT);
    $result_time_hour = str_pad($result_time_hour, 2, "0", STR_PAD_LEFT);
    $result_time_sec = str_pad($result_time_sec, 2, "0", STR_PAD_LEFT);
  
    return [$result_time_hour, $result_time_min, $result_time_sec];
  };

  /**
   * Форматирует число с разделением групп и добавляет занак '₽' к сумме
   *
   * Пример использования:
   * get_sum(10000); // 10 000 ₽
   * 
   * @param float $cost число для форматирования
   *
   * @return string цена
   */  
  function get_sum (float $cost) {
    
    $cost = ceil($cost);
  
    if ($cost >= 1000) {
      $cost = number_format($cost, 0, ',', ' ');
    }
    
    return $cost.' ₽';
  };

  /**
   * Форматирует число с разделением групп и добавляет занак 'р' к сумме
   *
   * Пример использования:
   * get_sum(10000); // 10 000 р
   * 
   * @param float $cost число для форматирования
   *
   * @return string цена
   */  
  function get_rate (float $cost) {
    
    $cost = ceil($cost);
  
    if ($cost >= 1000) {
      $cost = number_format($cost, 0, ',', ' ');
    }
    
    return $cost.' р';
  };
  
  /**
   * Возвращает массив со всеми данным таблицы category из БД
   * 
   * @param mysqli $connect Ресурс соединения
   *
   * @return array список категорий
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
   * Возвращает массив завершенных лотов
   * 
   * @param mysqli $connect Ресурс соединения
   * @param integer $user_id Id пользователя
   *
   * @return array список завершенных лотов
   */  
  function get_lots_finish($connect, $user_id) {
    $sql_lot_finish = "SELECT rate.user_id AS user_id, rate.lot_id AS lot_id, MAX(rate.cost) AS rate_cost, lot.date_finish AS lot_date_finish, lot.winner_id AS winner_id 
	FROM rate
		JOIN lot ON lot.id = rate.lot_id  
		WHERE rate.user_id = $user_id AND lot.date_finish < NOW()
    GROUP BY rate.lot_id";
    
    $result_lot_finish = mysqli_query($connect, $sql_lot_finish);

    if(!$result_lot_finish) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: '.$error;
    }
  
    $lots_finish = mysqli_fetch_all($result_lot_finish, MYSQLI_ASSOC);

    return $lots_finish;
  }

  /**
   * Валидация на пустое значение
   * 
   * @param string $name проверяемое значение
   *
   * @return string|null наименование класса для валидации
   */  
  function validateFilled(string $name) {
    if (empty($_POST[$name])) {
      return "form--invalid";
    }
  }

  /**
   * Валидация на email
   * 
   * @param string $name проверяемое значение
   *
   * @return string|null возвращает ошибку, если email не корректный
   */  
  function validateEmail($name) {
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
      return "Введите корректный email";
    }
  }

  /**
   * Валидация на целое, число которое больше 0
   * 
   * @param $price проверяемое значение
   *
   * @return string наименование класса для валидации или введенное значение в поле
   */  
  function validate_price($price) {
    $invalid_price = '';
    
    if(isset($_POST[$price])) {
      $invalid_price = !filter_var($_POST[$price], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ? 'form__item--invalid' : $_POST[$price];
    }

    return $invalid_price;
  }

  /**
   * Возвращает id последнего добавленного лота
   *
   * @param $link mysqli Ресурс соединения
   * @param array $data Данные для вставки на место плейсхолдеров
   *
   * @return integer id лота
   */ 
  function insert_lot($link, $data = []) {
    $sql_lot = "INSERT INTO lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $prepare = db_get_prepare_stmt($link, $sql_lot, $data);

    $result_id = '';

    if(!mysqli_stmt_execute($prepare)) {
      $error = mysqli_error($link);
      echo 'Ошибка MySQL: '.$error;
      die();
    };

    $result_id = mysqli_insert_id($link);

    return $result_id;
  }

  /**
   * Возвращает ошибку, если email уже используется на сайте
   *
   * @param $link mysqli Ресурс соединения
   * @param string $data email, который нужно проверить
   *
   * @return string|null возвращает ошибку, если email есть в БД
   */ 
  function check_email($link, $data) {
    $errors = '';

    $sql_email = "SELECT email FROM user WHERE email = ?";

    $stmt = mysqli_prepare($link, $sql_email);

    mysqli_stmt_bind_param($stmt, 's', $data);

    mysqli_stmt_execute($stmt);

    $result_email = mysqli_stmt_get_result($stmt);

    if(!$result_email) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: '.$error;
        die();
    }

    if(mysqli_num_rows($result_email)) {
      $errors = 'Данный email уже используется';
    };

    return $errors;
  }

  /**
   * Возвращает массив данных пользователя
   *
   * @param $link mysqli Ресурс соединения
   * @param string $data email, по которому подбираем данные пользователя
   *
   * @return array|null возвращает данные пользователя
   */ 
  function get_data_user($link, $data) {

    $sql_data_user = "SELECT id, name, password FROM user WHERE email = ?";

    $stmt = mysqli_prepare($link, $sql_data_user);

    mysqli_stmt_bind_param($stmt, 's', $data);

    mysqli_stmt_execute($stmt);

    $result_data_user = mysqli_stmt_get_result($stmt);

    if(!$result_data_user) {
      $error = mysqli_error($link);
      echo 'Ошибка MySQL: '.$error;
      die();
    }

    $fetch_data_user = mysqli_fetch_all($result_data_user, MYSQLI_ASSOC);

    if($fetch_data_user == []) {
      $fetch_data_user = null;
    }
     
    return $fetch_data_user;
  }

  
  /**
   * Добавление пользователя
   *
   * @param $link mysqli Ресурс соединения
   * @param array $data Данные для вставки на место плейсхолдеров
   */ 
  function insert_user($link, $data = []) {

    $sql_user = "INSERT INTO user(created_at, email, name, password, contact) 
      VALUES(?, ?, ?, ?, ?)";

    $prepare = db_get_prepare_stmt($link, $sql_user, $data);

    if(!mysqli_stmt_execute($prepare)) {
      $error = mysqli_error($link);
      echo 'Ошибка MySQL: '.$error;
      die();
    };
  }

  /**
   * Добавление  ставки
   *
   * @param $link mysqli Ресурс соединения
   * @param array $data Данные для вставки на место плейсхолдеров
   */ 
  function insert_rate($link, $data = []) {

    $sql_rate = "INSERT INTO rate(date, cost, user_id, lot_id) 
    VALUES(?, ?, ?, ?)";

    $prepare = db_get_prepare_stmt($link, $sql_rate, $data);

    if(!mysqli_stmt_execute($prepare)) {
      $error = mysqli_error($link);
      echo 'Ошибка MySQL: '.$error;
      die();
    };
  }

  /**
   * Время добавления ставки
   *
   * @param string $rate_time дата добавления ставки
   * 
   * @return string возвращает время, которое прошло с добавления ставки
   */
  function get_time_rate($rate_time) {
    $time_public = '';
    $now_time = time();
    $past_time = strtotime($rate_time);
    $result_time_hour = floor(($now_time - $past_time) / 3600);
    $result_time_min = floor((($now_time - $past_time) % 3600)/60);
    $result_time_sec = floor((($now_time - $past_time) % 3600)%60);
    $result_all_second = $now_time - $past_time;
  

    if($result_time_hour == 0 && $result_time_min == 0 && $result_time_sec < 60) {
      $time_public = 'только что';
    } elseif($result_time_hour == 0 && $result_time_min < 60 && $result_time_min >= 1) {
      $time_public = $result_time_min. get_noun_plural_form($result_time_min, ' минуту', ' минуты', ' минут', ' минута').' назад';
    } elseif($result_time_hour >= 1 && $result_all_second < 7200) {
      $time_public = 'Час назад';
    } elseif($result_all_second >= 7200) {
      $time_public = date('d.m.y', $past_time).' в '.date('H:i', $past_time);
    }
    return $time_public;
  }
  

?>
<?php

require_once('helpers.php');

$hours = get_dt_range($value['time']);

$is_auth = rand(0, 1);

$user_name = 'Павел'; // укажите здесь ваше имя



$con = mysqli_connect('localhost', 'root', 'root', 'yeticave');
mysqli_set_charset($con, 'utf8');

if (!$con) {
    echo 'Ошибка подключения: '.mysqli_connect_error();
}



$sql_lots = 'SELECT date_finish, category.name AS category, title, path, lot.cost, MAX(rate.cost) AS current_price
    FROM lot 
        JOIN category ON lot.category_id = category.id
        JOIN rate ON rate.lot_id = lot.id
            WHERE date_finish > NOW()
            GROUP BY lot.id
            ORDER BY lot.date_start DESC';

$result_lots = mysqli_query($con, $sql_lots);

if(!$result_lots) {
    $error = mysqli_error($con);
    echo 'Ошибка MySQL: '.$error;
}

$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);



$sql_categories = 'SELECT name, code FROM category'; 

$result_categories = mysqli_query($con, $sql_categories);

if(!$result_categories) {
    $error = mysqli_error($con);
    echo 'Ошибка MySQL: '.$error;
}

$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);





// $categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

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

$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);

$layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $user_name]);

echo $layout_content;

?>

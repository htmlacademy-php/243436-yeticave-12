<?php

require_once('helpers.php');

$hours = get_dt_range($value['time']);

$is_auth = rand(0, 1);

$user_name = 'Павел'; // укажите здесь ваше имя

$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

$lots = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'url' => 'img/lot-1.jpg',
        'time' => '2020-09-08'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'url' => 'img/lot-2.jpg',
        'time' => '2020-09-09'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'url' => 'img/lot-3.jpg',
        'time' => '2020-09-08'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'url' => 'img/lot-4.jpg',
        'time' => '2020-09-10'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'url' => 'img/lot-5.jpg',
        'time' => '2020-09-12'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'url' => 'img/lot-6.jpg',
        'time' => '2020-09-11'
    ]
];

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

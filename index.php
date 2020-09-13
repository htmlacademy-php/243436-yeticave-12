<?php

require_once('helpers.php');
require_once('functions.php');

$is_auth = rand(0, 1);

$user_name = 'Павел';


$connect = db_connect('localhost', 'root', 'root', 'yeticave');



$sql_lots = 'SELECT lot.id, date_finish, category.name AS category, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price
    FROM lot 
        JOIN category ON lot.category_id = category.id
        LEFT JOIN rate ON rate.lot_id = lot.id
            WHERE date_finish > NOW()
            GROUP BY lot.id
            ORDER BY lot.date_start DESC';

$result_lots = mysqli_query($connect, $sql_lots);

if(!$result_lots) {
    $error = mysqli_error($connect);
    echo 'Ошибка MySQL: '.$error;
}

$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);



$sql_categories = 'SELECT name, code FROM category'; 

$result_categories = mysqli_query($connect, $sql_categories);

if(!$result_categories) {
    $error = mysqli_error($connect);
    echo 'Ошибка MySQL: '.$error;
}

$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);



$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);

$layout_content = include_template('layout.php', ['content' => $page_content, 'categories' => $categories, 'title' => $user_name]);

echo $layout_content;

?>

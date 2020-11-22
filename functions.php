<?php

/**
 * @return mysqli Ресурс соединения
 */
function db_connect()
{
    $connect = mysqli_connect('localhost', 'root', 'root', 'yeticave');
    mysqli_set_charset($connect, 'utf8');

    if (!$connect) {
        echo 'Ошибка подключения: ' . mysqli_connect_error();
        die();
    }

    return $connect;
}

/**
 * Вовращает остаток времени до будущей даты и добавляет к строке 0, если остаток часов или минут меньше 10.
 *
 * @param string $date будущая дата в формате '2020-10-15'
 *
 * @return array остаток часов и минут
 */
function get_dt_range(string $date)
{
    $future_time = strtotime($date);
    $now_time = time();
    $result_time_hour = floor(($future_time - $now_time) / 3600);
    $result_time_min = floor((($future_time - $now_time) % 3600) / 60);
    $result_time_sec = floor((($future_time - $now_time) % 3600) % 60);

    $result_time_min = str_pad($result_time_min, 2, '0', STR_PAD_LEFT);
    $result_time_hour = str_pad($result_time_hour, 2, '0', STR_PAD_LEFT);
    $result_time_sec = str_pad($result_time_sec, 2, '0', STR_PAD_LEFT);

    return [$result_time_hour, $result_time_min, $result_time_sec];
}

/**
 * Форматирует число с разделением групп и добавляет занак '₽' к сумме.
 *
 * Пример использования:
 * get_sum(10000); // 10 000 ₽
 *
 * @param float $cost число для форматирования
 *
 * @return string цена
 */
function get_sum(float $cost)
{
    $cost = ceil($cost);

    if ($cost >= 1000) {
        $cost = number_format($cost, 0, ',', ' ');
    }

    return $cost . ' ₽';
}

/**
 * Форматирует число с разделением групп и добавляет занак 'р' к сумме.
 *
 * Пример использования:
 * get_sum(10000); // 10 000 р
 *
 * @param float $cost число для форматирования
 *
 * @return string цена
 */
function get_rate(float $cost)
{
    $cost = ceil($cost);

    if ($cost >= 1000) {
        $cost = number_format($cost, 0, ',', ' ');
    }

    return $cost . ' р';
}

/**
 * Валидация на пустое значение.
 *
 * @param string $name проверяемое значение
 *
 * @return string|null наименование класса для валидации
 */
function validateFilled(string $name)
{
    if (empty($_POST[$name])) {
        return 'form--invalid';
    } else {
        return null;
    }
}

/**
 * Валидация на email.
 *
 * @param string $name проверяемое значение
 *
 * @return string|null возвращает ошибку, если email не корректный
 */
function validateEmail(string $name)
{
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
        return 'Введите корректный email';
    } else {
        return null;
    }
}

/**
 * Валидация на проверку длины строки.
 *
 * @param string $name проверяемое значение
 * @param int $max максимальное значение длины поля
 *
 * @return string|null возвращает ошибку, если длина строки не корректная
 */
function isCorrectLength(string $name, int $max)
{
    $len = mb_strlen($_POST[$name], 'utf8');

    if ($len >= $max) {
        return "Значение должно быть до $max символов";
    } else {
        return null;
    }
}

/**
 * Валидация на целое, число которое больше 0.
 *
 * @param $price проверяемое значение
 *
 * @return string наименование класса для валидации или введенное значение в поле
 */
function validate_price($price)
{
    $invalid_price = '';

    if (isset($_POST[$price])) {
        $invalid_price = !filter_var($_POST[$price], FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]) ? 'form__item--invalid' : $_POST[$price];
    }

    return $invalid_price;
}

/**
 * Показ ошибок при валидации.
 *
 * @param string $name проверяемое значение
 * @param int $max максимальное значение длины поля
 * @param string $show_one описание первой ошибки
 * @param string $show_two описание второй ошибки
 *
 * @return string|null возвращает наименование ошибки, если валидация не прошла
 */
function show_errors(string $name, int $max, string $show_one, string $show_two)
{
    if (empty($_POST[$name])) {
        return $errors[$name] = $show_one;
    } elseif (isCorrectLength($name, $max)) {
        return $errors[$name] = $show_two;
    }
}

/**
 * Возвращает массив со всеми данным таблицы category из БД.
 *
 * @param mysqli $connect Ресурс соединения
 *
 * @return array список категорий
 */
function get_categories($connect)
{
    $sql_categories = 'SELECT id, name, code FROM category';

    $result_categories = mysqli_query($connect, $sql_categories);

    if (!$result_categories) {
        $error = mysqli_error($connect);
        echo 'Ошибка MySQL: ' . $error;
    }

    $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

    return $categories;
}

/**
 * Возвращает массив лотов, по которым пользователь делал ставку.
 *
 * @param $link mysqli Ресурс соединения
 * @param string $user_id Id пользователя
 *
 * @return array|null массив лотов, по которым пользователь делал ставку
 */
function get_rates_lots_user($link, string $user_id)
{
    $sql_rate = 'SELECT rate.user_id AS user_id, rate.lot_id AS lot_id, rate.cost AS rate_cost, rate.date AS rate_date, lot.path AS img_path, lot.title AS lot_name, lot.category_id AS category_id, lot.date_finish AS lot_date_finish, category.name AS category_name, lot.winner_id AS winner_id, user.contact AS contact
    FROM rate
      JOIN lot ON lot.id = rate.lot_id
      JOIN user ON user.id = rate.user_id
      JOIN category ON category.id = lot.category_id
      WHERE rate.user_id = ?
      ORDER BY rate.date DESC';

    $stmt = mysqli_prepare($link, $sql_rate);

    mysqli_stmt_bind_param($stmt, 'i', $user_id);

    mysqli_stmt_execute($stmt);

    $result_rate = mysqli_stmt_get_result($stmt);

    if (!$result_rate) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_rate = mysqli_fetch_all($result_rate, MYSQLI_ASSOC);

    if ($fetch_rate == []) {
        $fetch_rate = null;
    }

    return $fetch_rate;
}

/**
 * Возвращает карточку лота.
 *
 * @param $link mysqli Ресурс соединения
 * @param int $lot_id id лота
 *
 * @return array|null массив запрошенного лота
 */
function get_lot($link, int $lot_id)
{
    $sql_lot = 'SELECT lot.rate_step, date_finish, description, category.name AS category, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price
      FROM lot
          JOIN category ON lot.category_id = category.id
          LEFT JOIN rate ON rate.lot_id = lot.id
              WHERE lot.id = ?
              GROUP BY lot.id';

    $stmt = mysqli_prepare($link, $sql_lot);

    mysqli_stmt_bind_param($stmt, 'i', $lot_id);

    mysqli_stmt_execute($stmt);

    $result_lot = mysqli_stmt_get_result($stmt);

    if (!$result_lot) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_lot = mysqli_fetch_assoc($result_lot);

    if ($fetch_lot == []) {
        $fetch_lot = null;
    }

    return $fetch_lot;
}

/**
 * Возвращает количество категорий.
 *
 * @param $link mysqli Ресурс соединения
 * @param int $category_id id категории
 *
 * @return int количество категорий
 */
function get_lot_count($link, int $category_id)
{
    $sql_categories = 'SELECT category.id AS category_id
      FROM category
        WHERE category.id = ?';

    $stmt = mysqli_prepare($link, $sql_categories);

    mysqli_stmt_bind_param($stmt, 'i', $category_id);

    mysqli_stmt_execute($stmt);

    $result_categories = mysqli_stmt_get_result($stmt);

    if (!$result_categories) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $category_count = mysqli_num_rows($result_categories);

    return $category_count;
}

/**
 * Получение количества лотов для пагинации.
 *
 * @param $link mysqli Ресурс соединения
 * @param int $category_id id категории
 *
 * @return array|null количество категорий
 */
function get_category_count($link, int $category_id)
{
    $sql_lots_count = 'SELECT COUNT(*) as count FROM lot WHERE date_finish > NOW() AND category_id = ?';

    $stmt = mysqli_prepare($link, $sql_lots_count);

    mysqli_stmt_bind_param($stmt, 'i', $category_id);

    mysqli_stmt_execute($stmt);

    $result_lots_count = mysqli_stmt_get_result($stmt);

    if (!$result_lots_count) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_lots_count = mysqli_fetch_all($result_lots_count, MYSQLI_ASSOC)[0];

    return $fetch_lots_count;
}

/**
 * Получение списка существующих id категорий.
 *
 * @param $link mysqli Ресурс соединения
 * @param int $category_id id категории
 *
 * @return array|null количество категорий
 */
function get_list_id_category($link, int $category_id)
{
    $sql_id_category = 'SELECT id AS category_id FROM category WHERE id = ?';

    $stmt = mysqli_prepare($link, $sql_id_category);

    mysqli_stmt_bind_param($stmt, 'i', $category_id);

    mysqli_stmt_execute($stmt);

    $result_id_category = mysqli_stmt_get_result($stmt);

    if (!$result_id_category) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_id_category = mysqli_fetch_assoc($result_id_category);

    if ($fetch_id_category == []) {
        $fetch_id_category = null;
    }

    return $fetch_id_category;
}

/**
 * Получение списка лотов по категории.
 *
 * @param $link mysqli Ресурс соединения
 * @param int $category_id id категории
 * @param int $page_items лимит показа лотов на странице
 * @param int $offset смещение
 *
 * @return int количество категорий
 */
function get_lot_category_count($link, int $category_id, int $page_items, int $offset)
{
    $sql_lots = 'SELECT lot.id, date_finish, category.name AS category, lot.category_id AS category_id, title, path, IFNULL(MAX(rate.cost), lot.cost) AS current_price, (SELECT COUNT(*) FROM rate WHERE rate.lot_id = lot.id) AS count_rate
    FROM lot
      JOIN category ON lot.category_id = category.id
      LEFT JOIN rate ON rate.lot_id = lot.id
        WHERE date_finish > NOW() AND category.id = ?
        GROUP BY lot.id
        ORDER BY lot.date_start DESC LIMIT ? OFFSET  ?';

    $stmt = mysqli_prepare($link, $sql_lots);

    mysqli_stmt_bind_param($stmt, 'iii', $category_id, $page_items, $offset);

    mysqli_stmt_execute($stmt);

    $result_lots = mysqli_stmt_get_result($stmt);

    if (!$result_lots) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

    return $fetch_lots;
}

/**
 * Возвращает список ставок по лоту.
 *
 * @param $link mysqli Ресурс соединения
 * @param int $lot_id id лота
 *
 * @return array|null массив ставок по лоту
 */
function get_lot_rates($link, int $lot_id)
{
    $sql_rate = 'SELECT rate.date AS date, rate.cost, user.name, user.id AS user_id, lot.id
    FROM rate
        JOIN user ON rate.user_id = user.id
        JOIN lot ON rate.lot_id = lot.id
        WHERE lot.id = ?
        ORDER BY rate.date DESC';

    $stmt = mysqli_prepare($link, $sql_rate);

    mysqli_stmt_bind_param($stmt, 'i', $lot_id);

    mysqli_stmt_execute($stmt);

    $result_rate = mysqli_stmt_get_result($stmt);

    if (!$result_rate) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_rate = mysqli_fetch_all($result_rate, MYSQLI_ASSOC);

    if ($fetch_rate == []) {
        $fetch_rate = null;
    }

    return $fetch_rate;
}

/**
 * Возвращает id последнего добавленного лота.
 *
 * @param $link mysqli Ресурс соединения
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return int id лота
 */
function insert_lot($link, array $data = [])
{
    $sql_lot = 'INSERT INTO lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id)
    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

    $prepare = db_get_prepare_stmt($link, $sql_lot, $data);

    $result_id = '';

    if (!mysqli_stmt_execute($prepare)) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $result_id = mysqli_insert_id($link);

    return $result_id;
}

/**
 * Возвращает ошибку, если email уже используется на сайте.
 *
 * @param $link mysqli Ресурс соединения
 * @param string $data email, который нужно проверить
 *
 * @return string|null возвращает ошибку, если email есть в БД
 */
function check_email($link, string $data)
{
    $errors = '';

    $sql_email = 'SELECT email FROM user WHERE email = ?';

    $stmt = mysqli_prepare($link, $sql_email);

    mysqli_stmt_bind_param($stmt, 's', $data);

    mysqli_stmt_execute($stmt);

    $result_email = mysqli_stmt_get_result($stmt);

    if (!$result_email) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    if (mysqli_num_rows($result_email)) {
        $errors = 'Данный email уже используется';
    }

    return $errors;
}

/**
 * Возвращает ошибку, если id категории нет в базе данных.
 *
 * @param $link mysqli Ресурс соединения
 * @param int $data id, который нужно проверить
 *
 * @return string|null возвращает ошибку, если id отсутствует в БД
 */
function check_id_category($link, int $data)
{
    $errors = '';

    $sql_id_category = 'SELECT id FROM category WHERE id = ?';

    $stmt = mysqli_prepare($link, $sql_id_category);

    mysqli_stmt_bind_param($stmt, 's', $data);

    mysqli_stmt_execute($stmt);

    $result_id_category = mysqli_stmt_get_result($stmt);

    if (!$result_id_category) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    if (!mysqli_num_rows($result_id_category)) {
        $errors = 'Данный id категории не существует';
    }

    return $errors;
}

/**
 * Возвращает массив данных пользователя.
 *
 * @param $link mysqli Ресурс соединения
 * @param string $data email, по которому подбираем данные пользователя
 *
 * @return array|null возвращает данные пользователя
 */
function get_data_user($link, string $data)
{
    $sql_data_user = 'SELECT id, name, password FROM user WHERE email = ?';

    $stmt = mysqli_prepare($link, $sql_data_user);

    mysqli_stmt_bind_param($stmt, 's', $data);

    mysqli_stmt_execute($stmt);

    $result_data_user = mysqli_stmt_get_result($stmt);

    if (!$result_data_user) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_data_user = mysqli_fetch_all($result_data_user, MYSQLI_ASSOC);

    if ($fetch_data_user == []) {
        $fetch_data_user = null;
    }

    return $fetch_data_user;
}

/**
 * Возвращает id пользователя создавшего лот
 *
 * @param $link mysqli Ресурс соединения
 * @param int $data id лота
 *
 * @return array|null возвращает id пользователя
 */
function get_user_id_create_lot($link, int $data)
{
    $sql_user_id = 'SELECT user_id FROM lot WHERE id = ?';

    $stmt = mysqli_prepare($link, $sql_user_id);

    mysqli_stmt_bind_param($stmt, 'i', $data);

    mysqli_stmt_execute($stmt);

    $result_user_id = mysqli_stmt_get_result($stmt);

    if (!$result_user_id) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_user_id = mysqli_fetch_assoc($result_user_id);

    if ($fetch_user_id == []) {
        $fetch_user_id = null;
    }

    return $fetch_user_id;
}

/**
 * Возвращает id пользователя, сделавшего последнюю ставку в лоте.
 *
 * @param $link mysqli Ресурс соединения
 * @param int $data id лота
 *
 * @return array|null возвращает id лота
 */
function get_user_id_last_rate($link, int $data)
{
    $sql_rate_user_id = 'SELECT rate.user_id
    FROM rate
      WHERE rate.lot_id = ?
        ORDER BY cost DESC LIMIT 1';

    $stmt = mysqli_prepare($link, $sql_rate_user_id);

    mysqli_stmt_bind_param($stmt, 'i', $data);

    mysqli_stmt_execute($stmt);

    $result_rate_user_id = mysqli_stmt_get_result($stmt);

    if (!$result_rate_user_id) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }

    $fetch_rate_user_id = mysqli_fetch_assoc($result_rate_user_id);

    if ($fetch_rate_user_id == []) {
        $fetch_rate_user_id = null;
    }

    return $fetch_rate_user_id;
}

/**
 * Добавление пользователя.
 *
 * @param $link mysqli Ресурс соединения
 * @param array $data Данные для вставки на место плейсхолдеров
 */
function insert_user($link, array $data = [])
{
    $sql_user = 'INSERT INTO user(created_at, email, name, password, contact)
      VALUES(?, ?, ?, ?, ?)';

    $prepare = db_get_prepare_stmt($link, $sql_user, $data);

    if (!mysqli_stmt_execute($prepare)) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }
}

/**
 * Добавление  ставки.
 *
 * @param $link mysqli Ресурс соединения
 * @param array $data Данные для вставки на место плейсхолдеров
 */
function insert_rate($link, array $data = [])
{
    $sql_rate = 'INSERT INTO rate(date, cost, user_id, lot_id)
    VALUES(?, ?, ?, ?)';

    $prepare = db_get_prepare_stmt($link, $sql_rate, $data);

    if (!mysqli_stmt_execute($prepare)) {
        $error = mysqli_error($link);
        echo 'Ошибка MySQL: ' . $error;
        die();
    }
}

/**
 * Время добавления ставки.
 *
 * @param string $rate_time дата добавления ставки
 *
 * @return string возвращает время, которое прошло с добавления ставки
 */
function get_time_rate(string $rate_time)
{
    $time_public = '';
    $now_time = time();
    $past_time = strtotime($rate_time);
    $result_time_hour = floor(($now_time - $past_time) / 3600);
    $result_time_min = floor((($now_time - $past_time) % 3600) / 60);
    $result_time_sec = floor((($now_time - $past_time) % 3600) % 60);
    $result_all_second = $now_time - $past_time;

    if ($result_time_hour == 0 && $result_time_min == 0 && $result_time_sec < 60) {
        $time_public = 'только что';
    } elseif ($result_time_hour == 0 && $result_time_min < 60 && $result_time_min >= 1) {
        $time_public = $result_time_min . get_noun_plural_form($result_time_min, ' минуту', ' минуты', ' минут',
                ' минута') . ' назад';
    } elseif ($result_time_hour >= 1 && $result_all_second < 7200) {
        $time_public = 'Час назад';
    } elseif ($result_all_second >= 7200) {
        $time_public = date('d.m.y', $past_time) . ' в ' . date('H:i', $past_time);
    }

    return $time_public;
}

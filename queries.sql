USE yeticave;


-- Добавляем в таблицу недостающие категории
INSERT INTO category(name, code) VALUES ('Доски и лыжи', 'boards'), ('Крепления', 'attachment'), ('Ботинки', 'boots'), ('Одежда', 'clothing'), ('Инструменты', 'tools'), ('Разное', 'other');

-- Добавляем двух новых пользователей
INSERT INTO 
  user(created_at, email, name, password, contact) 
  VALUES (NOW(), 'mikhail@mail.ru', 'Mikhail', '0000', 'telegram: @mikhail');

INSERT INTO 
  user(created_at, email, name, password, contact) 
  VALUES (NOW(), 'alex@mail.ru', 'Alex', '111', 'skype: alex');

-- Добавляем существующие объявления(лоты)
INSERT INTO 
  lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
  VALUES 
    (NOW(),
    '2014 Rossignol District Snowboard',
    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
    'img/lot-1.jpg',
    10999,
    '2020-09-20',
    12000, 
    1, 
    1);

INSERT INTO 
  lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
  VALUES 
    (NOW(),
    'DC Ply Mens 2016/2017 Snowboard',
    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
    'img/lot-2.jpg',
    159999,
    '2020-09-25',
    160000,
    1,
    1); 

INSERT INTO 
  lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
  VALUES 
    (NOW(),
    'Крепления Union Contact Pro 2015 года размер L/XL',
    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
    'img/lot-3.jpg',
    8000,
    '2020-09-18',
    10000,
    1,
    2);  

INSERT INTO 
  lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
  VALUES 
    (NOW(),
    'Ботинки для сноуборда DC Mutiny Charocal',
    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
    'img/lot-4.jpg',
    10999,
    '2020-09-15',
    11000,
    2,
    3); 

INSERT INTO 
  lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
  VALUES 
    (NOW(),
    'Куртка для сноуборда DC Mutiny Charocal',
    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
    'img/lot-5.jpg',
    7500,
    '2020-09-19',
    8000,
    2,
    4); 

INSERT INTO 
  lot(date_start, title, description, path, cost, date_finish, rate_step, user_id, category_id) 
  VALUES 
    (NOW(),
    'Маска Oakley Canopy',
    'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
    'img/lot-6.jpg',
    5400,
    '2020-09-21',
    6000,
    2,
    6);    

-- Добавляем три ставки

INSERT INTO rate (date, cost, user_id, lot_id) VALUES (NOW(), 170000, 1, 2);
INSERT INTO rate (date, cost, user_id, lot_id) VALUES (NOW(), 30000, 2, 1);
INSERT INTO rate (date, cost, user_id, lot_id) VALUES (NOW(), 180000, 1, 2);
INSERT INTO rate (date, cost, user_id, lot_id) VALUES (NOW(), 180000, 1, 3);
INSERT INTO rate (date, cost, user_id, lot_id) VALUES (NOW(), 180000, 1, 4);
INSERT INTO rate (date, cost, user_id, lot_id) VALUES (NOW(), 180000, 2, 5);
INSERT INTO rate (date, cost, user_id, lot_id) VALUES (NOW(), 180000, 2, 6);

-- Получение всех категорий

SELECT name FROM category;

-- Получение самых новых, открытых лотов. Каждый лот включает название, стартовую цену, ссылку на изображение, текущую цену, название категории

SELECT lot.id, category.name AS category, title, path, lot.cost, MAX(rate.cost) AS current_price
	FROM lot 
		JOIN category ON lot.category_id = category.id
	 	JOIN rate ON rate.lot_id = lot.id
			WHERE date_finish > NOW()
			GROUP BY lot.id
      ORDER BY lot.date_start DESC;

-- Показ лота по его id. И название категории, к которой принадлежит лот

SELECT lot.id, lot.title, category.name FROM lot JOIN category ON lot.category_id = category.id WHERE lot.id = 2;

-- Обновил название лота по его идентификатору

UPDATE lot SET title = 'Новое название лота' WHERE id = 1;

-- Получение списка ставок для лота по его идентификатору с сортировкой по дате

SELECT rate.cost FROM lot JOIN rate ON lot.id = rate.lot_id WHERE lot.id = 2 ORDER BY rate.date ASC;
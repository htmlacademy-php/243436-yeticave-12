CREATE DATABASE yeticave;

USE yeticave;

CREATE TABLE category (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	category_name CHAR(255) NOT NULL,
	category_code CHAR(255) NOT NULL
);

INSERT INTO category (category_name, category_code) VALUES ('Доски и лыжи', 'boards'), ('Крепления', 'attachment'), ('Ботинки', 'boots'), ('Одежда', 'clothing');

CREATE TABLE lot (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date_start DATETIME,
  title CHAR(255) NOT NULL,
  descruption TEXT NOT NULL,
  path CHAR(255) NOT NULL,
  cost INT UNSIGNED,
  date_finish DATETIME,
  rate_stap INT UNSIGNED,   
  users_id INT UNSIGNED,
  category_id INT UNSIGNED	
);

CREATE TABLE rate (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_rate DATETIME,
	cost_rate INT UNSIGNED,
  users_id INT UNSIGNED,
  lot_id INT UNSIGNED
);

CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date_reg DATETIME,
  email CHAR(255) NOT NULL,
	name CHAR(255) NOT NULL,	
	password CHAR(64) NOT NULL,
	contact CHAR(255) NOT NULL,
  lot_id INT UNSIGNED,
  rate_id INT UNSIGNED
);




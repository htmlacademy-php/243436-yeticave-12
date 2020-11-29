CREATE DATABASE yeticave;
DEFAULT CHARACTER SET utf8;
DEFAULT COLLATE utf8_general_ci;  


USE yeticave;

CREATE TABLE category (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	code VARCHAR(20) NOT NULL
);

CREATE TABLE lot (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date_start DATETIME NOT NULL,
  title VARCHAR(700) NOT NULL,
  description TEXT NOT NULL,
  path VARCHAR(100) NOT NULL,
  cost INT UNSIGNED NOT NULL,
  date_finish DATETIME NOT NULL,
  rate_step INT UNSIGNED NOT NULL,   
  user_id INT UNSIGNED NOT NULL,
  winner_id INT UNSIGNED,
  category_id INT UNSIGNED NOT NULL
);

CREATE TABLE rate (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	date TIMESTAMP NOT NULL,
	cost INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  lot_id INT UNSIGNED NOT NULL
);

CREATE TABLE user (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	created_at TIMESTAMP NOT NULL,
  email VARCHAR(72) NOT NULL,
	name VARCHAR(100) NOT NULL,	
	password CHAR(64) NOT NULL,
	contact VARCHAR(255) NOT NULL
);

ALTER TABLE lot ADD FOREIGN KEY (user_id) REFERENCES user(id);
ALTER TABLE lot ADD FOREIGN KEY (winner_id) REFERENCES user(id);
ALTER TABLE lot ADD FOREIGN KEY (category_id) REFERENCES category(id);

ALTER TABLE rate ADD FOREIGN KEY (user_id) REFERENCES user(id);
ALTER TABLE rate ADD FOREIGN KEY (lot_id) REFERENCES lot(id);

CREATE FULLTEXT INDEX lot_search ON lot(title, description);
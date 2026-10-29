CREATE DATABASE IF NOT EXISTS library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library_db;
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL UNIQUE
);
CREATE TABLE IF NOT EXISTS books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  author VARCHAR(100) NOT NULL,
  category_id INT NOT NULL,
  year SMALLINT NOT NULL,
  description TEXT,
  copies TINYINT UNSIGNED NOT NULL DEFAULT 1,
  user_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  book_id INT NOT NULL,
  user_id INT NOT NULL,
  loaned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  returned_at DATETIME NULL,
  FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
INSERT IGNORE INTO categories (name) VALUES ('Роман'),('Ғылым'),('Бағдарламалау'),('Тарих'),('Поэзия');
INSERT INTO books (title, author, category_id, year, description, copies) VALUES
('Абай жолы','Мұхтар Әуезов',1,1942,'Абай Құнанбайұлының өмірін бейнелейтін эпопея.',3),
('Clean Code','Robert C. Martin',3,2008,'Таза және түсінікті код жазу туралы кітап.',2),
('Қобыланды батыр','Халық эпосы',5,1990,'Қазақ батырлық жыры.',1),
('Қысқаша уақыт тарихы','Стивен Хокинг',2,1988,'Ғалам құрылымы туралы ғылыми-көпшілік кітап.',2);

GRANT ALL PRIVILEGES ON mydb.* TO 'testuser'@'localhost';
FLUSH PRIVILEGES;

-- データベースの使用
USE mydb;

-- ユーザ情報テーブルの作成
CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);

-- 本の情報テーブルの作成
CREATE TABLE IF NOT EXISTS books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);

GRANT ALL PRIVILEGES ON yabukia.* TO 'testuser'@'localhost';
FLUSH PRIVILEGES;

-- データベースの使用
USE yabukia;

-- ユーザ情報テーブルの作成
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE books (
    book_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    title VARCHAR(255),
    author VARCHAR(255),
    publisher VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

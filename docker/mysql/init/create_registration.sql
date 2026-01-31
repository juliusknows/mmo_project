SELECT 'Шаг 1: Создание базы данных' AS Status;
CREATE DATABASE IF NOT EXISTS mmo_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

SELECT 'Шаг 2: Переключение на базу' AS Status;
USE mmo_db;

SELECT 'Шаг 3: Создание таблицы' AS Status;
CREATE TABLE IF NOT EXISTS registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(254) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL
    );

INSERT IGNORE INTO registration (name, email, password_hash)
VALUES ('Test', 'test@example.com', 'хеш_пароля_test');

SELECT 'Скрипт выполнен успешно!' AS Status;

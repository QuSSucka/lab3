-- База данных для ЛР3: пользователи и их настройки
CREATE DATABASE IF NOT EXISTS php_samoylov_lr3
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE php_samoylov_lr3;

CREATE TABLE IF NOT EXISTS users (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    login          VARCHAR(32)  NOT NULL UNIQUE,
    password_hash  VARCHAR(255) NOT NULL,
    bg_color       CHAR(7)      NOT NULL DEFAULT '#ffffff',
    text_color     CHAR(7)      NOT NULL DEFAULT '#000000',
    remember_token CHAR(64)     NULL,
    created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

<?php

namespace App;

// Все запросы к таблице users
class UserRepository
{
    public static function create(string $login, string $password,
                                  string $bg, string $text): void
    {
        $st = Database::get()->prepare(
            'INSERT INTO users (login, password_hash, bg_color, text_color)
             VALUES (?, ?, ?, ?)');
        $st->execute([$login, password_hash($password, PASSWORD_DEFAULT), $bg, $text]);
    }

    public static function findByLogin(string $login): ?array
    {
        $st = Database::get()->prepare('SELECT * FROM users WHERE login = ?');
        $st->execute([$login]);
        return $st->fetch() ?: null;
    }

    public static function findById(int $id): ?array
    {
        $st = Database::get()->prepare('SELECT * FROM users WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function updateColors(int $id, string $bg, string $text): void
    {
        $st = Database::get()->prepare(
            'UPDATE users SET bg_color = ?, text_color = ? WHERE id = ?');
        $st->execute([$bg, $text, $id]);
    }

    // В БД хранится только хеш токена автовхода
    public static function setRememberToken(int $id, ?string $tokenHash): void
    {
        $st = Database::get()->prepare(
            'UPDATE users SET remember_token = ? WHERE id = ?');
        $st->execute([$tokenHash, $id]);
    }
}

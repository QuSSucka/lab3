<?php

namespace App;

class Auth
{
    // Вход: сессия, токен автовхода и cookie с настройками
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];

        $token = bin2hex(random_bytes(32));
        UserRepository::setRememberToken($user['id'], hash('sha256', $token));
        self::setCookie('remember', $user['id'] . ':' . $token, true);

        self::sendSettings($user['bg_color'], $user['text_color']);
    }

    // Текущий пользователь; если сессии нет — автовход по cookie remember
    public static function user(): ?array
    {
        if (isset($_SESSION['user_id'])) {
            return UserRepository::findById($_SESSION['user_id']);
        }

        $cookie = $_COOKIE['remember'] ?? '';
        if (!preg_match('/^(\d+):([0-9a-f]{64})$/', $cookie, $m)) {
            return null;
        }

        $user = UserRepository::findById((int)$m[1]);
        if ($user === null || $user['remember_token'] === null
            || !hash_equals($user['remember_token'], hash('sha256', $m[2]))) {
            self::deleteCookie('remember');
            return null;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        self::sendSettings($user['bg_color'], $user['text_color']);
        return $user;
    }

    // Выход: токен в БД, сессия и все cookie удаляются
    public static function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            UserRepository::setRememberToken($_SESSION['user_id'], null);
        }
        $_SESSION = [];
        session_destroy();

        foreach (['remember', 'bg_color', 'text_color', session_name()] as $name) {
            self::deleteCookie($name);
        }
    }

    // Передача настроек на клиент в виде cookie
    public static function sendSettings(string $bg, string $text): void
    {
        self::setCookie('bg_color', $bg);
        self::setCookie('text_color', $text);
        $_COOKIE['bg_color'] = $bg;      // чтобы применить цвета
        $_COOKIE['text_color'] = $text;  // уже на этой странице
    }

    private static function setCookie(string $name, string $value,
                                      bool $httpOnly = false): void
    {
        setcookie($name, $value, [
            'expires'  => time() + COOKIE_LIFETIME,
            'path'     => '/',
            'httponly' => $httpOnly,
            'samesite' => 'Lax',
        ]);
    }

    private static function deleteCookie(string $name): void
    {
        setcookie($name, '', ['expires' => time() - 3600, 'path' => '/']);
        unset($_COOKIE[$name]);
    }
}

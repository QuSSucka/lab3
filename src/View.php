<?php

namespace App;

class View
{
    // Цвет из cookie применяется, только если это корректный #rrggbb
    public static function color(string $name, string $default): string
    {
        $value = $_COOKIE[$name] ?? '';
        return self::isColor($value) ? $value : $default;
    }

    public static function isColor(string $value): bool
    {
        return (bool)preg_match('/^#[0-9a-fA-F]{6}$/', $value);
    }

    public static function e(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }

    public static function header(string $title): void
    {
        $bg   = self::color('bg_color', '#ffffff');
        $text = self::color('text_color', '#000000');
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>{$title}</title>
            <style>
                body { background: {$bg}; color: {$text};
                       font-family: sans-serif; max-width: 420px; margin: 40px auto; }
                label { display: block; margin: 10px 0; }
                .error { color: #c00; }
            </style>
        </head>
        <body>
        <h1>{$title}</h1>
        HTML;
    }

    public static function footer(): void
    {
        echo "</body>\n</html>";
    }
}

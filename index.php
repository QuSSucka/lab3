<?php
require 'bootstrap.php';

use App\Auth;
use App\View;

// Без входа (и без cookie автовхода) — на страницу авторизации
$user = Auth::user();
if ($user === null) {
    header('Location: login.php');
    exit;
}

View::header('Главная');
?>
<p>Здравствуйте, <b><?= View::e($user['login']) ?></b>!</p>
<p>Цвет фона: <?= View::e($user['bg_color']) ?>,
   цвет шрифта: <?= View::e($user['text_color']) ?></p>
<p><a href="settings.php">Настройки</a> | <a href="logout.php">Выйти</a></p>
<?php View::footer(); ?>

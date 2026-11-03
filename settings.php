<?php
require 'bootstrap.php';

use App\Auth;
use App\UserRepository;
use App\View;

$user = Auth::user();
if ($user === null) {
    header('Location: login.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bg   = $_POST['bg_color'] ?? '';
    $text = $_POST['text_color'] ?? '';

    if (View::isColor($bg) && View::isColor($text)) {
        UserRepository::updateColors($user['id'], $bg, $text); // в БД
        Auth::sendSettings($bg, $text);                         // в cookie
        header('Location: index.php');
        exit;
    }
    $error = 'Некорректный цвет';
}

View::header('Настройки');
if ($error) {
    echo '<p class="error">' . $error . '</p>';
}
?>
<form method="post">
    <label>Цвет фона
        <input type="color" name="bg_color" value="<?= View::e($user['bg_color']) ?>"></label>
    <label>Цвет шрифта
        <input type="color" name="text_color" value="<?= View::e($user['text_color']) ?>"></label>
    <button>Сохранить</button>
</form>
<p><a href="index.php">На главную</a></p>
<?php View::footer(); ?>

<?php
require 'bootstrap.php';

use App\UserRepository;
use App\View;

$errors = [];
$login = trim($_POST['login'] ?? '');
$bg    = $_POST['bg_color'] ?? '#ffffff';
$text  = $_POST['text_color'] ?? '#000000';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_]{3,32}$/', $login)) {
        $errors[] = 'Логин: от 3 до 32 латинских букв, цифр или _';
    }
    if (mb_strlen($password) < 6) {
        $errors[] = 'Пароль должен быть не короче 6 символов';
    }
    if (!View::isColor($bg) || !View::isColor($text)) {
        $errors[] = 'Некорректный цвет';
    }
    if (!$errors && UserRepository::findByLogin($login) !== null) {
        $errors[] = 'Такой логин уже занят';
    }

    if (!$errors) {
        UserRepository::create($login, $password, $bg, $text);
        header('Location: login.php?registered=1');
        exit;
    }
}

View::header('Регистрация');
foreach ($errors as $error) {
    echo '<p class="error">' . View::e($error) . '</p>';
}
?>
<form method="post">
    <label>Логин <input name="login" value="<?= View::e($login) ?>" required></label>
    <label>Пароль <input type="password" name="password" required></label>
    <label>Цвет фона <input type="color" name="bg_color" value="<?= View::e($bg) ?>"></label>
    <label>Цвет шрифта <input type="color" name="text_color" value="<?= View::e($text) ?>"></label>
    <button>Зарегистрироваться</button>
</form>
<p><a href="login.php">Уже есть аккаунт? Войти</a></p>
<?php View::footer(); ?>

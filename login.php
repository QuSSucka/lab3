<?php
require 'bootstrap.php';

use App\Auth;
use App\UserRepository;
use App\View;

// Уже вошёл (в т.ч. автоматически по cookie) — сразу на главную
if (Auth::user() !== null) {
    header('Location: index.php');
    exit;
}

$error = '';
$login = trim($_POST['login'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = UserRepository::findByLogin($login);

    if ($user !== null && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        Auth::login($user);
        header('Location: index.php');
        exit;
    }
    $error = 'Неверный логин или пароль';
}

View::header('Авторизация');
if (isset($_GET['registered'])) {
    echo '<p>Регистрация прошла успешно, войдите.</p>';
}
if ($error) {
    echo '<p class="error">' . $error . '</p>';
}
?>
<form method="post">
    <label>Логин <input name="login" value="<?= View::e($login) ?>" required></label>
    <label>Пароль <input type="password" name="password" required></label>
    <button>Войти</button>
</form>
<p><a href="register.php">Регистрация</a></p>
<?php View::footer(); ?>

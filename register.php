<?php require_once 'config.php';
$errors = []; $name = $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? ''; $pass2 = $_POST['password2'] ?? '';
    if (mb_strlen($name) < 2 || mb_strlen($name) > 60) $errors[] = 'Аты 2-60 таңба болуы керек.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email дұрыс емес.';
    if (strlen($pass) < 6) $errors[] = 'Құпиясөз кемінде 6 таңба болуы керек.';
    if ($pass !== $pass2) $errors[] = 'Құпиясөздер сәйкес келмейді.';
    if (!$errors) {
        $st = $pdo->prepare('SELECT id FROM users WHERE email=?'); $st->execute([$email]);
        if ($st->fetch()) $errors[] = 'Бұл email тіркелген.';
    }
    if (!$errors) {
        $role = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() == 0 ? 'admin' : 'user';
        $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)')
            ->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT), $role]);
        flash('Тіркелу сәтті өтті. Енді кіріңіз.');
        header('Location: login.php'); exit;
    }
}
$title = 'Тіркелу'; require 'header.php'; ?>
<h1>Тіркелу</h1>
<?php foreach ($errors as $er): ?><div class="error"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" class="form" id="regForm" novalidate>
  <?= csrf_field() ?>
  <label>Аты-жөні <input name="name" value="<?= e($name) ?>" required minlength="2" maxlength="60"></label>
  <label>Email <input type="email" name="email" value="<?= e($email) ?>" required></label>
  <label>Құпиясөз <input type="password" name="password" required minlength="6"></label>
  <label>Құпиясөзді қайталаңыз <input type="password" name="password2" required></label>
  <button class="btn">Тіркелу</button>
</form>
<?php require 'footer.php'; ?>

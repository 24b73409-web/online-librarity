<?php require_once 'config.php';
$error = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim($_POST['email'] ?? ''); $pass = $_POST['password'] ?? '';
    $st = $pdo->prepare('SELECT * FROM users WHERE email=?'); $st->execute([$email]);
    $row = $st->fetch();
    if ($row && password_verify($pass, $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $row['id'], 'name' => $row['name'], 'role' => $row['role']];
        flash('Қош келдіңіз, '.$row['name'].'!');
        header('Location: books.php'); exit;
    }
    $error = 'Email немесе құпиясөз қате.';
}
$title = 'Кіру'; require 'header.php'; ?>
<h1>Кіру</h1>
<?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form">
  <?= csrf_field() ?>
  <label>Email <input type="email" name="email" value="<?= e($email) ?>" required></label>
  <label>Құпиясөз <input type="password" name="password" required></label>
  <button class="btn">Кіру</button>
  <p class="muted">Аккаунт жоқ па? <a href="register.php">Тіркелу</a></p>
</form>
<?php require 'footer.php'; ?>

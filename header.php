<?php require_once 'config.php'; $u = user(); $f = flash(); ?>
<!DOCTYPE html>
<html lang="kk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Онлайн кітапхана') ?></title>
<link rel="stylesheet" href="style.css">
<script src="app.js" defer></script>
</head>
<body>
<header class="top">
  <a class="brand" href="index.php">Кітапхана</a>
  <button class="burger" aria-label="Мәзір" aria-expanded="false">Мәзір</button>
  <nav id="menu">
    <a href="index.php">Басты бет</a>
    <a href="books.php">Кітаптар</a>
    <?php if ($u): ?>
      <a href="my_loans.php">Менің кітаптарым</a>
      <a href="book_form.php">Кітап қосу</a>
      <form method="post" action="logout.php" class="inline"><?= csrf_field() ?><button class="link">Шығу (<?= e($u['name']) ?>)</button></form>
    <?php else: ?>
      <a href="login.php">Кіру</a>
      <a class="btn small" href="register.php">Тіркелу</a>
    <?php endif; ?>
  </nav>
</header>
<main>
<?php if ($f): ?><div class="flash"><?= e($f) ?></div><?php endif; ?>

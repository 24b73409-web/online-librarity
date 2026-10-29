<?php require_once 'config.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: books.php'); exit; }
csrf_check();
$id = (int)($_POST['id'] ?? 0);
$st = $pdo->prepare('SELECT user_id FROM books WHERE id=?'); $st->execute([$id]); $row = $st->fetch();
if ($row && (user()['role'] === 'admin' || $row['user_id'] == user()['id'])) {
    $pdo->prepare('DELETE FROM books WHERE id=?')->execute([$id]); flash('Кітап өшірілді.');
} else flash('Рұқсат жоқ.');
header('Location: books.php');

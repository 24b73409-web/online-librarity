<?php require_once 'config.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: books.php'); exit; }
csrf_check();
$id = (int)($_POST['book_id'] ?? 0); $uid = user()['id'];
$st = $pdo->prepare('SELECT copies - (SELECT COUNT(*) FROM loans WHERE book_id=books.id AND returned_at IS NULL) AS free FROM books WHERE id=?');
$st->execute([$id]); $free = $st->fetchColumn();
$st = $pdo->prepare('SELECT id FROM loans WHERE book_id=? AND user_id=? AND returned_at IS NULL'); $st->execute([$id, $uid]);
if ($free === false) flash('Кітап табылмады.');
elseif ($st->fetch()) flash('Бұл кітап сізде бар.');
elseif ($free < 1) flash('Бос дана қалмады.');
else { $pdo->prepare('INSERT INTO loans (book_id,user_id) VALUES (?,?)')->execute([$id, $uid]); flash('Кітап қарызға алынды.'); }
header('Location: my_loans.php');

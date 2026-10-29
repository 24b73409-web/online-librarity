<?php require_once 'config.php'; require_login();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0); $errors = [];
$book = ['title'=>'','author'=>'','category_id'=>0,'year'=>date('Y'),'description'=>'','copies'=>1];
if ($id) {
    $st = $pdo->prepare('SELECT * FROM books WHERE id=?'); $st->execute([$id]); $row = $st->fetch();
    if (!$row) { http_response_code(404); die('Кітап табылмады.'); }
    if (user()['role'] !== 'admin' && $row['user_id'] != user()['id']) { http_response_code(403); die('Рұқсат жоқ.'); }
    $book = $row;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $book = ['title'=>trim($_POST['title']??''),'author'=>trim($_POST['author']??''),'category_id'=>(int)($_POST['category_id']??0),
        'year'=>(int)($_POST['year']??0),'description'=>trim($_POST['description']??''),'copies'=>(int)($_POST['copies']??0)];
    if (mb_strlen($book['title']) < 2 || mb_strlen($book['title']) > 150) $errors[] = 'Атауы 2-150 таңба болуы керек.';
    if (mb_strlen($book['author']) < 2 || mb_strlen($book['author']) > 100) $errors[] = 'Автор аты 2-100 таңба болуы керек.';
    if ($book['year'] < 1000 || $book['year'] > (int)date('Y')) $errors[] = 'Жыл дұрыс емес.';
    if ($book['copies'] < 1 || $book['copies'] > 100) $errors[] = 'Дана саны 1-100 аралығында болуы керек.';
    $st = $pdo->prepare('SELECT id FROM categories WHERE id=?'); $st->execute([$book['category_id']]);
    if (!$st->fetch()) $errors[] = 'Санатты таңдаңыз.';
    if (!$errors) {
        $v = [$book['title'],$book['author'],$book['category_id'],$book['year'],$book['description'],$book['copies']];
        if ($id) { $pdo->prepare('UPDATE books SET title=?,author=?,category_id=?,year=?,description=?,copies=? WHERE id=?')->execute([...$v, $id]); flash('Кітап жаңартылды.'); }
        else { $pdo->prepare('INSERT INTO books (title,author,category_id,year,description,copies,user_id) VALUES (?,?,?,?,?,?,?)')->execute([...$v, user()['id']]); flash('Кітап қосылды.'); }
        header('Location: books.php'); exit;
    }
}
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$title = $id ? 'Кітапты өзгерту' : 'Кітап қосу'; require 'header.php'; ?>
<h1><?= e($title) ?></h1>
<?php foreach ($errors as $er): ?><div class="error"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" class="form">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
  <label>Атауы <input name="title" value="<?= e($book['title']) ?>" required maxlength="150"></label>
  <label>Автор <input name="author" value="<?= e($book['author']) ?>" required maxlength="100"></label>
  <label>Санат
    <select name="category_id" required><option value="">Таңдаңыз</option>
    <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $book['category_id'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
    </select></label>
  <label>Шыққан жылы <input type="number" name="year" value="<?= e($book['year']) ?>" min="1000" max="<?= date('Y') ?>" required></label>
  <label>Дана саны <input type="number" name="copies" value="<?= e($book['copies']) ?>" min="1" max="100" required></label>
  <label>Сипаттама <textarea name="description" rows="4"><?= e($book['description']) ?></textarea></label>
  <button class="btn">Сақтау</button>
</form>
<?php require 'footer.php'; ?>

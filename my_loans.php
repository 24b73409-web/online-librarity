<?php require_once 'config.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $pdo->prepare('UPDATE loans SET returned_at=NOW() WHERE id=? AND user_id=? AND returned_at IS NULL')->execute([(int)$_POST['loan_id'], user()['id']]);
    flash('Кітап қайтарылды.'); header('Location: my_loans.php'); exit;
}
$st = $pdo->prepare('SELECT l.*, b.title, b.author FROM loans l JOIN books b ON b.id=l.book_id WHERE l.user_id=? ORDER BY l.id DESC');
$st->execute([user()['id']]); $loans = $st->fetchAll();
$title = 'Менің кітаптарым'; require 'header.php'; ?>
<h1>Менің кітаптарым</h1>
<?php if (!$loans): ?><p class="empty">Әзірге қарызға алған кітабыңыз жоқ. <a href="books.php">Кітаптарды қарау</a></p><?php else: ?>
<div class="table-wrap"><table>
<thead><tr><th>Кітап</th><th>Алынған күні</th><th>Қайтарылған күні</th><th></th></tr></thead>
<?php foreach ($loans as $l): ?>
<tr><td><?= e($l['title']) ?><br><span class="muted"><?= e($l['author']) ?></span></td><td><?= e($l['loaned_at']) ?></td><td><?= e($l['returned_at'] ?? '—') ?></td>
<td><?php if (!$l['returned_at']): ?><form method="post"><?= csrf_field() ?><input type="hidden" name="loan_id" value="<?= $l['id'] ?>"><button class="btn small">Қайтару</button></form><?php endif; ?></td></tr>
<?php endforeach; ?>
</table></div>
<?php endif; require 'footer.php'; ?>

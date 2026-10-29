<?php $title = 'Басты бет'; require 'header.php';
$stats = $pdo->query("SELECT (SELECT COUNT(*) FROM books) b, (SELECT COUNT(*) FROM users) u, (SELECT COUNT(*) FROM loans WHERE returned_at IS NULL) l")->fetch();
$latest = $pdo->query("SELECT b.*, c.name cat FROM books b JOIN categories c ON c.id=b.category_id ORDER BY b.id DESC LIMIT 3")->fetchAll();
?>
<section class="hero">
  <h1>Қолыңыздағы кітапхана</h1>
  <p>Кітапты іздеңіз, қарызға алыңыз, өз кітабыңызды қосыңыз.</p>
  <form action="books.php" method="get" class="searchbar">
    <input type="search" name="q" placeholder="Кітап атауы немесе автор" aria-label="Іздеу">
    <button class="btn">Іздеу</button>
  </form>
</section>
<section class="stats">
  <div><strong><?= (int)$stats['b'] ?></strong> кітап</div>
  <div><strong><?= (int)$stats['u'] ?></strong> оқырман</div>
  <div><strong><?= (int)$stats['l'] ?></strong> қазір қолда</div>
</section>
<h2>Соңғы қосылған кітаптар</h2>
<div class="grid">
<?php foreach ($latest as $b): ?>
  <article class="card">
    <h3><?= e($b['title']) ?></h3>
    <p class="muted"><?= e($b['author']) ?>, <?= (int)$b['year'] ?></p>
    <p><?= e($b['cat']) ?></p>
  </article>
<?php endforeach; ?>
</div>
<?php require 'footer.php'; ?>

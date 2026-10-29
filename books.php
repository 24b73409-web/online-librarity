<?php $title = 'Кітаптар'; require 'header.php';
$q = trim($_GET['q'] ?? ''); $cat = (int)($_GET['cat'] ?? 0);
$page = max(1, (int)($_GET['page'] ?? 1)); $per = 6;
$where = []; $p = [];
if ($q !== '') { $where[] = '(b.title LIKE ? OR b.author LIKE ?)'; $p[] = "%$q%"; $p[] = "%$q%"; }
if ($cat) { $where[] = 'b.category_id = ?'; $p[] = $cat; }
$w = $where ? 'WHERE '.implode(' AND ', $where) : '';
$st = $pdo->prepare("SELECT COUNT(*) FROM books b $w"); $st->execute($p);
$total = (int)$st->fetchColumn(); $pages = max(1, (int)ceil($total / $per)); $page = min($page, $pages);
$off = ($page - 1) * $per;
$st = $pdo->prepare("SELECT b.*, c.name cat,
  b.copies - (SELECT COUNT(*) FROM loans l WHERE l.book_id=b.id AND l.returned_at IS NULL) AS free
  FROM books b JOIN categories c ON c.id=b.category_id $w ORDER BY b.id DESC LIMIT $per OFFSET $off");
$st->execute($p); $books = $st->fetchAll();
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$qs = http_build_query(['q' => $q, 'cat' => $cat ?: '']);
?>
<h1>Кітаптар</h1>
<form method="get" class="filters">
  <input type="search" name="q" value="<?= e($q) ?>" placeholder="Атауы немесе автор" aria-label="Іздеу">
  <select name="cat" aria-label="Санат">
    <option value="0">Барлық санаттар</option>
    <?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
  </select>
  <button class="btn">Іздеу</button>
</form>
<?php if (!$books): ?><p class="empty">Ештеңе табылмады. Іздеу сөзін өзгертіп көріңіз.</p><?php endif; ?>
<div class="grid">
<?php foreach ($books as $b): $me = user(); $owner = $me && ($me['role'] === 'admin' || $me['id'] == $b['user_id']); ?>
  <article class="card">
    <h3><?= e($b['title']) ?></h3>
    <p class="muted"><?= e($b['author']) ?>, <?= (int)$b['year'] ?> · <?= e($b['cat']) ?></p>
    <p><?= e(mb_strimwidth($b['description'] ?? '', 0, 110, '…')) ?></p>
    <p class="<?= $b['free'] > 0 ? 'ok' : 'no' ?>"><?= $b['free'] > 0 ? 'Бос: '.(int)$b['free'] : 'Қазір қолда жоқ' ?></p>
    <div class="actions">
      <?php if ($me && $b['free'] > 0): ?>
        <form method="post" action="borrow.php"><?= csrf_field() ?><input type="hidden" name="book_id" value="<?= $b['id'] ?>"><button class="btn small">Қарызға алу</button></form>
      <?php endif; ?>
      <?php if ($owner): ?>
        <a class="btn small ghost" href="book_form.php?id=<?= $b['id'] ?>">Өзгерту</a>
        <form method="post" action="book_delete.php" data-confirm="Кітап өшірілсін бе?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $b['id'] ?>"><button class="btn small danger">Өшіру</button></form>
      <?php endif; ?>
    </div>
  </article>
<?php endforeach; ?>
</div>
<?php if ($pages > 1): ?>
<nav class="pager" aria-label="Беттер">
  <?php for ($i = 1; $i <= $pages; $i++): ?><a class="<?= $i == $page ? 'cur' : '' ?>" href="?<?= $qs ?>&page=<?= $i ?>"><?= $i ?></a><?php endfor; ?>
</nav>
<?php endif; ?>
<?php require 'footer.php'; ?>

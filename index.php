<?php require_once __DIR__."/config/db.php"; require_once __DIR__."/includes/header.php"; ?>
<section class="hero">
  <h1>Swap Skills. Learn & Teach.</h1>
  <p>SkillSwap connects people to exchange skills without money.</p>
  <form action="skills.php" method="get" class="searchbar">
    <input name="q" placeholder="Search skills or category..." value="<?= esc($_GET['q'] ?? '') ?>">
    <select name="type">
      <option value="">All</option>
      <option value="Teach">Teach</option>
      <option value="Learn">Learn</option>
    </select>
    <button>Search</button>
  </form>
</section>

<h3>Featured Skills</h3>
<div class="grid">
<?php
$skills = $pdo->query("SELECT s.*, u.name as user_name 
                       FROM skills s JOIN users u ON u.id=s.user_id
                       ORDER BY s.created_at DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
foreach($skills as $sk): ?>
  <div class="card">
    <h4><?= esc($sk['skill_name']) ?> <small>(<?= esc($sk['skill_type']) ?>)</small></h4>
    <p class="muted"><?= esc($sk['category']) ?></p>
    <p><?= esc(mb_strimwidth($sk['description'],0,120,'…')) ?></p>
    <p class="muted">by <?= esc($sk['user_name']) ?></p>
    <a class="btn" href="skills.php?id=<?= (int)$sk['id'] ?>">View</a>
  </div>
<?php endforeach; ?>
</div>
<?php require_once __DIR__."/includes/footer.php"; ?>

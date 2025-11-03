<?php require_once __DIR__."/config/db.php"; require_once __DIR__."/includes/header.php"; ?>
<h2>Browse Skills</h2>
<form action="" method="get" class="searchbar">
  <input name="q" placeholder="keyword..." value="<?= esc($_GET['q'] ?? '') ?>">
  <input name="cat" placeholder="category..." value="<?= esc($_GET['cat'] ?? '') ?>">
  <select name="type">
    <option value="">All</option>
    <option value="Teach" <?= (($_GET['type'] ?? '')==='Teach')?'selected':''; ?>>Teach</option>
    <option value="Learn" <?= (($_GET['type'] ?? '')==='Learn')?'selected':''; ?>>Learn</option>
  </select>
  <button>Filter</button>
</form>
<?php
// single skill view
if (isset($_GET['id'])) {
  $stmt = $pdo->prepare("SELECT s.*, u.name as user_name, u.id as owner_id 
                         FROM skills s JOIN users u ON u.id=s.user_id WHERE s.id=?");
  $stmt->execute([(int)$_GET['id']]);
  if ($sk = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <div class="card">
      <h3><?= esc($sk['skill_name']) ?> <small>(<?= esc($sk['skill_type']) ?>)</small></h3>
      <p class="muted">Category: <?= esc($sk['category']) ?> | by <?= esc($sk['user_name']) ?></p>
      <p><?= nl2br(esc($sk['description'])) ?></p>
      <?php if (!empty($_SESSION['user'])): ?>
        <?php if ((int)$sk['owner_id'] !== (int)$_SESSION['user']['id']): ?>
          <form method="post" action="request_exchange.php">
            <input type="hidden" name="skill_id" value="<?= (int)$sk['id'] ?>">
            <button class="btn">Send Exchange Request</button>
          </form>
        <?php else: ?>
          <p class="muted">This is your own listing.</p>
        <?php endif; ?>
      <?php else: ?>
        <a class="btn" href="login.php?next=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Login to request</a>
      <?php endif; ?>
    </div>
    <?php
  } else echo "<p>No skill found.</p>";
  require_once __DIR__."/includes/footer.php"; exit;
}

// list view
$q = "%".($_GET['q'] ?? "")."%";
$cat = "%".($_GET['cat'] ?? "")."%";
$type = $_GET['type'] ?? '';
$sql = "SELECT s.*, u.name as user_name FROM skills s JOIN users u ON u.id=s.user_id 
        WHERE (s.skill_name LIKE ? OR s.description LIKE ?) AND s.category LIKE ?";
$params = [$q,$q,$cat];
if ($type==='Teach' || $type==='Learn'){ $sql.=" AND s.skill_type=?"; $params[]=$type; }
$sql.=" ORDER BY s.created_at DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<div class='grid'>";
foreach($rows as $sk){
  echo "<div class='card'>
          <h4>".esc($sk['skill_name'])." <small>(".$sk['skill_type'].")</small></h4>
          <p class='muted'>".esc($sk['category'])." · by ".esc($sk['user_name'])."</p>
          <p>".esc(mb_strimwidth($sk['description'],0,110,'…'))."</p>
          <a class='btn' href='skills.php?id=".$sk['id']."'>View</a>
        </div>";
}
echo "</div>";

require_once __DIR__."/includes/footer.php";
?>

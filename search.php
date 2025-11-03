<?php
require_once __DIR__."/config/db.php";
require_once __DIR__."/includes/header.php";
$search = trim($_GET['search'] ?? '');
?>
<h2>Search</h2>
<form method="GET" action="search.php" class="searchbar">
  <input type="text" name="search" placeholder="Search skills..." value="<?= esc($search) ?>">
  <button type="submit">Search</button>
</form>
<?php
if ($search !== ''){
  $term = "%".$search."%";
  $stmt = $pdo->prepare("SELECT id, user_id, skill_name, skill_type, category, description, created_at FROM skills
                         WHERE skill_name LIKE ? OR skill_type LIKE ? OR category LIKE ? OR description LIKE ?
                         ORDER BY created_at DESC");
  $stmt->execute([$term,$term,$term,$term]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if ($rows){
    echo "<div class='grid'>";
    foreach($rows as $r){
      echo "<div class='card'>";
      echo "<h4>".esc($r['skill_name'])." <small>(".$r['skill_type'].")</small></h4>";
      echo "<p class='muted'>".esc($r['category'])."</p>";
      echo "<p>".esc(mb_strimwidth($r['description'],0,120,'…'))."</p>";
      echo "<a class='btn' href='skills.php?id=".$r['id']."'>View</a>";
      echo "</div>";
    }
    echo "</div>";
  } else {
    echo "<p>No skills found.</p>";
  }
}
require_once __DIR__."/includes/footer.php";
?>

<?php
require_once __DIR__."/includes/auth.php"; require_login();
require_once __DIR__."/config/db.php"; require_once __DIR__."/includes/header.php";
$uid = current_user_id();
?>
<h2>My Dashboard</h2>

<h3>My Skill Listings</h3>
<div class="grid">
<?php
$my = $pdo->prepare("SELECT * FROM skills WHERE user_id=? ORDER BY created_at DESC");
$my->execute([$uid]);
foreach($my->fetchAll(PDO::FETCH_ASSOC) as $sk){
  echo "<div class='card'><h4>".esc($sk['skill_name'])." <small>(".$sk['skill_type'].")</small></h4>
        <p class='muted'>".esc($sk['category'])."</p>
        <p>".esc(mb_strimwidth($sk['description'],0,120,'…'))."</p>
        <a class='btn' href='skills.php?id=".$sk['id']."'>View</a></div>";
}
?>
</div>

<h3>Requests I Received</h3>
<table class="table">
<tr><th>Skill</th><th>From</th><th>Status</th><th>Actions</th></tr>
<?php
$sql="SELECT e.*, s.skill_name, u1.name as requester_name
      FROM exchanges e 
      JOIN skills s ON s.id=e.skill_id
      JOIN users u1 ON u1.id=e.requester_id
      WHERE e.receiver_id=? ORDER BY e.created_at DESC";
$stmt=$pdo->prepare($sql); $stmt->execute([$uid]);
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $r){
  echo "<tr>
    <td>".esc($r['skill_name'])."</td>
    <td>".esc($r['requester_name'])."</td>
    <td>".esc($r['status'])."</td>
    <td>";
  if ($r['status']==='Pending'){
    echo "<form method='post' action='exchange_action.php' style='display:inline'>
            <input type='hidden' name='id' value='".$r['id']."'>
            <button name='action' value='accept'>Accept</button>
          </form>
          <form method='post' action='exchange_action.php' style='display:inline'>
            <input type='hidden' name='id' value='".$r['id']."'>
            <button name='action' value='reject'>Reject</button>
          </form>";
  } else echo "-";
  echo "</td></tr>";
}
?>
</table>

<h3>Requests I Sent</h3>
<table class="table">
<tr><th>Skill</th><th>To</th><th>Status</th><th>Action</th></tr>
<?php
$sql="SELECT e.*, s.skill_name, u2.name as receiver_name
      FROM exchanges e 
      JOIN skills s ON s.id=e.skill_id
      JOIN users u2 ON u2.id=e.receiver_id
      WHERE e.requester_id=? ORDER BY e.created_at DESC";
$stmt=$pdo->prepare($sql); $stmt->execute([$uid]);
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $r){
  echo "<tr>
    <td>".esc($r['skill_name'])."</td>
    <td>".esc($r['receiver_name'])."</td>
    <td>".esc($r['status'])."</td>
    <td>";
  if ($r['status']==='Pending'){
    echo "<form method='post' action='exchange_action.php'>
            <input type='hidden' name='id' value='".$r['id']."'>
            <button name='action' value='cancel'>Cancel</button>
          </form>";
  } else echo "-";
  echo "</td></tr>";
}
?>
</table>
<?php require_once __DIR__."/includes/footer.php"; ?>

<?php require_once __DIR__."/includes/auth.php"; require_login();
require_once __DIR__."/config/db.php"; require_once __DIR__."/includes/header.php"; ?>
<h2>Add a Skill</h2>
<?php
$errors=[];
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['skill_name'] ?? '');
  $type = $_POST['skill_type'] ?? '';
  $cat  = trim($_POST['category'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  if ($name==='') $errors[]="Skill name required";
  if (!in_array($type,['Teach','Learn'],true)) $errors[]="Select Teach/Learn";
  if (!$errors){
    $stmt=$pdo->prepare("INSERT INTO skills(user_id,skill_name,skill_type,category,description)
                         VALUES (?,?,?,?,?)");
    $stmt->execute([current_user_id(),$name,$type,$cat,$desc]);
    echo "<p class='ok'>Skill added. <a href='skills.php'>Browse</a></p>";
  }
}
if ($errors) echo "<div class='error'>".implode("<br>", array_map('esc',$errors))."</div>";
?>
<form method="post" class="card">
  <label>Skill Name<input name="skill_name" required></label>
  <label>Type
    <select name="skill_type" required>
      <option value="">-- Select --</option>
      <option value="Teach">Teach</option>
      <option value="Learn">Learn</option>
    </select>
  </label>
  <label>Category (e.g., Programming, Music)<input name="category"></label>
  <label>Description<textarea name="description" rows="5"></textarea></label>
  <button type="submit">Save</button>
</form>
<?php require_once __DIR__."/includes/footer.php"; ?>

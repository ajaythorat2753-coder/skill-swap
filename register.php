<?php require_once __DIR__."/config/db.php"; require_once __DIR__."/includes/header.php"; ?>
<h2>Create Account</h2>
<?php
$errors = [];
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if ($name==='') $errors[]="Name required";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[]="Valid email required";
  if (strlen($pass)<6) $errors[]="Password min 6 chars";
  if (!$errors){
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) $errors[]="Email already registered";
    else{
      $hash = password_hash($pass, PASSWORD_BCRYPT);
      $pdo->prepare("INSERT INTO users(name,email,password_hash) VALUES (?,?,?)")
          ->execute([$name,$email,$hash]);
      echo "<p class='ok'>Registered! <a href='login.php'>Login</a></p>";
    }
  }
}
if ($errors){ echo "<div class='error'>".implode("<br>", array_map('esc',$errors))."</div>"; }
?>
<form method="post" class="card">
  <label>Name<input name="name" required value="<?= esc($_POST['name'] ?? '') ?>"></label>
  <label>Email<input type="email" name="email" required value="<?= esc($_POST['email'] ?? '') ?>"></label>
  <label>Password<input type="password" name="password" required></label>
  <button type="submit">Register</button>
</form>
<?php require_once __DIR__."/includes/footer.php"; ?>

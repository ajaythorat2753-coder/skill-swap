<?php require_once __DIR__."/config/db.php"; require_once __DIR__."/includes/header.php"; ?>
<h2>Login</h2>
<?php
$err = "";
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $stmt = $pdo->prepare("SELECT id,name,email,password_hash,role FROM users WHERE email=?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($user && password_verify($pass, $user['password_hash'])){
    $_SESSION['user'] = $user;
    $next = $_GET['next'] ?? 'index.php';
    header("Location: $next"); exit;
  } else $err = "Invalid credentials";
}
if ($err) echo "<div class='error'>".esc($err)."</div>";
?>
<form method="post" class="card">
  <label>Email<input type="email" name="email" required></label>
  <label>Password<input type="password" name="password" required></label>
  <button type="submit">Login</button>
</form>
<?php require_once __DIR__."/includes/footer.php"; ?>

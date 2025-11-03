<?php
require_once __DIR__."/includes/auth.php"; require_login();
require_once __DIR__."/config/db.php";

$skill_id = (int)($_POST['skill_id'] ?? 0);
if ($skill_id<=0) die("Invalid");

$stmt = $pdo->prepare("SELECT user_id FROM skills WHERE id=?");
$stmt->execute([$skill_id]);
$skill = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$skill) die("Skill not found");

$requester = current_user_id();
$receiver  = (int)$skill['user_id'];
if ($requester === $receiver) die("You cannot request your own skill.");
// avoid duplicate pending
$chk = $pdo->prepare("SELECT id FROM exchanges WHERE requester_id=? AND receiver_id=? AND skill_id=? AND status='Pending'");
$chk->execute([$requester,$receiver,$skill_id]);
if ($chk->fetch()) {
  header("Location: dashboard.php?msg=Already%20requested"); exit;
}
$pdo->prepare("INSERT INTO exchanges(requester_id,receiver_id,skill_id) VALUES (?,?,?)")
    ->execute([$requester,$receiver,$skill_id]);
header("Location: dashboard.php?msg=Request%20sent");

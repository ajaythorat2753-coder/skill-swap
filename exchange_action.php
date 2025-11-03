<?php
require_once __DIR__."/includes/auth.php"; require_login();
require_once __DIR__."/config/db.php";

$id = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';
$uid = current_user_id();

$stmt = $pdo->prepare("SELECT * FROM exchanges WHERE id=?");
$stmt->execute([$id]);
$ex = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$ex) die("Invalid request");

if ($action==='accept' || $action==='reject'){
  if ($ex['receiver_id'] != $uid) die("Not allowed");
  $status = ($action==='accept')?'Accepted':'Rejected';
  $pdo->prepare("UPDATE exchanges SET status=? WHERE id=?")->execute([$status,$id]);
} elseif ($action==='cancel'){
  if ($ex['requester_id'] != $uid) die("Not allowed");
  $pdo->prepare("UPDATE exchanges SET status='Cancelled' WHERE id=?")->execute([$id]);
}
header("Location: dashboard.php");

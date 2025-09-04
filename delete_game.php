
<?php require 'auth.php'; require_once '../includes/db.php';
if(!isset($_GET['id'])){ header('Location: dashboard.php'); exit; }
$id = (int)$_GET['id'];
$stmt = $db->prepare("DELETE FROM games WHERE id=?");
$stmt->execute([$id]);
header('Location: dashboard.php');
exit;

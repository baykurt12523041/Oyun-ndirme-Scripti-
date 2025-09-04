
<?php
// includes/db.php
$host = "sql201.infinityfree.com";
$user = "if0_39684345";
$pass = "jVcA6fs4mCVrn";
$dbname = "if0_39684345_denemecik";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}
?>

<?php
session_start();
require_once("../includes/db.php");
require_once("auth.php");

// Ayarlar tablosundan verileri çek
$stmt = $db->prepare("SELECT * FROM settings WHERE id = 1 LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Eğer kayıt yoksa oluştur
if (!$settings) {
    $stmt = $db->prepare("INSERT INTO settings (id, site_name, logo_url, navbar_links) VALUES (1, 'Oyun İndir', 'assets/logo.png', '[]')");
    $stmt->execute();
    $settings = [
        'site_name' => 'Oyun İndir',
        'logo_url' => 'assets/logo.png',
        'navbar_links' => '[]'
    ];
}

// Form gönderildiğinde güncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name    = $_POST['site_name'] ?? 'Oyun İndir';
    $logo_url     = $_POST['logo_url'] ?? 'assets/logo.png';
    $navbar_links = $_POST['navbar_links'] ?? '[]';

    $sql = "UPDATE settings 
            SET site_name = :site_name, logo_url = :logo_url, navbar_links = :navbar_links 
            WHERE id = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':site_name'    => $site_name,
        ':logo_url'     => $logo_url,
        ':navbar_links' => $navbar_links
    ]);

    // Güncel verileri yeniden çek
    $settings = [
        'site_name' => $site_name,
        'logo_url' => $logo_url,
        'navbar_links' => $navbar_links
    ];

    $success = "Ayarlar başarıyla güncellendi!";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <?php 
    
    $favicon   = '../assets/favicon.ico'; // favicon dosya yolu
  ?>
  <title>Ayarlar - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="<?= $favicon ?>">
  <style>
    .form-text{
        color:#fff !important;
    }
  </style>
</head>
<body class="bg-dark text-light">
  <div class="container py-5">
    <h2 class="mb-4">Site Ayarları</h2>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Site İsmi</label>
        <input type="text" name="site_name" class="form-control" 
               value="<?= htmlspecialchars($settings['site_name']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Logo URL</label>
        <input type="text" name="logo_url" class="form-control" 
               value="<?= htmlspecialchars($settings['logo_url']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Navbar Linkleri (JSON format)</label>
        <textarea name="navbar_links" class="form-control" rows="5"><?= htmlspecialchars($settings['navbar_links']) ?></textarea>
        <div class="form-text">Örnek: [{"name":"Ana Sayfa","url":"index.php"},{"name":"Oyunlar","url":"#"}] klasörlere gitmek için ../sayfalar deyin ve bağlantızı yapibilirsiniz örneğin: admin/logout.php</div>
      </div>

      <button type="submit" class="btn btn-primary">Kaydet</button>
      <a href="dashboard.php" class="btn btn-secondary">Geri Dön</a>
    </form>
  </div>
</body>
</html>

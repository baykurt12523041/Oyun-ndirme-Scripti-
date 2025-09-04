
<?php require 'auth.php'; require_once '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
    
    $favicon   = '../assets/favicon.ico'; // favicon dosya yolu
  ?>
  <title>Yönetim Paneli</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="icon" type="image/x-icon" href="<?= $favicon ?>">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Yönetim Paneli</a>
    <div>
      <a href="../index.php" class="btn btn-sm btn-secondary">Siteyi Gör</a>
      <a href="settings.php" class="btn btn-sm btn-secondary">Ayarlar</a>
      <a href="logout.php" class="btn btn-sm btn-danger">Çıkış</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Oyunlar</h1>
    <a href="add_game.php" class="btn btn-accent">+ Oyun Ekle</a>
  </div>

  <div class="card card-soft p-3">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle">
        <thead>
          <tr><th>ID</th><th>Kapak</th><th>Başlık</th><th>Boyut</th><th>Oluşturma</th><th class="text-end">İşlemler</th></tr>
        </thead>
        <tbody>
          <?php foreach($db->query("SELECT * FROM games ORDER BY id DESC") as $g): ?>
            <tr>
              <td><?= (int)$g['id'] ?></td>
              <td style="width:90px"><img src="<?= htmlspecialchars($g['image_url']) ?>" style="height:60px; width:100%; object-fit:cover; border-radius:10px"></td>
              <td><?= htmlspecialchars($g['title']) ?></td>
              <td><?= htmlspecialchars($g['size'] ?: '-') ?></td>
              <td><?= htmlspecialchars($g['created_at']) ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-secondary" href="../post.php?id=<?= (int)$g['id'] ?>" target="_blank">Görüntüle</a>
                <a class="btn btn-sm btn-warning" href="edit_game.php?id=<?= (int)$g['id'] ?>">Düzenle</a>
                <a class="btn btn-sm btn-danger" href="delete_game.php?id=<?= (int)$g['id'] ?>" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>

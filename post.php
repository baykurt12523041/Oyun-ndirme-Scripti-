<?php 
include 'includes/db.php'; 
if(!isset($_GET['id'])){ header("Location: index.php"); exit; }
$id = (int)$_GET['id'];
$stmt = $db->prepare("SELECT * FROM games WHERE id=?");
$stmt->execute([$id]);
$game = $stmt->fetch();
if(!$game){ header("Location: index.php"); exit; }
$settings = $db->query("SELECT * FROM settings WHERE id=1")->fetch();
$links = json_decode($game['download_links'] ?? '[]', true) ?: [];
$screens = array_filter(array_map('trim', explode(',', $game['screenshots'] ?? '')));
?>
<!DOCTYPE html>
<html lang="tr">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
    
    $favicon   = 'assets/favicon.ico'; // favicon dosya yolu
  ?>
  <title><?= htmlspecialchars($game['title']) ?> - İndir</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="icon" type="image/x-icon" href="<?= $favicon ?>">
  <style>
    body {
      background-color: #0f1724; /* koyu arka plan */
      color: #ffffff; /* yazılar beyaz */
    }
    .card-soft {
      background: rgba(255,255,255,0.05);
      border: none;
      color: #fff; /* kart içi yazılar beyaz */
    }
    .card-soft h1, 
    .card-soft h2, 
    .card-soft h4,
    .card-soft p, 
    .card-soft div {
      color: #ffffff !important; /* yazıları zorla beyaz yap */
    }
    .btn-accent {
      background-color: #7c5cff;
      border: none;
      color: #fff;
      font-weight: 500;
      transition: 0.2s ease-in-out;
    }
    .btn-accent:hover {
      background-color: #5b3dff;
      transform: scale(1.03);
    }
    .gallery img {
      width: 100%;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm" style="background: rgba(0,0,0,0.6);">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
        <img src="<?= htmlspecialchars($settings['logo_url'] ?? 'assets/logo.png') ?>" alt="Logo" height="36" class="rounded-3">
        <span><?= htmlspecialchars($settings['site_name'] ?? 'Oyun İndir') ?></span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php
          $navlinks = json_decode($settings['navbar_links'] ?? '[]', true) ?: [];
          foreach($navlinks as $link){
            $name = htmlspecialchars($link['name']);
            $url = htmlspecialchars($link['url']);
            echo "<li class='nav-item'><a class='nav-link' href='$url'>$name</a></li>";
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container detail-hero pt-5 mt-5">
    <div class="row g-4 align-items-start">
      <div class="col-12 col-lg-5">
        <div class="card card-soft p-3">
          <img src="<?= htmlspecialchars($game['image_url']) ?>" class="w-100 rounded-3 mb-3" alt="<?= htmlspecialchars($game['title']) ?>">
          <h1 class="h4 mb-1"><?= htmlspecialchars($game['title']) ?></h1>
          <div class="text-muted mb-3">Boyut: <?= htmlspecialchars($game['size'] ?: '-') ?></div>
          <p style="white-space:pre-wrap"><?= nl2br(htmlspecialchars($game['description'])) ?></p>
        </div>
      </div>
      <div class="col-12 col-lg-7">
        <div class="card card-soft p-3 mb-4">
          <h2 class="h5 mb-3">İndirme Bağlantıları</h2>
          <div class="d-grid gap-2">
            <?php if($links): foreach($links as $i=>$lnk):
              $name = htmlspecialchars($lnk['name'] ?? ('Link '.($i+1)));
              $url = htmlspecialchars($lnk['url'] ?? '#');
            ?>
              <a class="btn btn-accent btn-lg" href="<?= $url ?>" target="_blank" rel="noopener"><?= $name ?></a>
            <?php endforeach; else: ?>
              <div class="alert alert-warning">Henüz indirme bağlantısı eklenmemiş.</div>
            <?php endif; ?>
          </div>
        </div>

        <div class="card card-soft p-3">
          <h2 class="h5 mb-3">Oyun İçi Görseller</h2>
          <div class="row g-3 gallery">
            <?php if($screens): foreach($screens as $s): ?>
              <div class="col-6 col-md-4"><img src="<?= htmlspecialchars($s) ?>" alt="Ekran görüntüsü"></div>
            <?php endforeach; else: ?>
              <div class="col-12"><div class="alert alert-secondary">Görsel eklenmemiş.</div></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

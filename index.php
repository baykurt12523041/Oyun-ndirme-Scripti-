
<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
    
    $favicon   = 'assets/favicon.ico'; // favicon dosya yolu
  ?>
  <title>Oyun İndir</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="icon" type="image/x-icon" href="<?= $favicon ?>">
  <style>
    .game-card h5 {
  color: #fff !important;
}
  </style>
</head>
<body>
  <?php $settings = $db->query("SELECT * FROM settings WHERE id=1")->fetch(); ?>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
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
          $links = json_decode($settings['navbar_links'] ?? '[]', true) ?: [];
          foreach($links as $link){
            $name = htmlspecialchars($link['name']);
            $url = htmlspecialchars($link['url']);
            echo "<li class='nav-item'><a class='nav-link' href='$url'>$name</a></li>";
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="search-wrap pt-5 mt-5 text-center">
    <div class="container">
      <form method="get" action="index.php" class="d-flex justify-content-center">
        <input type="text" name="q" class="form-control search-input w-100" style="max-width:720px" placeholder="Oyun ara..."
               value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
      </form>
    </div>
  </div>

  <div class="container py-4">
    <div class="row g-4 justify-content-center">
      <?php
      if(isset($_GET['q']) && $_GET['q']!==''){
          $stmt = $db->prepare("SELECT * FROM games WHERE title LIKE ? ORDER BY id DESC");
          $stmt->execute(['%'.$_GET['q'].'%']);
      } else {
          $stmt = $db->query("SELECT * FROM games ORDER BY id DESC");
      }
      foreach($stmt as $game):
      ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card game-card h-100">
          <img src="<?= htmlspecialchars($game['image_url']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
          <div class="card-body text-center">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge-soft"><?= htmlspecialchars($game['size'] ?: 'Boyut: -') ?></span>
            </div>
            <h5 title="<?= htmlspecialchars($game['title']) ?>"><?= htmlspecialchars($game['title']) ?></h5>
          </div>
          <div class="overlay">
            <a href="post.php?id=<?= (int)$game['id'] ?>" class="btn btn-accent">İndir</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <footer class="text-center py-4">
    <div class="container">
      <small>© <?= date('Y') ?> <?= htmlspecialchars($settings['site_name'] ?? 'Oyun İndir') ?></small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

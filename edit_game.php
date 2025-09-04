<?php 
require 'auth.php'; 
require_once '../includes/db.php';

if(!isset($_GET['id'])){ 
    header('Location: dashboard.php'); 
    exit; 
}
$id = (int)$_GET['id'];
$game = $db->prepare("SELECT * FROM games WHERE id=?");
$game->execute([$id]);
$g = $game->fetch();
if(!$g){ 
    header('Location: dashboard.php'); 
    exit; 
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $size = $_POST['size'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $screenshots = $_POST['screenshots'] ?? '';
    $download_names = $_POST['dl_name'] ?? [];
    $download_urls = $_POST['dl_url'] ?? [];
    $links = [];
    for($i=0; $i<count($download_urls); $i++){
        $name = trim($download_names[$i] ?? '');
        $url  = trim($download_urls[$i] ?? '');
        if($url !== ''){ 
            $links[] = ['name' => $name ?: 'İndir', 'url' => $url]; 
        }
    }
    $stmt = $db->prepare("UPDATE games SET title=?, description=?, size=?, image_url=?, screenshots=?, download_links=? WHERE id=?");
    $stmt->execute([$title, $description, $size, $image_url, $screenshots, json_encode($links, JSON_UNESCAPED_UNICODE), $id]);
    header("Location: dashboard.php");
    exit;
}

$dl = json_decode($g['download_links'] ?? '[]', true) ?: [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
    
    $favicon   = '../assets/favicon.ico'; // favicon dosya yolu
  ?>
  <title>Oyun Düzenle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="icon" type="image/x-icon" href="<?= $favicon ?>">
  <style>
     
  </style>
</head>
<body>
<div class="container py-4">
  <h1 class="h4 mb-3">Oyun Düzenle</h1>
  <form method="post" class="card card-soft p-3">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Başlık</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($g['title']) ?>" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Boyut</label>
        <input type="text" name="size" class="form-control" value="<?= htmlspecialchars($g['size']) ?>">
      </div>
      <div class="col-md-12">
        <label class="form-label">Kapak Görsel URL</label>
        <input type="url" name="image_url" class="form-control" value="<?= htmlspecialchars($g['image_url']) ?>">
      </div>
      <div class="col-md-12">
        <label class="form-label">Açıklama</label>
        <textarea name="description" rows="6" class="form-control"><?= htmlspecialchars($g['description']) ?></textarea>
      </div>
      <div class="col-md-12">
        <label class="form-label">Oyun İçi Görseller (virgülle ayır)</label>
        <textarea name="screenshots" rows="2" class="form-control"><?= htmlspecialchars($g['screenshots']) ?></textarea>
      </div>
    </div>

    <hr class="my-4">
    <h2 class="h6">İndirme Linkleri</h2>
    <div id="dl-wrap">
      <?php if($dl): foreach($dl as $item): ?>
      <div class="row g-2 mb-2">
        <div class="col-md-4"><input class="form-control" name="dl_name[]" value="<?= htmlspecialchars($item['name']) ?>" placeholder="Buton adı"></div>
        <div class="col-md-8"><input class="form-control" name="dl_url[]" value="<?= htmlspecialchars($item['url']) ?>" placeholder="https://..."></div>
      </div>
      <?php endforeach; else: ?>
      <div class="row g-2 mb-2">
        <div class="col-md-4"><input class="form-control" name="dl_name[]" placeholder="Buton adı"></div>
        <div class="col-md-8"><input class="form-control" name="dl_url[]" placeholder="https://..."></div>
      </div>
      <?php endif; ?>
    </div>
    <button type="button" class="btn btn-secondary btn-sm" onclick="addDL()">+ Link Ekle</button>

    <div class="mt-4 d-flex gap-2">
      <a href="dashboard.php" class="btn btn-outline-light">İptal</a>
      <button class="btn btn-accent">Güncelle</button>
    </div>
  </form>
</div>

<script>
function addDL(){
  const wrap = document.getElementById('dl-wrap');
  const row = document.createElement('div');
  row.className = 'row g-2 mb-2';
  row.innerHTML = `<div class="col-md-4"><input class="form-control" name="dl_name[]" placeholder="Buton adı"></div>
                   <div class="col-md-8"><input class="form-control" name="dl_url[]" placeholder="https://..."></div>`;
  wrap.appendChild(row);
}
</script>
</body>
</html>

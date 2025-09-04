
<?php require 'auth.php'; require_once '../includes/db.php';
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
        if($url !== ''){ $links[] = ['name' => $name ?: 'İndir', 'url' => $url]; }
    }
    $stmt = $db->prepare("INSERT INTO games (title, description, size, image_url, screenshots, download_links) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $size, $image_url, $screenshots, json_encode($links, JSON_UNESCAPED_UNICODE)]);
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
    
    $favicon   = '../assets/favicon.ico'; // favicon dosya yolu
  ?>
  <title>Oyun Ekle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <link rel="icon" type="image/x-icon" href="<?= $favicon ?>">
  <style>
    label {
      color: #fff !important;
    }
   h2 {
      color: #fff !important;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <h1 class="h4 mb-3">Oyun Ekle</h1>
  <form method="post" class="card card-soft p-3">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Başlık</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Boyut</label>
        <input type="text" name="size" class="form-control" placeholder="Örn: 12 GB">
      </div>
      <div class="col-md-12">
        <label class="form-label">Kapak Görsel URL</label>
        <input type="url" name="image_url" class="form-control" placeholder="https://...">
      </div>
      <div class="col-md-12">
        <label class="form-label">Açıklama</label>
        <textarea name="description" rows="6" class="form-control" placeholder="Oyun hakkında..."></textarea>
      </div>
      <div class="col-md-12">
        <label class="form-label">Oyun İçi Görseller (virgülle ayır)</label>
        <textarea name="screenshots" rows="2" class="form-control" placeholder="https://... , https://..."></textarea>
      </div>
    </div>

    <hr class="my-4">
    <h2 class="h6">İndirme Linkleri</h2>
    <div id="dl-wrap">
      <div class="row g-2 mb-2">
        <div class="col-md-4"><input class="form-control" name="dl_name[]" placeholder="Buton adı (örn: Google Drive)"></div>
        <div class="col-md-8"><input class="form-control" name="dl_url[]" placeholder="https://..."></div>
      </div>
    </div>
    <button type="button" class="btn btn-secondary btn-sm" onclick="addDL()">+ Link Ekle</button>

    <div class="mt-4 d-flex gap-2">
      <a href="dashboard.php" class="btn btn-outline-light">İptal</a>
      <button class="btn btn-accent">Kaydet</button>
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

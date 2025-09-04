<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Eğer zaten giriş yapılmışsa admin paneline yönlendir
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Sabit kullanıcı bilgileri
    $valid_user = "kullanıcı1";
    $valid_pass = "68684040";

    if ($username === $valid_user && $password === $valid_pass) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Kullanıcı adı veya şifre hatalı!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Giriş</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
    
    $favicon   = '../assets/favicon.ico'; // favicon dosya yolu
  ?>
    <link rel="icon" type="image/x-icon" href="<?= $favicon ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0f1724;
            color: #e6eef8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: rgba(255,255,255,0.05);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            width: 320px;
        }
        .login-box h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #7c5cff;
        }
        .login-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            outline: none;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #7c5cff;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-box button:hover {
            background: #5a3ecc;
        }
        .error {
            color: #ff6b6b;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Giriş</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="password" name="password" placeholder="Şifre" required>
            <button type="submit">Giriş Yap</button>
        </form>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    </div>
</body>
</html>

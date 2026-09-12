<?php
    // Kredensial dapat dioverride lewat environment variable (atau file
    // secrets.php di luar document root, lihat load_secrets.php) agar tidak
    // perlu mengubah kode saat rotasi password. Hash di bawah adalah fallback
    // untuk password default "passwordlogin" (bcrypt, dibuat dengan password_hash()).
    include_once __DIR__ . '/../load_secrets.php';
    $usernamelogin = getenv('DS_LOGIN_USERNAME') ?: 'rizky';
    $passwordlogin_hash = getenv('DS_LOGIN_PASSWORD_HASH')
        ?: '$2b$10$cK366Am2/mfNEnyUt7M34e3dp8XX3sGpQ52oFP6aUgTVRI22I/GVq';

    // memulai session
    session_start();

    // mengambil isian dari form login
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // pengecekan kredensial login
    if (hash_equals($usernamelogin, $username) && password_verify($password, $passwordlogin_hash)) {
        session_regenerate_id(true);
        $_SESSION['username'] = $username;
        header("Location: app.php");
        exit;
    }
    else {
        header("Location: login.php");
        exit;
   }
?>
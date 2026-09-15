<?php
    // Kredensial WAJIB datang dari environment variable (atau file secrets.php
    // di luar document root, lihat load_secrets.php). Tidak ada fallback ke
    // kredensial default: kalau belum dikonfigurasi, login ditolak (fail-closed)
    // supaya repo publik ini tidak memuat kredensial tebakan yang bisa dicoba
    // kalau secrets.php gagal termuat di server.
    include_once __DIR__ . '/load_secrets.php';
    require_once __DIR__ . '/csrf.php';
    $usernamelogin = getenv('DS_LOGIN_USERNAME');
    $passwordlogin_hash = getenv('DS_LOGIN_PASSWORD_HASH');

    // memulai session
    session_start();

    // mengambil isian dari form login
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    // tolak request tanpa CSRF token yang valid (mis. dari form asing / CSRF attack)
    if (!csrf_verify($csrf_token)) {
        header("Location: login.php");
        exit;
    }

    // kredensial belum dikonfigurasi di server (mis. secrets.php gagal termuat) -> tolak semua login
    if ($usernamelogin === false || $passwordlogin_hash === false) {
        error_log("Login ditolak: DS_LOGIN_USERNAME/DS_LOGIN_PASSWORD_HASH belum dikonfigurasi.");
        header("Location: login.php");
        exit;
    }

    // pengecekan kredensial login
    if (hash_equals($usernamelogin, $username) && password_verify($password, $passwordlogin_hash)) {
        session_regenerate_id(true);
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    }
    else {
        header("Location: login.php");
        exit;
    }
?>
<?php
    // Kredensial dapat dioverride lewat environment variable (atau file
    // secrets.php di luar document root, lihat load_secrets.php) agar tidak
    // perlu mengubah kode saat rotasi password. Hash di bawah adalah fallback
    // untuk password default "user123!?" (bcrypt, dibuat dengan password_hash()).
    include_once __DIR__ . '/load_secrets.php';
    require_once __DIR__ . '/csrf.php';
    $usernamelogin = getenv('DS_LOGIN_USERNAME') ?: 'user';
    $passwordlogin_hash = getenv('DS_LOGIN_PASSWORD_HASH')
        ?: '$2b$10$iiKnY0Kbf9jGuahS1ZcgKeIw/POzLtQ49UkWJEZhZ7HFGSlCnz/LK';

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
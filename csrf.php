<?php
// Helper CSRF sederhana berbasis synchronizer token per-session.
// Pemanggil wajib sudah memanggil session_start() sebelum memakai fungsi ini.
//
// Cara pakai:
//   - Di form: panggil echo csrf_field() di dalam blok PHP form.
//   - Di handler POST: if (!csrf_verify($_POST['csrf_token'] ?? '')) { ...tolak... }

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_verify($token) {
    return isset($_SESSION['csrf_token']) && is_string($token) && $token !== ''
        && hash_equals($_SESSION['csrf_token'], $token);
}

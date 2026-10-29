<?php
session_start();
const DB_HOST = 'localhost'; const DB_NAME = 'library_db'; const DB_USER = 'root'; const DB_PASS = '';
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $ex) {
    die('Дерекқорға қосыла алмадым. phpMyAdmin-де db.sql файлын импорттадыңыз ба?');
}
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function csrf() { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_field() { return '<input type="hidden" name="csrf" value="'.csrf().'">'; }
function csrf_check() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(403); die('Қауіпсіздік қатесі (CSRF).'); }
}
function user() { return $_SESSION['user'] ?? null; }
function require_login() { if (!user()) { header('Location: login.php'); exit; } }
function flash($m = null) {
    if ($m !== null) { $_SESSION['flash'] = $m; return; }
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}

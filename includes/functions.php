<?php
// Core helpers: config, PDO connection, safe queries, input cleaning, sessions, CSRF.
session_start();
const DB_HOST = '127.0.0.1', DB_NAME = 'company_pulse', DB_USER = 'root', DB_PASS = '';

function db($useDb = true) {
    static $conn = [];
    $k = (int)$useDb;
    if (!isset($conn[$k])) {
        $dsn = 'mysql:host=' . DB_HOST . ($useDb ? ';dbname=' . DB_NAME : '') . ';charset=utf8mb4';
        $conn[$k] = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $conn[$k];
}
function query($sql, $params = []) { $st = db()->prepare($sql); $st->execute($params); return $st; }
function column($sql, $params = []) { return query($sql, $params)->fetchAll(PDO::FETCH_COLUMN); }
function sanitize($s) { return trim(strip_tags((string)$s)); }          // clean input
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); } // escape output
function loggedIn() { return isset($_SESSION['user']); }
function requireLogin() { if (!loggedIn()) { header('Location: login.php'); exit; } }
function isPost() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function csrfField() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}
function csrfOk() { return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']); }
function validUsername($u) { return (bool)preg_match('/^[A-Za-z0-9_]{3,16}$/', $u); }
function memberExists($u) { return (bool)query('SELECT 1 FROM members WHERE user=?', [$u])->fetch(); }

function thumb($user) {
    $f = "uploads/avatars/$user.jpg";
    if (file_exists($f)) return $f . '?v=' . filemtime($f);
    return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23c9d3e6'/%3E%3Ccircle cx='50' cy='38' r='18' fill='%23fff'/%3E%3Cellipse cx='50' cy='88' rx='30' ry='24' fill='%23fff'/%3E%3C/svg%3E";
}
// Follow / unfollow (POST + CSRF), used by members.php and friends.php
function handleFollow() {
    if (!isPost() || !csrfOk() || !isset($_POST['who'])) return;
    $me = $_SESSION['user']; $who = sanitize($_POST['who']);
    if ($who === $me || !memberExists($who)) return;
    if (isset($_POST['follow'])) query('INSERT IGNORE INTO friends (user, friend) VALUES (?, ?)', [$me, $who]);
    if (isset($_POST['unfollow'])) query('DELETE FROM friends WHERE user=? AND friend=?', [$me, $who]);
    header('Location: ' . basename($_SERVER['SCRIPT_NAME'])); exit;
}
function followButton($who, $action, $label) {
    return '<form method="post" data-ajax="false" class="inline">' . csrfField() .
        '<input type="hidden" name="who" value="' . e($who) . '">' .
        '<button name="' . $action . '" value="1" class="ui-btn ui-btn-inline ui-mini ui-corner-all">' . e($label) . '</button></form>';
}
function userCard($u, $actions = '') {
    return '<div class="card"><img class="thumb" src="' . e(thumb($u)) . '" width="100" height="100" alt="">' .
        '<div class="body"><a href="profile.php?view=' . urlencode($u) . '" data-ajax="false"><strong>' . e($u) . '</strong></a><div>' . $actions . '</div></div></div>';
}
function pageEnd() { echo '</div></div></body></html>'; }

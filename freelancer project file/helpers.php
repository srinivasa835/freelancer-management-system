<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
// Critical: this data changes constantly (clients, payments, projects...).
// Explicitly forbid any cache/CDN from ever storing or reusing these
// responses — every request must hit the real database, every time.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 60);
session_set_cookie_params([
  'lifetime' => 60 * 60 * 24 * 60, // 60 days — installed app shouldn't demand the password every launch
  'path' => '/',
  'secure' => false,
  'httponly' => true,
  'samesite' => 'Lax',
]);
session_name('twinex_session_' . substr(md5(SESSION_SECRET), 0, 8));
session_start();

function json_input() {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function respond($data, $status = 200) {
  http_response_code($status);
  echo json_encode($data);
  exit;
}

function require_auth() {
  if (empty($_SESSION['twinex_authed'])) {
    respond(['error' => 'Not authenticated'], 401);
  }
}

function method() {
  return $_SERVER['REQUEST_METHOD'];
}

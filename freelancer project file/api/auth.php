<?php
require_once __DIR__ . '/../helpers.php';

$m = method();

if ($m === 'GET') {
  // Check whether the current browser session is already logged in
  respond(['authenticated' => !empty($_SESSION['twinex_authed'])]);
}

if ($m === 'POST') {
  $body = json_input();
  $password = $body['password'] ?? '';
  if (hash_equals(APP_PASSWORD, (string)$password)) {
    $_SESSION['twinex_authed'] = true;
    respond(['authenticated' => true]);
  }
  respond(['authenticated' => false, 'error' => 'Incorrect password'], 401);
}

if ($m === 'DELETE') {
  $_SESSION = [];
  session_destroy();
  respond(['authenticated' => false]);
}

respond(['error' => 'Method not allowed'], 405);

<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

require_auth();
$pdo = db();
$m = method();

if ($m === 'GET') {
  $rows = $pdo->query("SELECT * FROM payments ORDER BY id DESC")->fetchAll();
  respond(array_map(function ($r) {
    return [
      'id' => (int)$r['id'],
      'type' => $r['type'],
      'title' => $r['title'],
      'client' => $r['client'],
      'project' => $r['project'],
      'amount' => (float)$r['amount'],
      'date' => $r['date'],
    ];
  }, $rows));
}

if ($m === 'POST') {
  $b = json_input();
  $date = $b['date'] ?? date('d M Y');
  $stmt = $pdo->prepare("INSERT INTO payments (type, title, client, project, amount, date) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $b['type'] ?? 'incoming',
    $b['title'] ?? '',
    $b['client'] ?? null,
    $b['project'] ?? null,
    $b['amount'] ?? 0,
    $date,
  ]);
  $id = (int)$pdo->lastInsertId();
  respond([
    'id' => $id,
    'type' => $b['type'] ?? 'incoming',
    'title' => $b['title'] ?? '',
    'client' => $b['client'] ?? null,
    'project' => $b['project'] ?? null,
    'amount' => (float)($b['amount'] ?? 0),
    'date' => $date,
  ], 201);
}

if ($m === 'DELETE') {
  $id = $_GET['id'] ?? null;
  if (!$id) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("DELETE FROM payments WHERE id=?");
  $stmt->execute([$id]);
  respond(['ok' => true]);
}

respond(['error' => 'Method not allowed'], 405);

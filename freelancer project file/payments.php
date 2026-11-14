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

if ($m === 'PUT') {
  $b = json_input();
  if (empty($b['id'])) respond(['error' => 'id required'], 400);

  $stmt = $pdo->prepare("SELECT * FROM payments WHERE id=?");
  $stmt->execute([$b['id']]);
  $existing = $stmt->fetch();
  if (!$existing) respond(['error' => 'not found'], 404);

  $type   = $b['type'] ?? $existing['type'];
  $title  = $b['title'] ?? $existing['title'];
  $client = array_key_exists('client', $b) ? $b['client'] : $existing['client'];
  $project = array_key_exists('project', $b) ? $b['project'] : $existing['project'];
  $amount = $b['amount'] ?? $existing['amount'];
  $date   = $b['date'] ?? $existing['date'];

  $stmt = $pdo->prepare("UPDATE payments SET type=?, title=?, client=?, project=?, amount=?, date=? WHERE id=?");
  $stmt->execute([$type, $title, $client, $project, $amount, $date, $b['id']]);

  respond([
    'id' => (int)$b['id'],
    'type' => $type,
    'title' => $title,
    'client' => $client,
    'project' => $project,
    'amount' => (float)$amount,
    'date' => $date,
  ]);
}

if ($m === 'DELETE') {
  $id = $_GET['id'] ?? null;
  if (!$id) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("DELETE FROM payments WHERE id=?");
  $stmt->execute([$id]);
  respond(['ok' => true]);
}

respond(['error' => 'Method not allowed'], 405);

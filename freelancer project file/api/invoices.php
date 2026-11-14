<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

require_auth();
$pdo = db();
$m = method();

function invoice_row_to_json($r) {
  return [
    'id' => (int)$r['id'],
    'number' => $r['number'],
    'client' => $r['client'],
    'project' => $r['project'],
    'amount' => (float)$r['amount'],
    'dueDate' => $r['due_date'],
    'notes' => $r['notes'],
    'status' => $r['status'],
    'createdDate' => $r['created_date'],
  ];
}

if ($m === 'GET') {
  $rows = $pdo->query("SELECT * FROM invoices ORDER BY id DESC")->fetchAll();
  respond(array_map('invoice_row_to_json', $rows));
}

if ($m === 'POST') {
  $b = json_input();
  // Auto-number: INV-001, INV-002, ...
  $count = (int)$pdo->query("SELECT COUNT(*) c FROM invoices")->fetch()['c'];
  $number = $b['number'] ?? ('INV-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT));
  $createdDate = $b['createdDate'] ?? date('d M Y');
  $dueDate = $b['dueDate'] ?? 'On receipt';

  $stmt = $pdo->prepare("INSERT INTO invoices (number, client, project, amount, due_date, notes, status, created_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $number,
    $b['client'] ?? '',
    $b['project'] ?? null,
    $b['amount'] ?? 0,
    $dueDate,
    $b['notes'] ?? '',
    $b['status'] ?? 'draft',
    $createdDate,
  ]);
  $id = (int)$pdo->lastInsertId();
  respond([
    'id' => $id,
    'number' => $number,
    'client' => $b['client'] ?? '',
    'project' => $b['project'] ?? null,
    'amount' => (float)($b['amount'] ?? 0),
    'dueDate' => $dueDate,
    'notes' => $b['notes'] ?? '',
    'status' => $b['status'] ?? 'draft',
    'createdDate' => $createdDate,
  ], 201);
}

if ($m === 'PUT') {
  $b = json_input();
  if (empty($b['id'])) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("UPDATE invoices SET status=? WHERE id=?");
  $stmt->execute([$b['status'] ?? 'draft', $b['id']]);
  respond(['ok' => true]);
}

respond(['error' => 'Method not allowed'], 405);

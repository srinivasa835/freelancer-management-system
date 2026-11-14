<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

require_auth();
$pdo = db();
$m = method();

function project_row_to_json($r) {
  return [
    'id' => (int)$r['id'],
    'name' => $r['name'],
    'client' => $r['client'],
    'status' => $r['status'],
    'budget' => (float)$r['budget'],
    'paid' => (float)$r['paid'],
    'expenses' => (float)$r['expenses'],
    'deadline' => $r['deadline'],
    'memberNames' => json_decode($r['member_names'] ?: '[]', true),
  ];
}

function fetch_project($pdo, $id) {
  $stmt = $pdo->prepare("SELECT * FROM projects WHERE id=?");
  $stmt->execute([$id]);
  return $stmt->fetch();
}

if ($m === 'GET') {
  $rows = $pdo->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll();
  respond(array_map('project_row_to_json', $rows));
}

if ($m === 'POST') {
  $b = json_input();
  $memberNames = $b['memberNames'] ?? $b['member_names'] ?? [];
  $stmt = $pdo->prepare("INSERT INTO projects (name, client, status, budget, paid, expenses, deadline, member_names) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $b['name'] ?? '',
    $b['client'] ?? '',
    $b['status'] ?? 'new',
    $b['budget'] ?? 0,
    $b['paid'] ?? 0,
    $b['expenses'] ?? 0,
    $b['deadline'] ?? null,
    json_encode($memberNames),
  ]);
  $id = (int)$pdo->lastInsertId();
  respond(project_row_to_json(fetch_project($pdo, $id)), 201);
}

if ($m === 'PUT') {
  // Partial update: merge whatever fields were sent over the existing row,
  // so e.g. sending just { id, memberNames } doesn't blank out the budget.
  $b = json_input();
  if (empty($b['id'])) respond(['error' => 'id required'], 400);
  $existing = fetch_project($pdo, $b['id']);
  if (!$existing) respond(['error' => 'not found'], 404);

  $memberNamesIncoming = $b['memberNames'] ?? $b['member_names'] ?? null;

  $merged = [
    'name'      => array_key_exists('name', $b) ? $b['name'] : $existing['name'],
    'client'    => array_key_exists('client', $b) ? $b['client'] : $existing['client'],
    'status'    => array_key_exists('status', $b) ? $b['status'] : $existing['status'],
    'budget'    => array_key_exists('budget', $b) ? $b['budget'] : $existing['budget'],
    'paid'      => array_key_exists('paid', $b) ? $b['paid'] : $existing['paid'],
    'expenses'  => array_key_exists('expenses', $b) ? $b['expenses'] : $existing['expenses'],
    'deadline'  => array_key_exists('deadline', $b) ? $b['deadline'] : $existing['deadline'],
    'member_names' => $memberNamesIncoming !== null ? json_encode($memberNamesIncoming) : $existing['member_names'],
  ];

  $stmt = $pdo->prepare("UPDATE projects SET name=?, client=?, status=?, budget=?, paid=?, expenses=?, deadline=?, member_names=? WHERE id=?");
  $stmt->execute([
    $merged['name'], $merged['client'], $merged['status'], $merged['budget'],
    $merged['paid'], $merged['expenses'], $merged['deadline'], $merged['member_names'],
    $b['id'],
  ]);
  respond(project_row_to_json(fetch_project($pdo, $b['id'])));
}

if ($m === 'DELETE') {
  $id = $_GET['id'] ?? null;
  if (!$id) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("DELETE FROM projects WHERE id=?");
  $stmt->execute([$id]);
  respond(['ok' => true]);
}

respond(['error' => 'Method not allowed'], 405);

<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

require_auth();
$pdo = db();
$m = method();

if ($m === 'GET') {
  $rows = $pdo->query("SELECT * FROM tasks ORDER BY id DESC")->fetchAll();
  respond(array_map(function ($r) {
    return [
      'id' => (int)$r['id'],
      'title' => $r['title'],
      'description' => $r['description'],
      'project' => $r['project'],
      'assignee' => $r['assignee'],
      'status' => $r['status'],
      'deadlineDate' => $r['deadline_date'],
      'deadlineTime' => $r['deadline_time'],
    ];
  }, $rows));
}

if ($m === 'POST') {
  $b = json_input();
  $stmt = $pdo->prepare("INSERT INTO tasks (title, description, project, assignee, status, deadline_date, deadline_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $b['title'] ?? '',
    $b['description'] ?? '',
    $b['project'] ?? null,
    $b['assignee'] ?? null,
    $b['status'] ?? 'assigned',
    $b['deadlineDate'] ?? null,
    $b['deadlineTime'] ?? null,
  ]);
  $id = (int)$pdo->lastInsertId();
  $b['id'] = $id;
  respond($b, 201);
}

if ($m === 'PUT') {
  $b = json_input();
  if (empty($b['id'])) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("UPDATE tasks SET status=? WHERE id=?");
  $stmt->execute([$b['status'] ?? 'assigned', $b['id']]);
  respond(['ok' => true]);
}

if ($m === 'DELETE') {
  $id = $_GET['id'] ?? null;
  if (!$id) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("DELETE FROM tasks WHERE id=?");
  $stmt->execute([$id]);
  respond(['ok' => true]);
}

respond(['error' => 'Method not allowed'], 405);

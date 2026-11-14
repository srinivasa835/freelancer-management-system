<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

require_auth();
$pdo = db();
$m = method();

if ($m === 'GET') {
  $rows = $pdo->query("SELECT id, name, role, email, phone FROM team_members ORDER BY id DESC")->fetchAll();
  respond(array_map(function ($r) {
    $r['id'] = (int)$r['id'];
    return $r;
  }, $rows));
}

if ($m === 'POST') {
  $b = json_input();
  $stmt = $pdo->prepare("INSERT INTO team_members (name, role, email, phone) VALUES (?, ?, ?, ?)");
  $stmt->execute([$b['name'] ?? '', $b['role'] ?? '', $b['email'] ?? '', $b['phone'] ?? '']);
  $id = (int)$pdo->lastInsertId();
  respond(['id' => $id, 'name' => $b['name'] ?? '', 'role' => $b['role'] ?? '', 'email' => $b['email'] ?? '', 'phone' => $b['phone'] ?? ''], 201);
}

if ($m === 'PUT') {
  $b = json_input();
  if (empty($b['id'])) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, email=?, phone=? WHERE id=?");
  $stmt->execute([$b['name'] ?? '', $b['role'] ?? '', $b['email'] ?? '', $b['phone'] ?? '', $b['id']]);
  respond(['ok' => true]);
}

if ($m === 'DELETE') {
  $id = $_GET['id'] ?? null;
  if (!$id) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("DELETE FROM team_members WHERE id=?");
  $stmt->execute([$id]);
  respond(['ok' => true]);
}

respond(['error' => 'Method not allowed'], 405);

<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

require_auth();
$pdo = db();
$m = method();

if ($m === 'GET') {
  $rows = $pdo->query("SELECT id, name, phone, status, added_at, domain, domain_price, domain_renewal, hosting_plan, hosting_price, hosting_renewal FROM clients ORDER BY id DESC")->fetchAll();
  $out = array_map(function ($r) {
    return [
      'id' => (int)$r['id'],
      'name' => $r['name'],
      'phone' => $r['phone'],
      'status' => $r['status'],
      'added' => $r['added_at'],
      'domain' => $r['domain'],
      'domainPrice' => $r['domain_price'] !== null ? (float)$r['domain_price'] : 0,
      'domainRenewal' => $r['domain_renewal'],
      'hostingPlan' => $r['hosting_plan'],
      'hostingPrice' => $r['hosting_price'] !== null ? (float)$r['hosting_price'] : 0,
      'hostingRenewal' => $r['hosting_renewal'],
    ];
  }, $rows);
  respond($out);
}

if ($m === 'POST') {
  $b = json_input();
  $stmt = $pdo->prepare("INSERT INTO clients (name, phone, status, domain, domain_price, domain_renewal, hosting_plan, hosting_price, hosting_renewal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $b['name'] ?? '',
    $b['phone'] ?? '',
    $b['status'] ?? 'new',
    $b['domain'] ?? null,
    $b['domain_price'] ?? 0,
    $b['domain_renewal'] ?? null,
    $b['hosting_plan'] ?? null,
    $b['hosting_price'] ?? 0,
    $b['hosting_renewal'] ?? null,
  ]);
  $id = (int)$pdo->lastInsertId();
  respond([
    'id' => $id,
    'name' => $b['name'] ?? '',
    'phone' => $b['phone'] ?? '',
    'status' => $b['status'] ?? 'new',
    'added' => 'just now',
    'domain' => $b['domain'] ?? '',
    'domainPrice' => $b['domain_price'] ?? 0,
    'domainRenewal' => $b['domain_renewal'] ?? '',
    'hostingPlan' => $b['hosting_plan'] ?? '',
    'hostingPrice' => $b['hosting_price'] ?? 0,
    'hostingRenewal' => $b['hosting_renewal'] ?? '',
  ], 201);
}

if ($m === 'PUT') {
  $b = json_input();
  if (empty($b['id'])) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("UPDATE clients SET name=?, phone=?, status=?, domain=?, domain_price=?, domain_renewal=?, hosting_plan=?, hosting_price=?, hosting_renewal=? WHERE id=?");
  $stmt->execute([
    $b['name'] ?? '',
    $b['phone'] ?? '',
    $b['status'] ?? 'new',
    $b['domain'] ?? null,
    $b['domain_price'] ?? 0,
    $b['domain_renewal'] ?? null,
    $b['hosting_plan'] ?? null,
    $b['hosting_price'] ?? 0,
    $b['hosting_renewal'] ?? null,
    $b['id'],
  ]);
  respond(['ok' => true]);
}

if ($m === 'DELETE') {
  $id = $_GET['id'] ?? null;
  if (!$id) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("DELETE FROM clients WHERE id=?");
  $stmt->execute([$id]);
  respond(['ok' => true]);
}

respond(['error' => 'Method not allowed'], 405);

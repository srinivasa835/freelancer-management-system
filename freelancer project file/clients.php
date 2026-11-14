<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db.php';

require_auth();
$pdo = db();
$m = method();

function client_row_to_json($r) {
  return [
    'id' => (int)$r['id'],
    'name' => $r['name'],
    'phone' => $r['phone'],
    'status' => $r['status'],
    'added' => $r['added_at'],
    'domain' => $r['domain'],
    'domainPrice' => (float)$r['domain_price'],
    'domainRenewal' => $r['domain_renewal'],
    'hostingPlan' => $r['hosting_plan'],
    'hostingPrice' => (float)$r['hosting_price'],
    'hostingRenewal' => $r['hosting_renewal'],
  ];
}

if ($m === 'GET') {
  $rows = $pdo->query("SELECT * FROM clients ORDER BY id DESC")->fetchAll();
  respond(array_map('client_row_to_json', $rows));
}

if ($m === 'POST') {
  $b = json_input();
  $stmt = $pdo->prepare("INSERT INTO clients (name, phone, status, domain, domain_price, domain_renewal, hosting_plan, hosting_price, hosting_renewal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $b['name'] ?? '',
    $b['phone'] ?? '',
    $b['status'] ?? 'new',
    $b['domain'] ?? null,
    $b['domain_price'] ?? ($b['domainPrice'] ?? 0),
    $b['domain_renewal'] ?? ($b['domainRenewal'] ?? null),
    $b['hosting_plan'] ?? ($b['hostingPlan'] ?? null),
    $b['hosting_price'] ?? ($b['hostingPrice'] ?? 0),
    $b['hosting_renewal'] ?? ($b['hostingRenewal'] ?? null),
  ]);
  $id = (int)$pdo->lastInsertId();
  $stmt = $pdo->prepare("SELECT * FROM clients WHERE id=?");
  $stmt->execute([$id]);
  respond(client_row_to_json($stmt->fetch()), 201);
}

if ($m === 'PUT') {
  $b = json_input();
  if (empty($b['id'])) respond(['error' => 'id required'], 400);

  $stmt = $pdo->prepare("SELECT * FROM clients WHERE id=?");
  $stmt->execute([$b['id']]);
  $existing = $stmt->fetch();
  if (!$existing) respond(['error' => 'not found'], 404);

  $name           = $b['name'] ?? $existing['name'];
  $phone          = $b['phone'] ?? $existing['phone'];
  $status         = $b['status'] ?? $existing['status'];
  $domain         = array_key_exists('domain', $b) ? $b['domain'] : $existing['domain'];
  $domainPrice    = $b['domain_price'] ?? ($b['domainPrice'] ?? $existing['domain_price']);
  $domainRenewal  = array_key_exists('domain_renewal', $b) ? $b['domain_renewal'] : (array_key_exists('domainRenewal', $b) ? $b['domainRenewal'] : $existing['domain_renewal']);
  $hostingPlan    = array_key_exists('hosting_plan', $b) ? $b['hosting_plan'] : (array_key_exists('hostingPlan', $b) ? $b['hostingPlan'] : $existing['hosting_plan']);
  $hostingPrice   = $b['hosting_price'] ?? ($b['hostingPrice'] ?? $existing['hosting_price']);
  $hostingRenewal = array_key_exists('hosting_renewal', $b) ? $b['hosting_renewal'] : (array_key_exists('hostingRenewal', $b) ? $b['hostingRenewal'] : $existing['hosting_renewal']);

  $stmt = $pdo->prepare("UPDATE clients SET name=?, phone=?, status=?, domain=?, domain_price=?, domain_renewal=?, hosting_plan=?, hosting_price=?, hosting_renewal=? WHERE id=?");
  $stmt->execute([$name, $phone, $status, $domain, $domainPrice, $domainRenewal, $hostingPlan, $hostingPrice, $hostingRenewal, $b['id']]);

  $stmt = $pdo->prepare("SELECT * FROM clients WHERE id=?");
  $stmt->execute([$b['id']]);
  respond(client_row_to_json($stmt->fetch()));
}

if ($m === 'DELETE') {
  $id = $_GET['id'] ?? null;
  if (!$id) respond(['error' => 'id required'], 400);
  $stmt = $pdo->prepare("DELETE FROM clients WHERE id=?");
  $stmt->execute([$id]);
  respond(['ok' => true]);
}

respond(['error' => 'Method not allowed'], 405);

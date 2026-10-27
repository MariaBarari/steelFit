<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) { http_response_code(401); echo json_encode(['ok'=>false,'message'=>'You must be logged in.']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'message'=>'Method not allowed.']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); echo json_encode(['ok'=>false,'message'=>'Invalid workout ID.']); exit; }

$stmt = $pdo->prepare('DELETE FROM workouts WHERE id = :id AND user_id = :user_id');
$stmt->execute(['id'=>$id, 'user_id'=>$userId]);
if ($stmt->rowCount() !== 1) { http_response_code(404); echo json_encode(['ok'=>false,'message'=>'Workout not found in your account.']); exit; }
echo json_encode(['ok'=>true]);

<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId < 1) { http_response_code(401); echo json_encode(['ok'=>false,'message'=>'Not authenticated']); exit; }
try {
    $stmt = $pdo->prepare('SELECT w.id, w.name, w.workout_date, w.duration_minutes, COUNT(DISTINCT we.id) AS exercise_count, COALESCE(SUM(we.sets),0) AS total_sets FROM workouts w LEFT JOIN workout_exercises we ON we.workout_id = w.id WHERE w.user_id = :uid GROUP BY w.id ORDER BY w.workout_date DESC, w.id DESC');
    $stmt->execute(['uid'=>$userId]);
    $workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare('SELECT w.workout_date, e.name AS exercise_name, MAX(we.weight) AS max_weight FROM workouts w JOIN workout_exercises we ON we.workout_id = w.id JOIN exercises e ON e.id = we.exercise_id WHERE w.user_id = :uid GROUP BY w.id, w.workout_date, e.id, e.name ORDER BY w.workout_date ASC, w.id ASC');
    $stmt->execute(['uid'=>$userId]);
    $progress = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $month = date('Y-m');
    $monthlySets = 0;
    $totalExercises = 0;
    foreach ($workouts as $w) {
        $totalExercises += (int)$w['exercise_count'];
        if (substr($w['workout_date'], 0, 7) === $month) $monthlySets += (int)$w['total_sets'];
    }
    echo json_encode(['ok'=>true,'totalWorkouts'=>count($workouts),'totalExercises'=>$totalExercises,'monthlySets'=>$monthlySets,'workouts'=>array_slice($workouts,0,4),'progress'=>$progress], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('Dashboard data error: '.$e->getMessage());
    http_response_code(500);
    echo json_encode(['ok'=>false,'message'=>'Could not load dashboard data.']);
}

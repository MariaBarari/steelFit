<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
$userId = (int)$_SESSION['user_id'];
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
try {
    $stmt = $pdo->prepare('SELECT w.id, w.name, w.workout_date, w.duration_minutes, w.notes, COUNT(we.id) AS exercise_count, COALESCE(SUM(we.sets),0) AS total_sets, GROUP_CONCAT(CONCAT(e.name, " (", we.sets, " x ", we.reps, ", ", FORMAT(we.weight, 2), " kg)") ORDER BY e.name SEPARATOR "||") AS exercise_details FROM workouts w LEFT JOIN workout_exercises we ON we.workout_id=w.id LEFT JOIN exercises e ON e.id=we.exercise_id WHERE w.user_id=:uid GROUP BY w.id ORDER BY w.workout_date DESC, w.id DESC');
    $stmt->execute(['uid'=>$userId]); $workouts=$stmt->fetchAll();
    $total=count($workouts); $thisMonth=0; $durationTotal=0;
    foreach($workouts as $w) { if (substr($w['workout_date'],0,7)===date('Y-m')) $thisMonth++; $durationTotal+=(int)$w['duration_minutes']; }
    $average=$total ? (int)round($durationTotal/$total) : 0;
} catch (PDOException $e) { error_log('Workout history error: '.$e->getMessage()); $workouts=[]; $total=$thisMonth=$average=0; $dbError='Nu am putut încărca istoricul antrenamentelor.'; }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>SteelFit - Workouts</title><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="light.css"></head><body>
<header><div class="logo"><a href="index.php"><img src="pozeex/logo2.png" alt="SteelFit Logo"></a></div><nav><a href="index.php">Dashboard</a><a href="workouts.php">Workouts</a><a href="add-workout.php">Add Workout</a><a href="templates.php">Templates</a><a href="exercises.php">Exercises</a><a href="profile.php">Profile</a></nav></header>
<main><section><h1>Workouts</h1><p>Your workout history is saved to your account in MySQL.</p></section>
<?php if(isset($dbError)): ?><p role="alert"><?=h($dbError)?></p><?php endif; ?>
<section><h2>Workout Statistics</h2><div><h3>Total Workouts</h3><p><?= $total ?></p></div><div><h3>This Month</h3><p><?= $thisMonth ?></p></div><div><h3>Average Duration</h3><p><?= $average ?> min</p></div></section><hr>
<section><h2>Workout History</h2><?php if(!$workouts): ?><p>No workouts saved yet. Finish a workout and submit its check-in to see it here.</p><?php else: ?><div style="overflow-x:auto"><table><thead><tr><th>Date</th><th>Workout</th><th>Exercises</th><th>Sets</th><th>Duration</th><th>Details</th></tr></thead><tbody>
<?php foreach($workouts as $w): ?><tr><td><?=h(date('d M Y',strtotime($w['workout_date'])))?></td><td><?=h($w['name'])?></td><td><?= (int)$w['exercise_count'] ?></td><td><?= (int)$w['total_sets'] ?></td><td><?= (int)$w['duration_minutes'] ?> min</td><td><details><summary>View</summary><?php if($w['exercise_details']): ?><ul><?php foreach(explode('||',$w['exercise_details']) as $detail): ?><li><?=h($detail)?></li><?php endforeach; ?></ul><?php endif; ?><?php if($w['notes']): ?><p><strong>Notes:</strong> <?=h($w['notes'])?></p><?php endif; ?></details></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section><hr><section><h2>Quick Actions</h2><a href="add-workout.php">Add Workout</a> &nbsp; <a href="templates.php">View Templates</a> &nbsp; <a href="exercises.php">Browse Exercises</a></section></main>
<footer><img src="pozeex/logo1.png" alt="SteelFit Logo" width="100"><p>&copy; 2026 SteelFit</p><a href="profile.php">Profile</a></footer></body></html>

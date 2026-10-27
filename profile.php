<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

function h($value): string { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8'); }
$userId = (int) $_SESSION['user_id'];
$errors = [];
$success = '';
$fields = [
    'age' => '', 'gender' => '', 'height_cm' => '', 'weight_kg' => '', 'body_fat' => '',
    'chest_cm' => '', 'waist_cm' => '', 'hips_cm' => '', 'left_arm_cm' => '', 'right_arm_cm' => '',
    'left_thigh_cm' => '', 'right_thigh_cm' => '', 'training_level' => '', 'primary_goal' => '',
    'training_days' => '', 'preferred_training' => '', 'activity_level' => '', 'sleep_goal' => '', 'water_goal_l' => ''
];

try {
    $stmt = $pdo->prepare('SELECT * FROM user_profiles WHERE user_id = :user_id LIMIT 1');
    $stmt->execute(['user_id' => $userId]);
    $savedProfile = $stmt->fetch();
    if ($savedProfile) {
        foreach ($fields as $key => $value) {
            if (array_key_exists($key, $savedProfile) && $savedProfile[$key] !== null) $fields[$key] = (string)$savedProfile[$key];
        }
    }
} catch (PDOException $e) {
    error_log('Profile read error: ' . $e->getMessage());
    http_response_code(500);
    exit('Nu am putut încărca profilul. Importă database_setup.sql și verifică baza de date.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $key => $value) $fields[$key] = trim((string)($_POST[$key] ?? ''));

    $numericRanges = [
        'age' => [13, 120, 'Vârsta'], 'height_cm' => [80, 250, 'Înălțimea'], 'weight_kg' => [20, 400, 'Greutatea'],
        'body_fat' => [1, 75, 'Procentul de grăsime'], 'chest_cm' => [1, 300, 'Bustul'], 'waist_cm' => [1, 300, 'Talia'],
        'hips_cm' => [1, 300, 'Șoldurile'], 'left_arm_cm' => [1, 150, 'Brațul stâng'], 'right_arm_cm' => [1, 150, 'Brațul drept'],
        'left_thigh_cm' => [1, 150, 'Coapsa stângă'], 'right_thigh_cm' => [1, 150, 'Coapsa dreaptă'],
        'training_days' => [1, 7, 'Zilele de antrenament'], 'water_goal_l' => [0.1, 15, 'Obiectivul de apă']
    ];
    foreach ($numericRanges as $key => [$min, $max, $label]) {
        if ($fields[$key] !== '' && (!is_numeric($fields[$key]) || (float)$fields[$key] < $min || (float)$fields[$key] > $max)) {
            $errors[] = "$label trebuie să fie între $min și $max.";
        }
    }
    $allowed = [
        'gender' => ['Female', 'Male', 'Non-binary', 'Prefer not to say'],
        'training_level' => ['Beginner', 'Intermediate', 'Advanced'],
        'primary_goal' => ['Build Muscle', 'Lose Fat', 'Maintain Weight', 'Improve Strength', 'Improve Endurance'],
        'preferred_training' => ['Weight Training', 'Cardio', 'Calisthenics', 'Mixed Training'],
        'activity_level' => ['Sedentary', 'Lightly Active', 'Moderately Active', 'Very Active']
    ];
    foreach ($allowed as $key => $options) {
        if ($fields[$key] !== '' && !in_array($fields[$key], $options, true)) $errors[] = 'A fost selectată o valoare invalidă.';
    }
    if (mb_strlen($fields['sleep_goal']) > 40) $errors[] = 'Obiectivul de somn este prea lung.';

    if (!$errors) {
        $sql = 'INSERT INTO user_profiles (user_id, age, gender, height_cm, weight_kg, body_fat, chest_cm, waist_cm, hips_cm, left_arm_cm, right_arm_cm, left_thigh_cm, right_thigh_cm, training_level, primary_goal, training_days, preferred_training, activity_level, sleep_goal, water_goal_l)
                VALUES (:user_id, :age, :gender, :height_cm, :weight_kg, :body_fat, :chest_cm, :waist_cm, :hips_cm, :left_arm_cm, :right_arm_cm, :left_thigh_cm, :right_thigh_cm, :training_level, :primary_goal, :training_days, :preferred_training, :activity_level, :sleep_goal, :water_goal_l)
                ON DUPLICATE KEY UPDATE age=VALUES(age), gender=VALUES(gender), height_cm=VALUES(height_cm), weight_kg=VALUES(weight_kg), body_fat=VALUES(body_fat), chest_cm=VALUES(chest_cm), waist_cm=VALUES(waist_cm), hips_cm=VALUES(hips_cm), left_arm_cm=VALUES(left_arm_cm), right_arm_cm=VALUES(right_arm_cm), left_thigh_cm=VALUES(left_thigh_cm), right_thigh_cm=VALUES(right_thigh_cm), training_level=VALUES(training_level), primary_goal=VALUES(primary_goal), training_days=VALUES(training_days), preferred_training=VALUES(preferred_training), activity_level=VALUES(activity_level), sleep_goal=VALUES(sleep_goal), water_goal_l=VALUES(water_goal_l), updated_at=CURRENT_TIMESTAMP';
        try {
            $stmt = $pdo->prepare($sql);
            $params = ['user_id' => $userId];
            foreach ($fields as $key => $value) $params[$key] = $value === '' ? null : $value;
            $stmt->execute($params);
            $success = 'Profilul a fost salvat. Datele tale sunt asociate doar contului tău.';
        } catch (PDOException $e) {
            error_log('Profile save error: ' . $e->getMessage());
            $errors[] = 'Profilul nu a putut fi salvat momentan. Încearcă din nou.';
        }
    }
}
function displayValue(array $fields, string $key, string $suffix = ''): string {
    return $fields[$key] !== '' ? h($fields[$key] . $suffix) : '<span class="not-set">Not set</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SteelFit - Profile</title><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="light.css">
</head>
<body>
<header>
    <div class="logo"><a href="index.php"><img src="pozeex/logo2.png" alt="SteelFit Logo"></a></div>
    <nav>
        <a href="index.php">Dashboard</a><a href="workouts.php">Workouts</a><a href="add-workout.php">Add Workout</a>
        <a href="templates.php">Templates</a><a href="exercises.php">Exercises</a><a href="profile.php">Profile</a>
    </nav>
</header>
<main class="profile-page">
    <h1>Your Profile</h1>
    <p>Your fitness details are private to your account. New accounts start with an empty profile.</p>
    <?php if ($success): ?><p class="success-message" role="status"><?= h($success) ?></p><?php endif; ?>
    <?php if ($errors): ?><div class="error-messages" role="alert"><?php foreach ($errors as $error): ?><p><?= h($error) ?></p><?php endforeach; ?></div><?php endif; ?>

    <section class="profile-summary">
        <h2>Personal & Fitness Information</h2>
        <div class="profile-summary-grid">
            <p><strong>Username:</strong> <?= h($_SESSION['username'] ?? 'User') ?></p>
            <p><strong>Age:</strong> <?= displayValue($fields, 'age', ' years') ?></p>
            <p><strong>Gender:</strong> <?= displayValue($fields, 'gender') ?></p>
            <p><strong>Height:</strong> <?= displayValue($fields, 'height_cm', ' cm') ?></p>
            <p><strong>Weight:</strong> <?= displayValue($fields, 'weight_kg', ' kg') ?></p>
            <p><strong>Body fat:</strong> <?= displayValue($fields, 'body_fat', '%') ?></p>
            <p><strong>Training level:</strong> <?= displayValue($fields, 'training_level') ?></p>
            <p><strong>Primary goal:</strong> <?= displayValue($fields, 'primary_goal') ?></p>
            <p><strong>Training days/week:</strong> <?= displayValue($fields, 'training_days') ?></p>
            <p><strong>Preferred training:</strong> <?= displayValue($fields, 'preferred_training') ?></p>
            <p><strong>Activity level:</strong> <?= displayValue($fields, 'activity_level') ?></p>
            <p><strong>Sleep goal:</strong> <?= displayValue($fields, 'sleep_goal') ?></p>
            <p><strong>Water goal:</strong> <?= displayValue($fields, 'water_goal_l', ' L/day') ?></p>
        </div>
    </section>

    <form method="post" action="profile.php" class="profile-form">
        <section><h2>Personal & Body Information</h2><p>All fields are optional. Fill in only the details you want to track.</p>
            <div class="profile-form-grid">
                <label>Age <input type="number" name="age" min="13" max="120" value="<?= h($fields['age']) ?>" placeholder="Years"></label>
                <label>Gender <select name="gender"><option value="">Select (optional)</option><?php foreach (['Female','Male','Non-binary','Prefer not to say'] as $v): ?><option value="<?= h($v) ?>" <?= $fields['gender']===$v?'selected':'' ?>><?= h($v) ?></option><?php endforeach; ?></select></label>
                <label>Height (cm) <input type="number" name="height_cm" min="80" max="250" step="0.1" value="<?= h($fields['height_cm']) ?>" placeholder="e.g. 170"></label>
                <label>Weight (kg) <input type="number" name="weight_kg" min="20" max="400" step="0.1" value="<?= h($fields['weight_kg']) ?>" placeholder="e.g. 70"></label>
                <label>Body fat (%) <input type="number" name="body_fat" min="1" max="75" step="0.1" value="<?= h($fields['body_fat']) ?>" placeholder="Optional"></label>
            </div>
        </section>
        <section><h2>Body Measurements</h2><div class="profile-form-grid">
            <?php foreach (['chest_cm'=>'Chest (cm)','waist_cm'=>'Waist (cm)','hips_cm'=>'Hips (cm)','left_arm_cm'=>'Left arm (cm)','right_arm_cm'=>'Right arm (cm)','left_thigh_cm'=>'Left thigh (cm)','right_thigh_cm'=>'Right thigh (cm)'] as $key=>$label): ?>
            <label><?= h($label) ?><input type="number" name="<?= h($key) ?>" min="1" max="300" step="0.1" value="<?= h($fields[$key]) ?>" placeholder="Not set"></label>
            <?php endforeach; ?>
        </div></section>
        <section><h2>Fitness Preferences</h2><div class="profile-form-grid">
            <?php $selects = ['training_level'=>['Training level',['Beginner','Intermediate','Advanced']], 'primary_goal'=>['Primary goal',['Build Muscle','Lose Fat','Maintain Weight','Improve Strength','Improve Endurance']], 'preferred_training'=>['Preferred training',['Weight Training','Cardio','Calisthenics','Mixed Training']], 'activity_level'=>['Activity level',['Sedentary','Lightly Active','Moderately Active','Very Active']]]; foreach ($selects as $key=>[$label,$options]): ?>
            <label><?= h($label) ?><select name="<?= h($key) ?>"><option value="">Select (optional)</option><?php foreach ($options as $v): ?><option value="<?= h($v) ?>" <?= $fields[$key]===$v?'selected':'' ?>><?= h($v) ?></option><?php endforeach; ?></select></label>
            <?php endforeach; ?>
            <label>Training days per week <input type="number" name="training_days" min="1" max="7" value="<?= h($fields['training_days']) ?>" placeholder="1–7"></label>
            <label>Sleep goal <input type="text" name="sleep_goal" maxlength="40" value="<?= h($fields['sleep_goal']) ?>" placeholder="e.g. 7–8 hours/night"></label>
            <label>Water goal (litres/day) <input type="number" name="water_goal_l" min="0.1" max="15" step="0.1" value="<?= h($fields['water_goal_l']) ?>" placeholder="e.g. 2.5"></label>
        </div><button type="submit">Save Profile</button></section>
    </form>

    <section id="appearance-settings" class="appearance-card">
        <details><summary><span>⚙</span> Appearance settings <small>Theme preferences</small></summary>
            <div class="appearance-content"><p>Choose a theme for SteelFit. Your choice is remembered in this browser.</p>
                <button type="button" id="theme-toggle" class="theme-toggle">Switch to Light Mode</button><p id="theme-message" role="status"></p>
            </div>
        </details>
    </section>
    <section class="account-settings-card">
        <h2>Account</h2>
        <p>Finished your session? You can safely sign out of your SteelFit account here.</p>
        <a href="logout.php" class="logout-button">Log out</a>
    </section>
</main>
<footer><img src="pozeex/logo1.png" alt="SteelFit Logo" width="100"><p>&copy; 2026 SteelFit</p><a href="profile.php">Profile</a></footer>
<script src="script.js"></script>
</body></html>

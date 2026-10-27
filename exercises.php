<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

$errors = [];
$success = '';
$userId = (int) ($_SESSION['user_id'] ?? 0);

/** Escape values before rendering them in HTML. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/* Built-in exercises are part of the application and do not need database rows. */
$catalog = [
    'Chest' => [
        ['name' => 'Bench Press', 'description' => 'A compound exercise that primarily targets the chest, with additional work from the shoulders and triceps.', 'image' => 'BenchPress.png'],
        ['name' => 'Incline Dumbbell Press', 'description' => 'A pressing exercise that emphasizes the upper chest while also training the shoulders and triceps.', 'image' => 'InclineDumbbellPress.png'],
        ['name' => 'Cable Fly', 'description' => 'An isolation exercise focused on the chest using constant cable resistance.', 'image' => 'CableFly.png'],
    ],
    'Back' => [
        ['name' => 'Lat Pulldown', 'description' => 'A pulling exercise that primarily targets the latissimus dorsi and also works the biceps and upper back.', 'image' => 'LatPulldown.png'],
        ['name' => 'Seated Cable Row', 'description' => 'A horizontal pulling exercise that targets the upper and middle back.', 'image' => 'SeatedCableRow.png'],
        ['name' => 'Deadlift', 'description' => 'A compound exercise that trains the posterior chain, including the back, glutes and hamstrings.', 'image' => 'Deadlift.png'],
    ],
    'Shoulders' => [
        ['name' => 'Shoulder Press', 'description' => 'A pressing exercise that primarily targets the shoulders, with additional work from the triceps.', 'image' => 'ShoulderPress.png'],
        ['name' => 'Lateral Raise', 'description' => 'An isolation exercise focused on the lateral part of the shoulders, helping develop shoulder width.', 'image' => 'LateralRaise.png'],
        ['name' => 'Face Pull', 'description' => 'A cable exercise that targets the rear shoulders and upper back.', 'image' => 'FacePull.png'],
    ],
    'Biceps' => [
        ['name' => 'Bicep Curl', 'description' => 'An isolation exercise that primarily targets the biceps.', 'image' => 'BicepCurl.png'],
        ['name' => 'Hammer Curl', 'description' => 'A biceps and forearm exercise performed with a neutral grip throughout the movement.', 'image' => 'HammerCurl.png'],
        ['name' => 'Preacher Curl', 'description' => 'Performed on the preacher machine or on an incline bench.', 'image' => ''],
    ],
    'Triceps' => [
        ['name' => 'Tricep Bar Pushdown', 'description' => 'An exercise that targets all three triceps heads.', 'image' => 'TricepPushdownWithBar.png'],
        ['name' => 'Close Grip Bench Press', 'description' => 'A variation of the traditional bench press, with a narrower grip, targeting the triceps.', 'image' => 'CloseGripBench.png'],
    ],
    'Legs' => [
        ['name' => 'Squat', 'description' => 'A compound lower-body exercise that targets the quadriceps, glutes and supporting muscles.', 'image' => 'Squat.png'],
        ['name' => 'Leg Press', 'description' => 'A machine-based compound exercise that primarily trains the quadriceps and glutes.', 'image' => 'LegPress.png'],
        ['name' => 'Leg Curl', 'description' => 'An isolation exercise that focuses mainly on the hamstrings.', 'image' => 'SeatedLegCurl.png'],
    ],
    'Abs' => [
        ['name' => 'Crunch', 'description' => 'A basic abdominal exercise that focuses on the muscles at the front of the core.', 'image' => 'Crunch.png'],
        ['name' => 'Hanging Leg Raise', 'description' => 'A challenging core exercise performed while hanging, primarily targeting the abdominal muscles.', 'image' => 'HangingLegRaise.png'],
        ['name' => 'Plank', 'description' => 'An isometric exercise that trains the core while requiring the body to remain stable.', 'image' => 'Plank.png'],
    ],
];

$builtInNames = [];

foreach ($catalog as $items) {
    foreach ($items as $item) {
        $builtInNames[] = mb_strtolower($item['name']);
    }
}

$muscleGroups = [
    'Chest',
    'Back',
    'Shoulders',
    'Biceps',
    'Triceps',
    'Arms',
    'Legs',
    'Abs',
    'Other',
];

/* Create a custom exercise for the currently authenticated user. */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'create_custom'
) {
    $name = trim($_POST['name'] ?? '');
    $muscleGroup = trim($_POST['muscle_group'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (mb_strlen($name) < 3 || mb_strlen($name) > 100) {
        $errors[] = 'The exercise name must contain between 3 and 100 characters.';
    }

    if (!in_array($muscleGroup, $muscleGroups, true)) {
        $errors[] = 'Please choose a valid muscle group.';
    }

    if (mb_strlen($description) > 1000) {
        $errors[] = 'The description cannot exceed 1000 characters.';
    }

    if (in_array(mb_strtolower($name), $builtInNames, true)) {
        $errors[] = 'This exercise already exists in the built-in catalog.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO exercises (user_id, name, muscle_group, description)
                 VALUES (:user_id, :name, :muscle_group, :description)'
            );

            $stmt->execute([
                'user_id' => $userId,
                'name' => $name,
                'muscle_group' => $muscleGroup,
                'description' => $description,
            ]);

            header('Location: exercises.php?created=1#custom-exercise');
            exit;
        } catch (PDOException $exception) {
            error_log('Create custom exercise error: ' . $exception->getMessage());
            $errors[] = 'Could not save the exercise. You may already have an exercise with this name.';
        }
    }
}

if (isset($_GET['created'])) {
    $success = 'Your custom exercise was saved successfully.';
}

/* Only load custom exercises created by this user. */
try {
    $stmt = $pdo->prepare(
        'SELECT id, name, muscle_group, description
         FROM exercises
         WHERE user_id = :user_id
         ORDER BY name ASC'
    );

    $stmt->execute(['user_id' => $userId]);
    $customExercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    error_log('Load custom exercises error: ' . $exception->getMessage());
    $customExercises = [];
    $errors[] = 'Could not load your custom exercises from the database.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SteelFit | Exercises</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="light.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php" aria-label="SteelFit dashboard">
                <img src="pozeex/logo2.png" alt="SteelFit Logo" width="150">
            </a>
        </div>

        <nav aria-label="Main navigation">
            <a href="index.php">Dashboard</a>
            <a href="workouts.php">Workouts</a>
            <a href="add-workout.php">Add Workout</a>
            <a href="templates.php">Templates</a>
            <a href="exercises.php" aria-current="page">Exercises</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    <main>
        <section class="page-intro">
            <h1>Exercise Library</h1>
            <p>
                Explore exercises by muscle group, review their descriptions,
                and build your own personal exercise list.
            </p>
        </section>

        <?php foreach ($errors as $error): ?>
            <p class="form-message form-message--error" role="alert">
                <?= e($error) ?>
            </p>
        <?php endforeach; ?>

        <?php if ($success !== ''): ?>
            <p class="form-message form-message--success" role="status">
                <?= e($success) ?>
            </p>
        <?php endif; ?>

        <section class="built-in-exercises" aria-labelledby="built-in-heading">
            <h2 id="built-in-heading">Built-in Exercises</h2>
            <p>
                These exercises are available to everyone and are stored
                directly in the application.
            </p>

            <?php foreach ($catalog as $muscleGroup => $items): ?>
                <section class="exercise-group">
                    <details>
                        <summary>
                            <h3><?= e($muscleGroup) ?></h3>
                        </summary>

                        <div class="exercise-list">
                            <?php foreach ($items as $item): ?>
                                <details class="exercise-item">
                                    <summary><?= e($item['name']) ?></summary>

                                    <p><?= e($item['description']) ?></p>

                                    <?php if ($item['image'] !== ''): ?>
                                        <img
                                            src="pozeex/<?= e($item['image']) ?>"
                                            alt="<?= e($item['name']) ?> demonstration"
                                            width="300"
                                            loading="lazy"
                                        >
                                    <?php else: ?>
                                        <p>
                                            <em>Demonstration image not available yet.</em>
                                        </p>
                                    <?php endif; ?>
                                </details>
                            <?php endforeach; ?>
                        </div>
                    </details>
                </section>

                <hr>
            <?php endforeach; ?>
        </section>

        <section id="custom-exercise" class="form-section">
            <h2>Create a Custom Exercise</h2>
            <p>
                Create an exercise for your own account.
                Your custom exercises are not shared with other users.
            </p>

            <form method="post" action="exercises.php#custom-exercise">
                <input type="hidden" name="action" value="create_custom">

                <div class="form-field">
                    <label for="name">Exercise name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        minlength="3"
                        maxlength="100"
                        placeholder="e.g. Machine Chest Press"
                    >
                </div>

                <div class="form-field">
                    <label for="muscle_group">Muscle group</label>
                    <select id="muscle_group" name="muscle_group" required>
                        <?php foreach ($muscleGroups as $group): ?>
                            <option value="<?= e($group) ?>">
                                <?= e($group) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label for="description">
                        Description <span>(optional)</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        maxlength="1000"
                        placeholder="Add instructions or notes about this exercise..."
                    ></textarea>
                </div>

                <button type="submit">Create Exercise</button>
            </form>

            <div class="custom-exercises" aria-labelledby="custom-exercises-heading">
                <h3 id="custom-exercises-heading">My Custom Exercises</h3>

                <?php if (!$customExercises): ?>
                    <p>You haven't created any custom exercises yet.</p>
                <?php else: ?>
                    <div class="custom-exercises-list">
                        <?php foreach ($customExercises as $exercise): ?>
                            <details class="exercise-item">
                                <summary><?= e($exercise['name']) ?></summary>

                                <p>
                                    <strong>Muscle group:</strong>
                                    <?= e($exercise['muscle_group']) ?>
                                </p>

                                <p>
                                    <?= e(
                                        $exercise['description']
                                        ?: 'No description provided.'
                                    ) ?>
                                </p>
                            </details>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="quick-actions" aria-labelledby="quick-actions-heading">
            <h2 id="quick-actions-heading">Quick Actions</h2>

            <div class="quick-actions__links">
                <a href="add-workout.php">Add Workout</a>
                <a href="templates.php">View Templates</a>
                <a href="index.php">Back to Dashboard</a>
            </div>
        </section>
    </main>

    <footer>
        <img src="pozeex/logo1.png" alt="SteelFit Logo" width="100">
        <p>&copy; 2026 SteelFit</p>
        <a href="profile.php">Profile</a>
    </footer>
</body>
</html>
<?php

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $data): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');

    respond(405, [
        'ok' => false,
        'message' => 'Method not allowed.',
    ]);
}

$userId = (int) ($_SESSION['user_id'] ?? 0);

if ($userId <= 0) {
    respond(401, [
        'ok' => false,
        'message' => 'You must be logged in to save a workout.',
    ]);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input)) {
    respond(400, [
        'ok' => false,
        'message' => 'Invalid request data.',
    ]);
}

$name = trim((string) ($input['name'] ?? ''));
$duration = filter_var(
    $input['durationMinutes'] ?? 0,
    FILTER_VALIDATE_INT
);

$exercises = $input['exercises'] ?? [];
$notes = trim((string) ($input['checkin']['notes'] ?? ''));

if (
    mb_strlen($name) < 3
    || mb_strlen($name) > 100
    || !is_array($exercises)
    || count($exercises) < 1
    || $duration === false
    || $duration < 0
    || $duration > 1440
    || mb_strlen($notes) > 500
) {
    respond(422, [
        'ok' => false,
        'message' => 'Check the workout name, duration and exercises.',
    ]);
}

/*
 * Use the current date by default.
 * If completedAt is a valid Unix timestamp in milliseconds,
 * use the corresponding date instead.
 */
$date = date('Y-m-d');

if (
    isset($input['completedAt'])
    && is_numeric($input['completedAt'])
) {
    $timestamp = (int) floor(
        (float) $input['completedAt'] / 1000
    );

    $date = date('Y-m-d', $timestamp);
}

/*
 * These are the built-in exercises supported by the application.
 * They are shared by all users.
 *
 * Custom exercises must already exist in the database and belong
 * to the currently logged-in user.
 */
$builtInExercises = [
    'Bench Press' => 'Chest',
    'Incline Dumbbell Press' => 'Chest',
    'Cable Fly' => 'Chest',

    'Lat Pulldown' => 'Back',
    'Seated Cable Row' => 'Back',
    'Deadlift' => 'Back',

    'Shoulder Press' => 'Shoulders',
    'Lateral Raise' => 'Shoulders',
    'Face Pull' => 'Shoulders',

    'Bicep Curl' => 'Biceps',
    'Hammer Curl' => 'Biceps',
    'Preacher Curl' => 'Biceps',

    'Tricep Bar Pushdown' => 'Triceps',
    'Tricep Pushdown' => 'Triceps',
    'Close Grip Bench Press' => 'Triceps',

    'Squat' => 'Legs',
    'Leg Press' => 'Legs',
    'Leg Curl' => 'Legs',

    'Crunch' => 'Abs',
    'Hanging Leg Raise' => 'Abs',
    'Plank' => 'Abs',
];

try {
    $pdo->beginTransaction();

    /*
     * Create the workout under the authenticated user's account.
     * Never accept user_id from the submitted JavaScript data.
     */
    $insertWorkout = $pdo->prepare(
        'INSERT INTO workouts
            (user_id, name, workout_date, duration_minutes, notes)
         VALUES
            (:user_id, :name, :workout_date, :duration, :notes)'
    );

    $insertWorkout->execute([
        'user_id' => $userId,
        'name' => $name,
        'workout_date' => $date,
        'duration' => $duration,
        'notes' => $notes,
    ]);

    $workoutId = (int) $pdo->lastInsertId();

    /*
     * Built-in exercises have user_id = NULL.
     * Custom exercises must match both their name and user_id.
     */
    $findBuiltIn = $pdo->prepare(
        'SELECT id
         FROM exercises
         WHERE name = :name
           AND user_id IS NULL
         LIMIT 1'
    );

    $findCustom = $pdo->prepare(
        'SELECT id
         FROM exercises
         WHERE name = :name
           AND user_id = :user_id
         LIMIT 1'
    );

    $createBuiltIn = $pdo->prepare(
        'INSERT INTO exercises
            (user_id, name, muscle_group, description)
         VALUES
            (NULL, :name, :muscle_group, :description)'
    );

    $insertWorkoutExercise = $pdo->prepare(
        'INSERT INTO workout_exercises
            (workout_id, exercise_id, sets, reps, weight)
         VALUES
            (:workout_id, :exercise_id, :sets, :reps, :weight)'
    );

    foreach ($exercises as $exercise) {
        if (!is_array($exercise)) {
            throw new RuntimeException(
                'Invalid exercise data.'
            );
        }

        $exerciseName = trim(
            (string) ($exercise['name'] ?? '')
        );

        if (
            $exerciseName === ''
            || mb_strlen($exerciseName) > 100
        ) {
            throw new RuntimeException(
                'An exercise has an invalid name.'
            );
        }

        /*
         * Resolve the exercise without allowing access to another
         * user's custom exercises.
         */
        if (isset($builtInExercises[$exerciseName])) {
            $findBuiltIn->execute([
                'name' => $exerciseName,
            ]);

            $exerciseId = $findBuiltIn->fetchColumn();

            /*
             * Create the shared built-in database record if it
             * doesn't exist yet.
             */
            if (!$exerciseId) {
                $createBuiltIn->execute([
                    'name' => $exerciseName,
                    'muscle_group' => $builtInExercises[$exerciseName],
                    'description' => '',
                ]);

                $exerciseId = (int) $pdo->lastInsertId();
            }
        } else {
            /*
             * A custom exercise must already exist and belong
             * to the current user.
             */
            $findCustom->execute([
                'name' => $exerciseName,
                'user_id' => $userId,
            ]);

            $exerciseId = $findCustom->fetchColumn();

            if (!$exerciseId) {
                throw new RuntimeException(
                    'An exercise is invalid or does not belong to your account: '
                    . $exerciseName
                );
            }
        }

        /*
         * Validate set information.
         */
        $setsList = is_array($exercise['sets'] ?? null)
            ? $exercise['sets']
            : [];

        $setCount = count($setsList);

        if ($setCount === 0) {
            $setCount = (int) ($exercise['setCount'] ?? 3);
        }

        $setCount = max(1, min(100, $setCount));

        $reps = 10;
        $weight = 0.0;

        if (isset($setsList[0]) && is_array($setsList[0])) {
            $rawReps = $setsList[0]['reps'] ?? null;
            $rawWeight = $setsList[0]['weight'] ?? null;

            if (
                $rawReps !== null
                && $rawReps !== ''
                && is_numeric($rawReps)
            ) {
                $reps = max(
                    1,
                    min(1000, (int) $rawReps)
                );
            }

            if (
                $rawWeight !== null
                && $rawWeight !== ''
                && is_numeric($rawWeight)
            ) {
                $weight = max(
                    0,
                    min(99999.99, (float) $rawWeight)
                );
            }
        }

        $insertWorkoutExercise->execute([
            'workout_id' => $workoutId,
            'exercise_id' => (int) $exerciseId,
            'sets' => $setCount,
            'reps' => $reps,
            'weight' => $weight,
        ]);
    }

    $pdo->commit();

    respond(200, [
        'ok' => true,
        'workoutId' => $workoutId,
        'message' => 'Workout saved successfully.',
    ]);

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Save workout error: ' . $e->getMessage());

    respond(422, [
        'ok' => false,
        'message' => 'The workout could not be saved. Check the exercises and try again.',
    ]);
}
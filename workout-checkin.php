<?php
require_once __DIR__ . '/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SteelFit - Workout Check-in</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="light.css">
</head>

<body>

<header>

    <div class="logo">
        <a href="index.php">
            <img src="pozeex/logo2.png" alt="SteelFit Logo" width="150">
        </a>
    </div>

    <nav>
        <a href="index.php">Dashboard</a>
        <a href="workouts.php">Workouts</a>
        <a href="add-workout.php">Add Workout</a>
        <a href="templates.php">Templates</a>
        <a href="exercises.php">Exercises</a>
        <a href="profile.php">Profile</a>
    </nav>

</header>

<main>

    <h1>Workout Check-in</h1>

    <p id="checkin-workout-name"></p>

    <form id="checkin-form">

        <section class="form-section">

            <label for="feeling">
                How did the workout feel?
            </label>

            <select id="feeling" required>
                <option value="">Select</option>
                <option value="easy">Easy</option>
                <option value="moderate">Moderate</option>
                <option value="hard">Hard</option>
                <option value="very-hard">Very hard</option>
            </select>

            <br><br>

            <label for="energy">
                Energy level:
            </label>

            <input
                type="number"
                id="energy"
                min="1"
                max="10"
                required
            >

            <br><br>

            <label for="sleep">
                Hours of sleep:
            </label>

            <input
                type="number"
                id="sleep"
                min="0"
                max="24"
                step="0.5"
                required
            >

            <br><br>

            <label for="calories">
                Estimated calorie intake:
            </label>

            <input
                type="number"
                id="calories"
                min="0"
                max="10000"
                required
            >

            <br><br>

            <label for="notes">
                Notes:
            </label>

            <br>

            <textarea
                id="notes"
                rows="5"
                cols="50"
                maxlength="500"
            ></textarea>

        </section>

        <br>

        <button type="submit">
            Save Workout
        </button>

    </form>

</main>

<footer>

    <img src="pozeex/logo1.png" alt="SteelFit Logo" width="100">

    <p>&copy; 2026 SteelFit</p>

    <a href="profile.php">Profile</a>

</footer>

<script src="script.js"></script>

</body>
</html>

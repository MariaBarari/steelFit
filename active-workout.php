<?php
require_once __DIR__ . '/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SteelFit - Active Workout</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- HEADER -->

    <header>

        <div class="logo">
            <a href="index.php">
                <img
                    src="pozeex/logo2.png"
                    alt="SteelFit Logo"
                    width="150"
                >
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


    <!-- MAIN -->

    <main>

        <h1 id="active-workout-name">Active Workout</h1>

        <p>
            Complete each set as you perform it.
        </p>

        <hr>

        <div id="active-exercises"></div>

        <hr>

        <section class="active-workout-actions">

            <button
                type="button"
                id="finish-workout"
            >
                Finish Workout
            </button>

        </section>

    </main>


    <!-- FOOTER -->

    <footer>

        <img
            src="pozeex/logo1.png"
            alt="SteelFit Logo"
            width="100"
        >

        <p>
            &copy; 2026 SteelFit
        </p>

        <a href="profile.php">Profile</a>

    </footer>


    <script src="script.js"></script>

</body>

</html>

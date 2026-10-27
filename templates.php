<?php
require_once __DIR__ . '/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SteelFit - Templates</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="light.css">
</head>

<body>

    <!-- HEADER -->

    <header>

        <div class="logo">
            <a href="index.php">
                <img src="pozeex/logo2.png" alt="SteelFit Logo">
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

        <h1>Workout Templates</h1>

        <p>
            Choose a predefined workout and use it as a starting point
            for your training session.
        </p>


        <hr>


        <!-- PUSH DAY -->

        <section>

            <h2>Push Day</h2>

            <p>
                Chest, shoulders and triceps.
            </p>


            <!-- BENCH PRESS -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Bench Press tips"
                >
                    Bench Press
                </a>
            </h3>

            <ol>

                <li>
                    50 kg &nbsp; 10 reps
                </li>

                <li>
                    55 kg &nbsp; 8 reps
                </li>

                <li>
                    60 kg &nbsp; 6 reps
                </li>

            </ol>


            <!-- INCLINE DUMBBELL PRESS -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Incline Dumbbell Press tips"
                >
                    Incline Dumbbell Press
                </a>
            </h3>

            <ol>

                <li>
                    16 kg &nbsp; 12 reps
                </li>

                <li>
                    18 kg &nbsp; 10 reps
                </li>

                <li>
                    20 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- CABLE FLY -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Cable Fly tips"
                >
                    Cable Fly
                </a>
            </h3>

            <ol>

                <li>
                    15 kg &nbsp; 12 reps
                </li>

                <li>
                    17 kg &nbsp; 10 reps
                </li>

                <li>
                    20 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- SHOULDER PRESS -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Shoulder Press tips"
                >
                    Shoulder Press
                </a>
            </h3>

            <ol>

                <li>
                    12 kg &nbsp; 12 reps
                </li>

                <li>
                    15 kg &nbsp; 10 reps
                </li>

                <li>
                    17 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- LATERAL RAISE -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Lateral Raise tips"
                >
                    Lateral Raise
                </a>
            </h3>

            <ol>

                <li>
                    6 kg &nbsp; 15 reps
                </li>

                <li>
                    8 kg &nbsp; 12 reps
                </li>

                <li>
                    8 kg &nbsp; 10 reps
                </li>

            </ol>


            <!-- TRICEP PUSHDOWN -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Tricep Pushdown tips"
                >
                    Tricep Pushdown
                </a>
            </h3>

            <ol>

                <li>
                    20 kg &nbsp; 15 reps
                </li>

                <li>
                    25 kg &nbsp; 12 reps
                </li>

                <li>
                    25 kg &nbsp; 10 reps
                </li>

            </ol>


            <br>

            <a href="add-workout.php">
                Use This Template
            </a>

        </section>


        <hr>


        <!-- PULL DAY -->

        <section>

            <h2>Pull Day</h2>

            <p>
                Back and biceps.
            </p>


            <!-- LAT PULLDOWN -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Lat Pulldown tips"
                >
                    Lat Pulldown
                </a>
            </h3>

            <ol>

                <li>
                    30 kg &nbsp; 10 reps
                </li>

                <li>
                    35 kg &nbsp; 8 reps
                </li>

                <li>
                    40 kg &nbsp; 6 reps
                </li>

            </ol>


            <!-- SEATED CABLE ROW -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Seated Cable Row tips"
                >
                    Seated Cable Row
                </a>
            </h3>

            <ol>

                <li>
                    35 kg &nbsp; 12 reps
                </li>

                <li>
                    40 kg &nbsp; 10 reps
                </li>

                <li>
                    45 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- DEADLIFT -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Deadlift tips"
                >
                    Deadlift
                </a>
            </h3>

            <ol>

                <li>
                    60 kg &nbsp; 8 reps
                </li>

                <li>
                    70 kg &nbsp; 6 reps
                </li>

                <li>
                    80 kg &nbsp; 5 reps
                </li>

            </ol>


            <!-- FACE PULL -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Face Pull tips"
                >
                    Face Pull
                </a>
            </h3>

            <ol>

                <li>
                    15 kg &nbsp; 15 reps
                </li>

                <li>
                    20 kg &nbsp; 12 reps
                </li>

                <li>
                    20 kg &nbsp; 10 reps
                </li>

            </ol>


            <!-- BICEP CURL -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Bicep Curl tips"
                >
                    Bicep Curl
                </a>
            </h3>

            <ol>

                <li>
                    8 kg &nbsp; 12 reps
                </li>

                <li>
                    10 kg &nbsp; 10 reps
                </li>

                <li>
                    12 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- HAMMER CURL -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Hammer Curl tips"
                >
                    Hammer Curl
                </a>
            </h3>

            <ol>

                <li>
                    10 kg &nbsp; 12 reps
                </li>

                <li>
                    12 kg &nbsp; 10 reps
                </li>

                <li>
                    12 kg &nbsp; 8 reps
                </li>

            </ol>


            <br>

            <a href="add-workout.php">
                Use This Template
            </a>

        </section>


        <hr>


        <!-- LEG DAY -->

        <section>

            <h2>Leg Day</h2>

            <p>
                Quads, hamstrings and lower body.
            </p>


            <!-- SQUAT -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Squat tips"
                >
                    Squat
                </a>
            </h3>

            <ol>

                <li>
                    40 kg &nbsp; 10 reps
                </li>

                <li>
                    50 kg &nbsp; 8 reps
                </li>

                <li>
                    60 kg &nbsp; 6 reps
                </li>

            </ol>


            <!-- LEG PRESS -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Leg Press tips"
                >
                    Leg Press
                </a>
            </h3>

            <ol>

                <li>
                    80 kg &nbsp; 12 reps
                </li>

                <li>
                    100 kg &nbsp; 10 reps
                </li>

                <li>
                    120 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- LEG CURL -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Leg Curl tips"
                >
                    Leg Curl
                </a>
            </h3>

            <ol>

                <li>
                    25 kg &nbsp; 15 reps
                </li>

                <li>
                    30 kg &nbsp; 12 reps
                </li>

                <li>
                    35 kg &nbsp; 10 reps
                </li>

            </ol>


            <!-- CALF RAISE -->

            <h3>
                Calf Raise
            </h3>

            <ol>

                <li>
                    40 kg &nbsp; 15 reps
                </li>

                <li>
                    50 kg &nbsp; 12 reps
                </li>

                <li>
                    50 kg &nbsp; 10 reps
                </li>

            </ol>


            <br>

            <a href="add-workout.php">
                Use This Template
            </a>

        </section>


        <hr>


        <!-- UPPER BODY -->

        <section>

            <h2>Upper Body</h2>

            <p>
                A balanced upper body workout.
            </p>


            <!-- BENCH PRESS -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Bench Press tips"
                >
                    Bench Press
                </a>
            </h3>

            <ol>

                <li>
                    50 kg &nbsp; 10 reps
                </li>

                <li>
                    55 kg &nbsp; 8 reps
                </li>

                <li>
                    60 kg &nbsp; 6 reps
                </li>

            </ol>


            <!-- LAT PULLDOWN -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Lat Pulldown tips"
                >
                    Lat Pulldown
                </a>
            </h3>

            <ol>

                <li>
                    30 kg &nbsp; 10 reps
                </li>

                <li>
                    35 kg &nbsp; 8 reps
                </li>

                <li>
                    40 kg &nbsp; 6 reps
                </li>

            </ol>


            <!-- SHOULDER PRESS -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Shoulder Press tips"
                >
                    Shoulder Press
                </a>
            </h3>

            <ol>

                <li>
                    12 kg &nbsp; 12 reps
                </li>

                <li>
                    15 kg &nbsp; 10 reps
                </li>

                <li>
                    17 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- SEATED CABLE ROW -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Seated Cable Row tips"
                >
                    Seated Cable Row
                </a>
            </h3>

            <ol>

                <li>
                    35 kg &nbsp; 12 reps
                </li>

                <li>
                    40 kg &nbsp; 10 reps
                </li>

                <li>
                    45 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- BICEP CURL -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Bicep Curl tips"
                >
                    Bicep Curl
                </a>
            </h3>

            <ol>

                <li>
                    8 kg &nbsp; 12 reps
                </li>

                <li>
                    10 kg &nbsp; 10 reps
                </li>

                <li>
                    12 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- TRICEP PUSHDOWN -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Tricep Pushdown tips"
                >
                    Tricep Pushdown
                </a>
            </h3>

            <ol>

                <li>
                    20 kg &nbsp; 15 reps
                </li>

                <li>
                    25 kg &nbsp; 12 reps
                </li>

                <li>
                    25 kg &nbsp; 10 reps
                </li>

            </ol>


            <br>

            <a href="add-workout.php">
                Use This Template
            </a>

        </section>


        <hr>


        <!-- LOWER BODY -->

        <section>

            <h2>Lower Body</h2>

            <p>
                A balanced lower body workout.
            </p>


            <!-- SQUAT -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Squat tips"
                >
                    Squat
                </a>
            </h3>

            <ol>

                <li>
                    40 kg &nbsp; 10 reps
                </li>

                <li>
                    50 kg &nbsp; 8 reps
                </li>

                <li>
                    60 kg &nbsp; 6 reps
                </li>

            </ol>


            <!-- LEG PRESS -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Leg Press tips"
                >
                    Leg Press
                </a>
            </h3>

            <ol>

                <li>
                    80 kg &nbsp; 12 reps
                </li>

                <li>
                    100 kg &nbsp; 10 reps
                </li>

                <li>
                    120 kg &nbsp; 8 reps
                </li>

            </ol>


            <!-- LEG CURL -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Leg Curl tips"
                >
                    Leg Curl
                </a>
            </h3>

            <ol>

                <li>
                    25 kg &nbsp; 15 reps
                </li>

                <li>
                    30 kg &nbsp; 12 reps
                </li>

                <li>
                    35 kg &nbsp; 10 reps
                </li>

            </ol>


            <!-- HANGING LEG RAISE -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Hanging Leg Raise tips"
                >
                    Hanging Leg Raise
                </a>
            </h3>

            <ol>

                <li>
                    Bodyweight &nbsp; 15 reps
                </li>

                <li>
                    Bodyweight &nbsp; 12 reps
                </li>

                <li>
                    Bodyweight &nbsp; 10 reps
                </li>

            </ol>


            <!-- PLANK -->

            <h3>
                <a
                    href="exercises.php"
                    title="View Plank tips"
                >
                    Plank
                </a>
            </h3>

            <ol>

                <li>
                    Bodyweight &nbsp; 60 sec
                </li>

                <li>
                    Bodyweight &nbsp; 45 sec
                </li>

                <li>
                    Bodyweight &nbsp; 45 sec
                </li>

            </ol>


            <br>

            <a href="add-workout.php">
                Use This Template
            </a>

        </section>

    </main>


    <!-- FOOTER -->

    <footer>

        <img src="pozeex/logo1.png" alt="SteelFit Logo" width="100">

        <p>
            &copy; 2026 SteelFit
        </p>

        <a href="profile.php">Profile</a>

    </footer>
<script src="script.js"></script>
</body>

</html>
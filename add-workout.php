<?php
require_once __DIR__ . '/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SteelFit - New Workout</title>
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

    <h1>New Workout</h1>

    <p>Create a new workout or start from a template.</p>

    <hr>

    <section class="form-section">
        <h2>Choose How to Start</h2>

        <label for="workout-type">Start with:</label>

        <select id="workout-type">
            <option value="new">New Workout</option>
            <option value="template">Template</option>
        </select>
    </section>

    <hr>

    <form id="workout-form">

        <!-- NEW WORKOUT -->

        <section id="new-workout" class="form-section">

            <h2>New Workout</h2>

            <label for="workout-name">Workout name:</label>

            <input
                type="text"
                id="workout-name"
                name="workout-name"
                placeholder="Example: Push Day"
                required
                minlength="3"
                maxlength="50"
            >

            <h3>Exercises</h3>

            <div id="exercise-list">
                <p id="no-exercises">No exercises added yet.</p>
            </div>

            <details>

                <summary>+ Add Exercise</summary>

                <h3>Choose Exercise</h3>

                <p>Select an exercise from the library.</p>

                <details>
                    <summary>Chest</summary>
                    <ul>
                        <li><button type="button" class="add-exercise" data-exercise="Bench Press">Bench Press</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Incline Dumbbell Press">Incline Dumbbell Press</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Cable Fly">Cable Fly</button></li>
                    </ul>
                </details>

                <details>
                    <summary>Back</summary>
                    <ul>
                        <li><button type="button" class="add-exercise" data-exercise="Lat Pulldown">Lat Pulldown</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Seated Cable Row">Seated Cable Row</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Deadlift">Deadlift</button></li>
                    </ul>
                </details>

                <details>
                    <summary>Shoulders</summary>
                    <ul>
                        <li><button type="button" class="add-exercise" data-exercise="Shoulder Press">Shoulder Press</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Lateral Raise">Lateral Raise</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Face Pull">Face Pull</button></li>
                    </ul>
                </details>

                <details>
                    <summary>Arms</summary>
                    <ul>
                        <li><button type="button" class="add-exercise" data-exercise="Bicep Curl">Bicep Curl</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Hammer Curl">Hammer Curl</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Tricep Bar Pushdown">Tricep Bar Pushdown</button></li>
                    </ul>
                </details>

                <details>
                    <summary>Legs</summary>
                    <ul>
                        <li><button type="button" class="add-exercise" data-exercise="Squat">Squat</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Leg Press">Leg Press</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Leg Curl">Leg Curl</button></li>
                    </ul>
                </details>

                <details>
                    <summary>Abs</summary>
                    <ul>
                        <li><button type="button" class="add-exercise" data-exercise="Crunch">Crunch</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Hanging Leg Raise">Hanging Leg Raise</button></li>
                        <li><button type="button" class="add-exercise" data-exercise="Plank">Plank</button></li>
                    </ul>
                </details>


                                
                <details>
                    <summary>My Custom Exercises</summary>
                    <div id="custom-workout-exercises">
                        <p>No custom exercises created yet.</p>
                    </div>
                </details>


                <br>

                <a href="exercises.php#custom-exercise">
                    + Create Custom Exercise
                </a>

            </details>

        </section>

        <!-- TEMPLATE -->

        <section id="template-section" class="form-section">

            <h2>Template</h2>

            <label for="template">Choose template:</label>

            <select id="template">
                <option value="">Select a template</option>
                <option value="push">Push Day</option>
                <option value="pull">Pull Day</option>
                <option value="legs">Leg Day</option>
                <option value="upper">Upper Body</option>
                <option value="lower">Lower Body</option>
            </select>

            <h3>Exercises</h3>

            <div id="template-exercise-list">
                <p>Select a template to see its exercises.</p>
            </div>

        </section>

        <hr>

        <section class="form-section">

            <button type="submit">Start Workout</button>

            <a href="workouts.php">Cancel</a>

        </section>

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

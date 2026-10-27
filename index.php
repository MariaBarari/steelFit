<?php
require_once __DIR__ . '/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SteelFit - Dashboard</title>

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

        <!-- WELCOME -->

        <section>

            <h1>Welcome back!</h1>

            <p>
                Here's an overview of your training progress.
            </p>

        </section>


        <hr>

        <!-- STATISTICS -->

        <section>
            <h2>Statistics</h2>

            <div>
                <h3>Workouts</h3>
                <p id="dashboard-total-workouts">0</p>
                <p>This month</p>
            </div>

            <div>
                <h3>Exercises</h3>
                <p id="dashboard-total-exercises">0</p>
                <p>Total logged</p>
            </div>

            <div>
                <h3>Total Sets</h3>
                <p id="dashboard-monthly-sets">0</p>
                <p>This month</p>
            </div>
        </section>

        <hr>

        <!-- PROGRESS -->

        <section>
            <h2>Progress</h2>

            <p>Track your progress for individual exercises.</p>

            <label for="exercise-select">Choose exercise:</label>

            <select id="exercise-select">
                <option value="">No exercises recorded yet</option>
            </select>

            <h3 id="progress-chart-title">Exercise Progress</h3>

            <p id="progress-chart-message">
                Complete and save workouts with recorded weights to see your progress.
            </p>

            <div class="progress-chart-container">
                <svg
                    id="progress-chart"
                    viewBox="0 0 700 350"
                    role="img"
                    aria-label="Exercise weight progress chart"
                >
                </svg>
            </div>
            <noscript>
                <p>
                    JavaScript is disabled. The exercise progress graph
                    requires JavaScript to display interactive data.
                </p>
            </noscript>
        </section>



        <hr>


        <!-- RECENT WORKOUTS -->

        <section>
            <h2>Recent Workouts</h2>

            <a href="workouts.php">View all workouts</a>

            <table border="1">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Workout</th>
                        <th>Exercises</th>
                        <th>Sets</th>
                    </tr>
                </thead>

                <tbody id="recent-workouts-body">
                    <tr>
                        <td colspan="4">Loading workouts...</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- QUICK ACTIONS -->

        <section>

            <h2>Quick Actions</h2>

            <a href="add-workout.php">
                Add Workout
            </a>

            <br>

            <a href="templates.php">
                View Templates
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



<script>
    console.log("SteelFit Dashboard loaded successfully.");
</script>

<script src="script.js"></script>
<script>
(async function loadDashboardFromDatabase() {
    const totalWorkouts = document.getElementById('dashboard-total-workouts');
    const totalExercises = document.getElementById('dashboard-total-exercises');
    const monthlySets = document.getElementById('dashboard-monthly-sets');
    const recentBody = document.getElementById('recent-workouts-body');
    const select = document.getElementById('exercise-select');
    const chart = document.getElementById('progress-chart');
    const chartTitle = document.getElementById('progress-chart-title');
    const chartMessage = document.getElementById('progress-chart-message');
    try {
        const response = await fetch('dashboard-data.php', { credentials: 'same-origin', cache: 'no-store' });
        const data = await response.json();
        if (!response.ok || !data.ok) throw new Error(data.message || 'Dashboard data could not be loaded.');
        if (totalWorkouts) totalWorkouts.textContent = data.totalWorkouts;
        if (totalExercises) totalExercises.textContent = data.totalExercises;
        if (monthlySets) monthlySets.textContent = data.monthlySets;
        if (recentBody) {
            recentBody.replaceChildren();
            if (!data.workouts.length) {
                const row = recentBody.insertRow(); const cell = row.insertCell();
                cell.colSpan = 4; cell.textContent = 'No completed workouts yet. Finish a workout and submit its check-in to see it here.';
            } else data.workouts.forEach(w => {
                const row = recentBody.insertRow();
                const date = new Date(w.workout_date + 'T12:00:00');
                [date.toLocaleDateString(undefined, {day:'2-digit', month:'short', year:'numeric'}), w.name, w.exercise_count, w.total_sets].forEach(value => {
                    const cell = row.insertCell(); cell.textContent = value;
                });
            });
        }
        if (select && chart && chartTitle && chartMessage) {
            const previous = select.value;
            const names = [...new Set(data.progress.map(p => p.exercise_name))].sort((a,b) => a.localeCompare(b));
            select.replaceChildren();
            if (!names.length) {
                const opt = new Option('No exercises recorded yet', ''); select.add(opt);
                chart.replaceChildren(); chartMessage.textContent = 'Complete and save workouts with recorded weights to see your progress.';
            } else {
                names.forEach(name => select.add(new Option(name, name)));
                if (names.includes(previous)) select.value = previous;
                const draw = () => {
                    const name = select.value;
                    const points = data.progress.filter(p => p.exercise_name === name && Number(p.max_weight) >= 0);
                    chart.replaceChildren(); chartTitle.textContent = name + ' Progress';
                    if (!points.length) { chartMessage.textContent = 'No weight records for this exercise yet.'; return; }
                    chartMessage.textContent = 'Maximum recorded weight per workout (kg).';
                    const ns = 'http://www.w3.org/2000/svg';
                    const weights = points.map(p => Number(p.max_weight));
                    const max = Math.max(...weights, 1); const min = Math.min(...weights, 0);
                    const left=55, right=675, top=25, bottom=285;
                    const x = i => points.length === 1 ? (left+right)/2 : left+i*(right-left)/(points.length-1);
                    const y = v => bottom-(v-min)/(max-min || 1)*(bottom-top);
                    const axis = document.createElementNS(ns,'path'); axis.setAttribute('d',`M ${left} ${top} V ${bottom} H ${right}`); axis.setAttribute('fill','none'); axis.setAttribute('stroke','currentColor'); axis.setAttribute('opacity','.5'); chart.appendChild(axis);
                    const line = document.createElementNS(ns,'polyline'); line.setAttribute('points', points.map((p,i)=>`${x(i)},${y(Number(p.max_weight))}`).join(' ')); line.setAttribute('fill','none'); line.setAttribute('stroke','#ff4d4d'); line.setAttribute('stroke-width','3'); chart.appendChild(line);
                    points.forEach((p,i)=>{ const dot=document.createElementNS(ns,'circle'); dot.setAttribute('cx',x(i)); dot.setAttribute('cy',y(Number(p.max_weight))); dot.setAttribute('r','4'); dot.setAttribute('fill','#ff4d4d'); chart.appendChild(dot); });
                };
                select.onchange = draw; draw();
            }
        }
    } catch (error) {
        console.error(error);
        if (recentBody) { recentBody.replaceChildren(); const row=recentBody.insertRow(); const cell=row.insertCell(); cell.colSpan=4; cell.textContent='Could not load dashboard data from MySQL. Check dashboard-data.php and the database connection.'; }
        if (chartMessage) chartMessage.textContent = 'Could not load progress data from MySQL.';
    }
})();
</script>

</body>

</html>
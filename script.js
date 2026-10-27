// =====================================================
// STEELFIT JAVASCRIPT
// =====================================================


class Exercise {
    constructor(name, muscle, description) {
        this.name = name;
        this.muscle = muscle;
        this.description = description;
    }

    displayDetails() {
        console.log("Exercise Details:");
        console.log(`Name:${this.name}`, this.name);
        console.log("Muscle Group:", this.muscle);
        console.log("Description:", this.description);
    }
}


// =====================================================
// ADD WORKOUT PAGE
// =====================================================

const workoutType = document.getElementById("workout-type");

if (workoutType && document.getElementById("workout-form")) {

    const newWorkoutSection = document.getElementById("new-workout");
    const templateSection = document.getElementById("template-section");
    const template = document.getElementById("template");
    const templateExerciseList = document.getElementById("template-exercise-list");
    const exerciseList = document.getElementById("exercise-list");
    const noExercises = document.getElementById("no-exercises");
    
    const workoutForm = document.getElementById("workout-form");
    const workoutNameInput = document.getElementById("workout-name");


    const templates = {

        push: [
            "Bench Press",
            "Incline Dumbbell Press",
            "Cable Fly",
            "Shoulder Press",
            "Lateral Raise",
            "Tricep Pushdown"
        ],

        pull: [
            "Lat Pulldown",
            "Seated Cable Row",
            "Deadlift",
            "Face Pull",
            "Bicep Curl",
            "Hammer Curl"
        ],

        legs: [
            "Squat",
            "Leg Press",
            "Leg Curl"
        ],

        upper: [
            "Bench Press",
            "Lat Pulldown",
            "Shoulder Press",
            "Seated Cable Row",
            "Bicep Curl",
            "Tricep Pushdown"
        ],

        lower: [
            "Squat",
            "Leg Press",
            "Leg Curl",
            "Hanging Leg Raise",
            "Plank"
        ]

    };


    function updateStartType() {

        if (workoutType.value === "new") {

            newWorkoutSection.style.display = "block";
            templateSection.style.display = "none";

            if (workoutNameInput) {
                workoutNameInput.required = true;
            }

        } else {

            newWorkoutSection.style.display = "none";
            templateSection.style.display = "block";

            if (workoutNameInput) {
                workoutNameInput.required = false;
            }
        }
    }


    updateStartType();

    workoutType.addEventListener("change", updateStartType);

    
    // Load custom exercises saved on the Exercises page.
    const customWorkoutExercises = document.getElementById(
        "custom-workout-exercises"
    );

    if (customWorkoutExercises) {
        const customExercises = JSON.parse(
            localStorage.getItem("customExercises") || "[]"
        );

        customWorkoutExercises.innerHTML = "";

        if (customExercises.length === 0) {
            customWorkoutExercises.textContent =
                "No custom exercises created yet.";
        } else {
            const muscleGroups = [
                "chest",
                "back",
                "shoulders",
                "arms",
                "legs",
                "abs"
            ];

            muscleGroups.forEach(function (muscle) {
                const exercisesForMuscle = customExercises.filter(
                    function (exercise) {
                        return exercise.muscle === muscle;
                    }
                );

                if (exercisesForMuscle.length === 0) {
                    return;
                }

                const heading = document.createElement("h4");
                heading.textContent =
                    muscle.charAt(0).toUpperCase() + muscle.slice(1);

                customWorkoutExercises.appendChild(heading);

                const list = document.createElement("ul");

                exercisesForMuscle.forEach(function (exercise) {
                    const item = document.createElement("li");
                    const button = document.createElement("button");

                    button.type = "button";
                    button.className = "add-exercise";
                    button.dataset.exercise = exercise.name;
                    button.textContent = exercise.name;

                    item.appendChild(button);
                    list.appendChild(item);
                });

                customWorkoutExercises.appendChild(list);
            });
        }
    }



    // Add exercises to a new workout

    
    // Add exercises to a new workout.
    // Event delegation supports both built-in and custom exercises.

    const exercisePicker = document.getElementById("new-workout");

    exercisePicker.addEventListener("click", function (event) {

        const button = event.target.closest(".add-exercise");

        if (!button || !exercisePicker.contains(button)) {
            return;
        }

        const exerciseName = button.dataset.exercise;

        const existingExercises =
            exerciseList.querySelectorAll(".workout-exercise");

        let alreadyAdded = false;

        existingExercises.forEach(function (exercise) {
            if (exercise.dataset.exercise === exerciseName) {
                alreadyAdded = true;
            }
        });

        if (alreadyAdded) {
            alert("This exercise has already been added.");
            return;
        }

        const exercise = document.createElement("div");

        exercise.className = "workout-exercise";
        exercise.dataset.exercise = exerciseName;

        exercise.innerHTML = `
            <h4>${exerciseName}</h4>
            <button type="button" class="remove-exercise">
                Remove
            </button>
        `;

        exerciseList.appendChild(exercise);
        noExercises.style.display = "none";

        const removeButton =
            exercise.querySelector(".remove-exercise");

        removeButton.addEventListener("click", function () {

            exercise.remove();

            if (
                exerciseList.querySelectorAll(".workout-exercise").length === 0
            ) {
                noExercises.style.display = "block";
            }

        });

    });



    // Show exercises when a template is selected

    if (template) {

        template.addEventListener("change", function () {

            const selectedTemplate = template.value;

            templateExerciseList.innerHTML = "";


            if (!selectedTemplate) {

                templateExerciseList.innerHTML =
                    "<p>Select a template to see its exercises.</p>";

                return;
            }


            templates[selectedTemplate].forEach(function (exerciseName) {

                const exercise = document.createElement("p");

                exercise.textContent = exerciseName;

                templateExerciseList.appendChild(exercise);

            });

        });

    }


    // Start workout

    workoutForm.addEventListener("submit", function (event) {

        event.preventDefault();


        let exercises = [];
        let workoutName = "";


        if (workoutType.value === "new") {

            workoutName = workoutNameInput.value.trim();


            document
                .querySelectorAll("#exercise-list .workout-exercise")
                .forEach(function (exercise) {

                    exercises.push({
                        name: exercise.dataset.exercise,

                        sets: [
                            {
                                weight: "",
                                reps: "",
                                completed: false
                            }
                        ]
                    });

                });


            if (exercises.length === 0) {

                alert("Please add at least one exercise.");

                return;
            }


        } else {

            const selectedTemplate = template.value;


            if (!selectedTemplate) {

                alert("Please choose a template.");

                return;
            }


            workoutName =
                template.options[template.selectedIndex].text;


            exercises =
                templates[selectedTemplate].map(function (exerciseName) {

                    return {
                        name: exerciseName,

                        sets: [
                            {
                                weight: "",
                                reps: "",
                                completed: false
                            }
                        ]
                    };

                });

        }


        const workout = {

            name: workoutName,
            startedAt: Date.now(),
            exercises: exercises

        };


        localStorage.setItem(
            "activeWorkout",
            JSON.stringify(workout)
        );


        window.location.href = "active-workout.php";

    });

}


// =====================================================
// ACTIVE WORKOUT PAGE
// =====================================================

const activeExercises =
    document.getElementById("active-exercises");


if (activeExercises) {

    const workoutData =
        localStorage.getItem("activeWorkout");


    if (!workoutData) {

        activeExercises.innerHTML =
            "<p>No active workout found.</p>";

    } else {

        const workout =
            JSON.parse(workoutData);


        document.getElementById("active-workout-name").textContent =
            workout.name;


        workout.exercises.forEach(function (exercise, exerciseIndex) {

            const exerciseSection =
                document.createElement("section");

            exerciseSection.className =
                "active-exercise";


            const exerciseTitle =
                document.createElement("h2");

            exerciseTitle.textContent =
                exercise.name;


            exerciseSection.appendChild(exerciseTitle);


            const table =
                document.createElement("table");

            table.className =
                "active-workout-table";


            const tableHead =
                document.createElement("thead");


            tableHead.innerHTML = `
                <tr>
                    <th>Set</th>
                    <th>Reps</th>
                    <th>Weight</th>
                </tr>
            `;


            table.appendChild(tableHead);


            const tableBody =
                document.createElement("tbody");


            exercise.sets.forEach(function (set, setIndex) {

                const row =
                    document.createElement("tr");


                if (set.completed) {
                    row.classList.add("completed");
                }


                row.innerHTML = `
                    <td>
                        <button
                            type="button"
                            class="set-button"
                            aria-label="Complete set ${setIndex + 1}"
                        >
                            ${set.completed ? "✓ " : ""}${setIndex + 1}
                        </button>
                    </td>

                    <td>
                        <input
                            type="number"
                            class="set-reps"
                            min="1"
                            step="1"
                            placeholder="Reps"
                            value="${set.reps}"
                        >
                    </td>

                    <td>
                        <input
                            type="number"
                            class="set-weight"
                            min="0"
                            step="0.5"
                            placeholder="kg"
                            value="${set.weight}"
                        >
                    </td>
                `;


                tableBody.appendChild(row);


                const setButton =
                    row.querySelector(".set-button");

                const repsInput =
                    row.querySelector(".set-reps");

                const weightInput =
                    row.querySelector(".set-weight");


                // Complete / uncomplete a set

                setButton.addEventListener("click", function () {

                    set.completed = !set.completed;

                    row.classList.toggle(
                        "completed",
                        set.completed
                    );


                    setButton.textContent =
                        set.completed
                            ? "✓ " + (setIndex + 1)
                            : String(setIndex + 1);


                    saveActiveWorkout(workout);

                });


                // Save reps

                repsInput.addEventListener("input", function () {

                    set.reps =
                        repsInput.value;

                    saveActiveWorkout(workout);

                });


                // Save weight

                weightInput.addEventListener("input", function () {

                    set.weight =
                        weightInput.value;

                    saveActiveWorkout(workout);

                });

            });


            table.appendChild(tableBody);

            exerciseSection.appendChild(table);


            // Add Set button

            const addSetButton =
                document.createElement("button");

            addSetButton.type = "button";
            addSetButton.className = "add-set-button";
            addSetButton.textContent = "+ Add Set";


            addSetButton.addEventListener("click", function () {

                exercise.sets.push({

                    weight: "",
                    reps: "",
                    completed: false

                });


                saveActiveWorkout(workout);

                renderActiveWorkout(workout);

            });


            exerciseSection.appendChild(addSetButton);


            activeExercises.appendChild(exerciseSection);

        });

    }


    const finishButton =
        document.getElementById("finish-workout");


    finishButton.addEventListener("click", function () {

        const workout =
            JSON.parse(localStorage.getItem("activeWorkout"));


        if (!workout) {

            alert("No active workout found.");

            return;
        }


        const incompleteSet =
            workout.exercises.some(function (exercise) {

                return exercise.sets.some(function (set) {
                    return !set.completed;
                });

            });


        if (incompleteSet) {

            alert("Complete all sets before finishing the workout.");

            return;
        }


        localStorage.setItem(
            "workoutForCheckin",
            JSON.stringify(workout)
        );


        window.location.href =
            "workout-checkin.php";

    });

}


// =====================================================
// ACTIVE WORKOUT HELPERS
// =====================================================

function saveActiveWorkout(workout) {

    localStorage.setItem(
        "activeWorkout",
        JSON.stringify(workout)
    );

}


function renderActiveWorkout(workout) {

    const activeExercises =
        document.getElementById("active-exercises");


    if (!activeExercises) {
        return;
    }


    activeExercises.innerHTML = "";


    document.getElementById("active-workout-name").textContent =
        workout.name;


    workout.exercises.forEach(function (exercise, exerciseIndex) {

        const exerciseSection =
            document.createElement("section");

        exerciseSection.className =
            "active-exercise";


        const exerciseTitle =
            document.createElement("h2");

        exerciseTitle.textContent =
            exercise.name;


        exerciseSection.appendChild(exerciseTitle);


        const table =
            document.createElement("table");

        table.className =
            "active-workout-table";


        table.innerHTML = `
            <thead>
                <tr>
                    <th>Set</th>
                    <th>Reps</th>
                    <th>Weight</th>
                </tr>
            </thead>
        `;


        const tableBody =
            document.createElement("tbody");


        exercise.sets.forEach(function (set, setIndex) {

            const row =
                document.createElement("tr");


            if (set.completed) {
                row.classList.add("completed");
            }


            row.innerHTML = `
                <td>
                    <button
                        type="button"
                        class="set-button"
                        aria-label="Complete set ${setIndex + 1}"
                    >
                        ${set.completed ? "✓ " : ""}${setIndex + 1}
                    </button>
                </td>

                <td>
                    <input
                        type="number"
                        class="set-reps"
                        min="1"
                        step="1"
                        placeholder="Reps"
                        value="${set.reps}"
                    >
                </td>

                <td>
                    <input
                        type="number"
                        class="set-weight"
                        min="0"
                        step="0.5"
                        placeholder="kg"
                        value="${set.weight}"
                    >
                </td>
            `;


            tableBody.appendChild(row);


            const setButton =
                row.querySelector(".set-button");

            const repsInput =
                row.querySelector(".set-reps");

            const weightInput =
                row.querySelector(".set-weight");


            setButton.addEventListener("click", function () {

                set.completed = !set.completed;

                row.classList.toggle(
                    "completed",
                    set.completed
                );


                setButton.textContent =
                    set.completed
                        ? "✓ " + (setIndex + 1)
                        : String(setIndex + 1);


                saveActiveWorkout(workout);

            });


            repsInput.addEventListener("input", function () {

                set.reps =
                    repsInput.value;

                saveActiveWorkout(workout);

            });


            weightInput.addEventListener("input", function () {

                set.weight =
                    weightInput.value;

                saveActiveWorkout(workout);

            });

        });


        table.appendChild(tableBody);

        exerciseSection.appendChild(table);


        const addSetButton =
            document.createElement("button");

        addSetButton.type = "button";
        addSetButton.className = "add-set-button";
        addSetButton.textContent = "+ Add Set";


        addSetButton.addEventListener("click", function () {

            exercise.sets.push({

                weight: "",
                reps: "",
                completed: false

            });


            saveActiveWorkout(workout);

            renderActiveWorkout(workout);

        });


        exerciseSection.appendChild(addSetButton);

        activeExercises.appendChild(exerciseSection);

    });

}


// =====================================================
// WORKOUT CHECK-IN PAGE
// =====================================================

const checkinForm = document.getElementById("checkin-form");

if (checkinForm) {
    const workoutData = localStorage.getItem("workoutForCheckin");

    if (workoutData) {
        const workout = JSON.parse(workoutData);
        document.getElementById("checkin-workout-name").textContent =
            "Workout: " + workout.name;
    }

    checkinForm.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!workoutData) {
            alert("No workout found.");
            return;
        }

        const workout = JSON.parse(localStorage.getItem("workoutForCheckin"));
        const completedAt = Date.now();
        const startedAt = workout.startedAt || completedAt;

        workout.checkin = {
            feeling: document.getElementById("feeling").value,
            energy: document.getElementById("energy").value,
            sleep: document.getElementById("sleep").value,
            calories: document.getElementById("calories").value,
            notes: document.getElementById("notes").value
        };

        workout.completed = true;
        workout.completedAt = completedAt;
        workout.durationMinutes = Math.max(1, Math.round((completedAt - startedAt) / 60000));

        fetch("save-workout.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(workout)
        }).then(async function (response) {
            const result = await response.json().catch(function () { return {}; });
            if (!response.ok || !result.ok) {
                throw new Error(result.message || "Antrenamentul nu a putut fi salvat.");
            }
            localStorage.removeItem("activeWorkout");
            localStorage.removeItem("workoutForCheckin");
            window.location.href = "workouts.php";
        }).catch(function (error) {
            alert(error.message || "Eroare la salvarea antrenamentului. Verifică conexiunea la baza de date.");
        });
    });
}


// =====================================================
// WORKOUT HISTORY PAGE
// =====================================================

const historyBody = document.getElementById("workout-history-body");

if (historyBody && !historyBody.querySelector("[data-workout-id]")) {
    const historyTypeFilter = document.getElementById("workout-type");
    const historyMonthFilter = document.getElementById("workout-month");
    const history = JSON.parse(localStorage.getItem("workoutHistory") || "[]");

    function formatWorkoutDate(workout) {
        if (!workout.completedAt) return "—";

        return new Date(workout.completedAt).toLocaleDateString(undefined, {
            day: "2-digit",
            month: "short",
            year: "numeric"
        });
    }

    function countWorkoutSets(workout) {
        return (workout.exercises || []).reduce(function (total, exercise) {
            return total + (exercise.sets || []).length;
        }, 0);
    }

    function createDetailsRow(workout, colSpan) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");

        cell.colSpan = colSpan;
        cell.className = "workout-details-cell";

        const heading = document.createElement("h3");
        heading.textContent = "Workout details";
        cell.appendChild(heading);

        (workout.exercises || []).forEach(function (exercise) {
            const title = document.createElement("h4");
            title.textContent = exercise.name;
            cell.appendChild(title);

            const table = document.createElement("table");
            table.className = "workout-details-table";
            table.innerHTML =
                "<thead><tr><th>Set</th><th>Reps</th><th>Weight</th><th>Status</th></tr></thead>";

            const body = document.createElement("tbody");

            (exercise.sets || []).forEach(function (set, index) {
                const setRow = document.createElement("tr");

                [
                    String(index + 1),
                    set.reps || "—",
                    set.weight ? set.weight + " kg" : "—",
                    set.completed ? "Completed" : "Not completed"
                ].forEach(function (value) {
                    const td = document.createElement("td");
                    td.textContent = value;
                    setRow.appendChild(td);
                });

                body.appendChild(setRow);
            });

            table.appendChild(body);
            cell.appendChild(table);
        });

        if (workout.checkin) {
            const checkinHeading = document.createElement("h3");
            checkinHeading.textContent = "Check-in";
            cell.appendChild(checkinHeading);

            const checkinList = document.createElement("ul");

            const checkinItems = [
                ["Workout feeling", workout.checkin.feeling],
                ["Energy", workout.checkin.energy ? workout.checkin.energy + "/10" : "—"],
                ["Sleep", workout.checkin.sleep ? workout.checkin.sleep + " hours" : "—"],
                ["Estimated calories", workout.checkin.calories || "—"],
                ["Notes", workout.checkin.notes || "None"]
            ];

            checkinItems.forEach(function (item) {
                const li = document.createElement("li");
                li.textContent = item[0] + ": " + (item[1] || "—");
                checkinList.appendChild(li);
            });

            cell.appendChild(checkinList);
        }

        row.appendChild(cell);
        return row;
    }

    function renderWorkoutHistory() {
        historyBody.innerHTML = "";

        const selectedType = historyTypeFilter
            ? historyTypeFilter.value
            : "All";

        const selectedMonth = historyMonthFilter
            ? historyMonthFilter.value
            : "All";

        const filteredHistory = history.filter(function (workout) {
            const matchesType =
                selectedType === "All" || workout.name === selectedType;

            let matchesMonth = true;

            if (selectedMonth !== "All" && workout.completedAt) {
                const date = new Date(workout.completedAt);

                const monthKey =
                    date.getFullYear() +
                    "-" +
                    String(date.getMonth() + 1).padStart(2, "0");

                matchesMonth = monthKey === selectedMonth;
            } else if (selectedMonth !== "All" && !workout.completedAt) {
                matchesMonth = false;
            }

            return matchesType && matchesMonth;
        });

        if (filteredHistory.length === 0) {
            const row = document.createElement("tr");
            const cell = document.createElement("td");

            cell.colSpan = 6;
            cell.textContent = history.length === 0
                ? "No completed workouts yet. Finish a workout and save its check-in to see it here."
                : "No workouts match these filters.";

            row.appendChild(cell);
            historyBody.appendChild(row);
            return;
        }

        filteredHistory.forEach(function (workout) {
            const originalIndex = history.indexOf(workout);
            const row = document.createElement("tr");

            const values = [
                formatWorkoutDate(workout),
                workout.name || "Workout",
                (workout.exercises || []).length,
                countWorkoutSets(workout),
                workout.durationMinutes
                    ? workout.durationMinutes + " min"
                    : "—"
            ];

            values.forEach(function (value) {
                const cell = document.createElement("td");
                cell.textContent = value;
                row.appendChild(cell);
            });

            const actionsCell = document.createElement("td");

            const detailsButton = document.createElement("button");
            detailsButton.type = "button";
            detailsButton.textContent = "View details";
            detailsButton.className = "history-details-button";
            actionsCell.appendChild(detailsButton);

            const deleteButton = document.createElement("button");
            deleteButton.type = "button";
            deleteButton.textContent = "Delete";
            deleteButton.className = "history-delete-button";
            actionsCell.appendChild(deleteButton);

            row.appendChild(actionsCell);
            historyBody.appendChild(row);

            let detailsRow = null;

            detailsButton.addEventListener("click", function () {
                if (detailsRow) {
                    detailsRow.remove();
                    detailsRow = null;
                    detailsButton.textContent = "View details";
                } else {
                    detailsRow = createDetailsRow(workout, 6);
                    row.insertAdjacentElement("afterend", detailsRow);
                    detailsButton.textContent = "Hide details";
                }
            });

            deleteButton.addEventListener("click", function () {
                if (!confirm("Delete this workout from your history?")) {
                    return;
                }

                history.splice(originalIndex, 1);

                localStorage.setItem(
                    "workoutHistory",
                    JSON.stringify(history)
                );

                renderWorkoutHistory();
            });
        });
    }

    if (historyTypeFilter) {
        historyTypeFilter.addEventListener("change", renderWorkoutHistory);
    }

    if (historyMonthFilter) {
        historyMonthFilter.addEventListener("change", renderWorkoutHistory);
    }

    renderWorkoutHistory();
}


 // =====================================================
 // WORKOUTS PAGE - STATISTICS
 // =====================================================

const totalWorkoutsElement = document.getElementById("total-workouts");
const monthlyWorkoutsElement = document.getElementById("monthly-workouts");
const averageDurationElement = document.getElementById("average-duration");

if (
    totalWorkoutsElement &&
    monthlyWorkoutsElement &&
    averageDurationElement
) {
    const history = JSON.parse(
        localStorage.getItem("workoutHistory") || "[]"
    );

    const now = new Date();

    // Count workouts completed in the current month.
    const workoutsThisMonth = history.filter(function (workout) {
        if (!workout.completedAt) {
            return false;
        }

        const date = new Date(workout.completedAt);

        return (
            date.getMonth() === now.getMonth() &&
            date.getFullYear() === now.getFullYear()
        );
    });

    // Calculate average duration using workouts with a recorded duration.
    const workoutsWithDuration = history.filter(function (workout) {
        return (
            workout.completedAt &&
            Number.isFinite(Number(workout.durationMinutes)) &&
            Number(workout.durationMinutes) > 0
        );
    });

    let averageDuration = 0;

    if (workoutsWithDuration.length > 0) {
        const totalDuration = workoutsWithDuration.reduce(
            function (total, workout) {
                return total + Number(workout.durationMinutes);
            },
            0
        );

        averageDuration = Math.round(
            totalDuration / workoutsWithDuration.length
        );
    }

    // Display the statistics.
    totalWorkoutsElement.textContent = history.length;
    monthlyWorkoutsElement.textContent = workoutsThisMonth.length;
    averageDurationElement.textContent = averageDuration + " min";
}


 // =====================================================
 // EXERCISES PAGE - CUSTOM EXERCISES
 // =====================================================

const customExerciseForm = document.getElementById("custom-exercise-form");

if (customExerciseForm) {
    const customNameInput = document.getElementById("custom-name");
    const customMuscleInput = document.getElementById("custom-muscle");
    const customDescriptionInput = document.getElementById("custom-description");
    const customExerciseMessage = document.getElementById("custom-exercise-message");
    const customExercisesList = document.getElementById("custom-exercises-list");

    const builtInExerciseNames = [
        "Bench Press",
        "Incline Dumbbell Press",
        "Cable Fly",
        "Lat Pulldown",
        "Seated Cable Row",
        "Deadlift",
        "Shoulder Press",
        "Lateral Raise",
        "Face Pull",
        "Bicep Curl",
        "Hammer Curl",
        "Preacher Curl",
        "Tricep Bar Pushdown",
        "Close Grip Bench Press",
        "Squat",
        "Leg Press",
        "Leg Curl",
        "Crunch",
        "Hanging Leg Raise",
        "Plank"
    ];

    function getCustomExercises() {
        return JSON.parse(
            localStorage.getItem("customExercises") || "[]"
        );
    }

    function renderCustomExercises() {
        const exercises = getCustomExercises();

        customExercisesList.innerHTML = "";

        if (exercises.length === 0) {
            customExercisesList.textContent =
                "No custom exercises created yet.";
            return;
        }

        exercises.forEach(function (exercise, index) {
            const card = document.createElement("div");
            card.className = "custom-exercise-card";

            const title = document.createElement("h4");
            title.textContent = exercise.name;

            const muscle = document.createElement("p");
            muscle.textContent =
                "Muscle group: " +
                exercise.muscle.charAt(0).toUpperCase() +
                exercise.muscle.slice(1);

            const description = document.createElement("p");
            description.textContent =
                exercise.description || "No description provided.";

            const deleteButton = document.createElement("button");
            deleteButton.type = "button";
            deleteButton.textContent = "Delete";

            deleteButton.addEventListener("click", function () {
                if (!confirm("Delete " + exercise.name + "?")) {
                    return;
                }

                const updatedExercises = getCustomExercises();
                updatedExercises.splice(index, 1);

                localStorage.setItem(
                    "customExercises",
                    JSON.stringify(updatedExercises)
                );

                renderCustomExercises();
            });

            card.appendChild(title);
            card.appendChild(muscle);
            card.appendChild(description);
            card.appendChild(deleteButton);

            customExercisesList.appendChild(card);
        });
    }

    customExerciseForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const name = customNameInput.value.trim();
        const muscle = customMuscleInput.value;
        const description = customDescriptionInput.value.trim();

        const exercise = new Exercise(name, muscle, description);
        exercise.displayDetails(); 

        if (name.length < 3) {
            customExerciseMessage.textContent =
                "Exercise name must contain at least 3 characters.";
            return;
        }

        const customExercises = getCustomExercises();

        const nameExists =
            builtInExerciseNames.some(function (builtInName) {
                return builtInName.toLowerCase() === name.toLowerCase();
            }) ||
            customExercises.some(function (exercise) {
                return exercise.name.toLowerCase() === name.toLowerCase();
            });

        if (nameExists) {
            customExerciseMessage.textContent =
                "An exercise with this name already exists.";
            return;
        }

        customExercises.push(exercise);

        localStorage.setItem(
            "customExercises",
            JSON.stringify(customExercises)
        );

        customExerciseForm.reset();

        customExerciseMessage.textContent =
            "Exercise created successfully!";

        renderCustomExercises();
    });

    renderCustomExercises();
}



// =====================================================
// DASHBOARD STATISTICS
// =====================================================

const dashboardWorkouts = document.getElementById("dashboard-total-workouts");
const dashboardExercises = document.getElementById("dashboard-total-exercises");
const dashboardSets = document.getElementById("dashboard-monthly-sets");

if (dashboardWorkouts && dashboardExercises && dashboardSets) {
    const history = JSON.parse(
        localStorage.getItem("workoutHistory") || "[]"
    );

    const now = new Date();

    const thisMonth = history.filter(function (workout) {
        if (!workout.completedAt) {
            return false;
        }

        const date = new Date(workout.completedAt);

        return (
            date.getMonth() === now.getMonth() &&
            date.getFullYear() === now.getFullYear()
        );
    });

    const totalExercises = history.reduce(function (total, workout) {
        return total + (workout.exercises || []).length;
    }, 0);

    const monthlySets = thisMonth.reduce(function (total, workout) {
        return total + (workout.exercises || []).reduce(
            function (exerciseTotal, exercise) {
                return exerciseTotal + (exercise.sets || []).length;
            },
            0
        );
    }, 0);

    dashboardWorkouts.textContent = history.length;
    dashboardExercises.textContent = totalExercises;
    dashboardSets.textContent = monthlySets;    
}

// =====================================================
// DASHBOARD - RECENT WORKOUTS
// =====================================================

const recentWorkoutsBody = document.getElementById(
    "recent-workouts-body"
);

if (recentWorkoutsBody) {
    const history = JSON.parse(
        localStorage.getItem("workoutHistory") || "[]"
    );

    const recentWorkouts = history
        .filter(function (workout) {
            return workout.completedAt;
        })
        .sort(function (a, b) {
            return Number(b.completedAt) - Number(a.completedAt);
        })
        .slice(0, 4);

    recentWorkoutsBody.innerHTML = "";

    if (recentWorkouts.length === 0) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");

        cell.colSpan = 4;
        cell.textContent =
            "No completed workouts yet. Save a workout to see it here.";

        row.appendChild(cell);
        recentWorkoutsBody.appendChild(row);
    } else {
        recentWorkouts.forEach(function (workout) {
            const row = document.createElement("tr");

            const dateCell = document.createElement("td");
            dateCell.textContent = new Date(
                Number(workout.completedAt)
            ).toLocaleDateString(undefined, {
                day: "2-digit",
                month: "short",
                year: "numeric"
            });

            const nameCell = document.createElement("td");
            nameCell.textContent = workout.name || "Workout";

            const exercisesCell = document.createElement("td");
            exercisesCell.textContent =
                (workout.exercises || []).length;

            const setsCell = document.createElement("td");

            const totalSets = (workout.exercises || []).reduce(
                function (total, exercise) {
                    return total + (exercise.sets || []).length;
                },
                0
            );

            setsCell.textContent = totalSets;

            row.appendChild(dateCell);
            row.appendChild(nameCell);
            row.appendChild(exercisesCell);
            row.appendChild(setsCell);

            recentWorkoutsBody.appendChild(row);
        });
    }
}


// =====================================================
// DASHBOARD - EXERCISE PROGRESS GRAPH
// =====================================================

const exerciseSelect = document.getElementById("exercise-select");
const progressChart = document.getElementById("progress-chart");
const progressChartTitle = document.getElementById("progress-chart-title");
const progressChartMessage = document.getElementById("progress-chart-message");

if (
    exerciseSelect &&
    progressChart &&
    progressChartTitle &&
    progressChartMessage
) {
    const history = JSON.parse(
        localStorage.getItem("workoutHistory") || "[]"
    );

    // Get all exercises from the Exercises page,
    // including exercises that have not been logged yet.
    const exerciseNames = [];

    function addExerciseName(name) {
        name = (name || "").trim();

        if (
            name &&
            !exerciseNames.some(function (existingName) {
                return existingName.toLowerCase() === name.toLowerCase();
            })
        ) {
            exerciseNames.push(name);
        }
    }

    // Read exercise names from the Exercises page HTML.
    // The Dashboard can do this only if the exercise library
    // is also present on the current page, so use the shared
    // list below for the existing built-in exercises instead.
    const builtInExercises = [
        "Bench Press",
        "Incline Dumbbell Press",
        "Cable Fly",
        "Lat Pulldown",
        "Seated Cable Row",
        "Deadlift",
        "Shoulder Press",
        "Lateral Raise",
        "Face Pull",
        "Bicep Curl",
        "Hammer Curl",
        "Preacher Curl",
        "Tricep Bar Pushdown",
        "Close Grip Bench Press",
        "Squat",
        "Leg Press",
        "Leg Curl",
        "Crunch",
        "Hanging Leg Raise",
        "Plank"
    ];

    builtInExercises.forEach(addExerciseName);

    // Include custom exercises saved in localStorage.
    const customExercises = JSON.parse(
        localStorage.getItem("customExercises") || "[]"
    );

    customExercises.forEach(function (exercise) {
        addExerciseName(
            typeof exercise === "string" ? exercise : exercise.name
        );
    });

    // Include exercises in completed workouts, so older or
    // custom workout data is not lost.
    history.forEach(function (workout) {
        (workout.exercises || []).forEach(function (exercise) {
            addExerciseName(exercise.name);
        });
    });

    exerciseNames.sort(function (a, b) {
        return a.localeCompare(b);
    });


    exerciseNames.sort(function (a, b) {
        return a.localeCompare(b);
    });

    // Populate the dropdown dynamically.
    exerciseSelect.innerHTML = "";

    if (exerciseNames.length === 0) {
        const option = document.createElement("option");
        option.value = "";
        option.textContent = "No exercises recorded yet";

        exerciseSelect.appendChild(option);
        exerciseSelect.disabled = true;
    } else {
        exerciseSelect.disabled = false;

        exerciseNames.forEach(function (name) {
            const option = document.createElement("option");

            option.value = name;
            option.textContent = name;

            exerciseSelect.appendChild(option);
        });
    }

    function drawEmptyProgressChart() {
        const namespace = "http://www.w3.org/2000/svg";

        function addElement(tag, attributes, textContent) {
            const element = document.createElementNS(namespace, tag);

            Object.keys(attributes).forEach(function (attribute) {
                element.setAttribute(attribute, attributes[attribute]);
            });

            if (textContent !== undefined) {
                element.textContent = textContent;
            }

            progressChart.appendChild(element);
        }

        const width = 700;
        const height = 350;

        const left = 70;
        const right = 30;
        const top = 25;
        const bottom = 65;

        const chartWidth = width - left - right;
        const chartHeight = height - top - bottom;

        // Empty chart with placeholder axes and grid.
        for (let i = 0; i <= 4; i++) {
            const y = top + (chartHeight * i) / 4;

            addElement("line", {
                x1: left,
                y1: y,
                x2: width - right,
                y2: y,
                stroke: "#cccccc",
                "stroke-dasharray": "4 4"
            });

            addElement("text", {
                x: left - 12,
                y: y + 4,
                "text-anchor": "end",
                "font-size": "12",
                fill: "currentColor"
            }, "—");
        }

        addElement("line", {
            x1: left,
            y1: top,
            x2: left,
            y2: height - bottom,
            stroke: "currentColor"
        });

        addElement("line", {
            x1: left,
            y1: height - bottom,
            x2: width - right,
            y2: height - bottom,
            stroke: "currentColor"
        });

        // Placeholder dates: no fake measurements or progress line.
        ["Date", "Date", "Date", "Date", "Date", "Date"].forEach(
            function (label, index) {
                const x = left + (index / 5) * chartWidth;

                addElement("text", {
                    x: x,
                    y: height - bottom + 22,
                    "text-anchor": "middle",
                    "font-size": "11",
                    fill: "currentColor"
                }, label);
            }
        );
    }


    function drawProgressChart() {
        progressChart.innerHTML = "";

        const selectedExercise = exerciseSelect.value;

        progressChartTitle.textContent = selectedExercise
            ? selectedExercise + " Progress"
            : "Exercise Progress";

        if (!selectedExercise) {
            progressChartMessage.textContent =
                "Complete and save a workout to see your progress.";
            return;
        }

        // Find the maximum recorded weight for this exercise
        // in each completed workout.
        const progressData = [];

        history.forEach(function (workout) {
            if (!workout.completedAt) {
                return;
            }

            const matchingExercise = (workout.exercises || []).find(
                function (exercise) {
                    return (exercise.name || "").toLowerCase() ===
                        selectedExercise.toLowerCase();
                }
            );

            if (!matchingExercise) {
                return;
            }

            const weights = (matchingExercise.sets || [])
                .map(function (set) {
                    return Number(set.weight);
                })
                .filter(function (weight) {
                    return Number.isFinite(weight) && weight > 0;
                });

            if (weights.length === 0) {
                return;
            }

            progressData.push({
                date: Number(workout.completedAt),
                weight: Math.max.apply(null, weights)
            });
        });

        progressData.sort(function (a, b) {
            return a.date - b.date;
        });

        if (progressData.length === 0) {
            progressChartMessage.textContent =
                "No recorded weights for this exercise yet. Enter weights during a workout and save it.";

            drawEmptyProgressChart();
            return;
        }


        progressChartMessage.textContent =
            "Showing the highest recorded weight for each saved workout.";

        const namespace = "http://www.w3.org/2000/svg";

        function addSvgElement(tag, attributes, textContent) {
            const element = document.createElementNS(namespace, tag);

            Object.keys(attributes).forEach(function (attribute) {
                element.setAttribute(attribute, attributes[attribute]);
            });

            if (textContent !== undefined) {
                element.textContent = textContent;
            }

            progressChart.appendChild(element);

            return element;
        }

        const width = 700;
        const height = 350;

        const left = 70;
        const right = 25;
        const top = 25;
        const bottom = 65;

        const chartWidth = width - left - right;
        const chartHeight = height - top - bottom;

        const weights = progressData.map(function (point) {
            return point.weight;
        });

        const minimumWeight = Math.max(
            0,
            Math.floor(Math.min.apply(null, weights) - 5)
        );

        const maximumWeight =
            Math.ceil(Math.max.apply(null, weights) + 5);

        const weightRange = Math.max(
            1,
            maximumWeight - minimumWeight
        );

        function getX(index) {
            if (progressData.length === 1) {
                return left + chartWidth / 2;
            }

            return left +
                (index / (progressData.length - 1)) * chartWidth;
        }

        function getY(weight) {
            return top +
                ((maximumWeight - weight) / weightRange) * chartHeight;
        }

        // Horizontal grid lines and weight labels.
        for (let i = 0; i <= 4; i++) {
            const weight =
                minimumWeight + (weightRange * i) / 4;

            const y = getY(weight);

            addSvgElement("line", {
                x1: left,
                y1: y,
                x2: width - right,
                y2: y,
                stroke: "#cccccc",
                "stroke-dasharray": "4 4"
            });

            addSvgElement("text", {
                x: left - 12,
                y: y + 4,
                "text-anchor": "end",
                "font-size": "12",
                fill: "currentColor"
            }, Number(weight.toFixed(1)) + " kg");
        }

        // Axes.
        addSvgElement("line", {
            x1: left,
            y1: top,
            x2: left,
            y2: height - bottom,
            stroke: "currentColor"
        });

        addSvgElement("line", {
            x1: left,
            y1: height - bottom,
            x2: width - right,
            y2: height - bottom,
            stroke: "currentColor"
        });

        // Progress line.
        const points = progressData.map(function (point, index) {
            return getX(index) + "," + getY(point.weight);
        }).join(" ");

        if (progressData.length > 1) {
            addSvgElement("polyline", {
                points: points,
                fill: "none",
                stroke: "#2878d0",
                "stroke-width": "3",
                "stroke-linejoin": "round",
                "stroke-linecap": "round"
            });
        }

        // Points and dates.
        progressData.forEach(function (point, index) {
            const x = getX(index);
            const y = getY(point.weight);

            addSvgElement("circle", {
                cx: x,
                cy: y,
                r: "5",
                fill: "#2878d0"
            });

            addSvgElement("text", {
                x: x,
                y: y - 12,
                "text-anchor": "middle",
                "font-size": "12",
                fill: "currentColor"
            }, point.weight + " kg");

            const dateLabel = new Date(
                point.date
            ).toLocaleDateString(undefined, {
                day: "2-digit",
                month: "short"
            });

            addSvgElement("text", {
                x: x,
                y: height - bottom + 22,
                "text-anchor": "middle",
                "font-size": "11",
                fill: "currentColor"
            }, dateLabel);
        });
    }

        exerciseSelect.addEventListener("change", function () {
        drawProgressChart();

        progressChartMessage.style.color = "#ff4d4d";
    });

    drawProgressChart();
}


/* Profile measurements are saved and loaded server-side through PHP/MySQL. */

/* LIGHT / DARK THEME */

(function () {
    const savedTheme = localStorage.getItem("steelFitTheme");

    if (savedTheme === "light") {
        document.body.classList.add("light-theme");
    }

    const themeButton = document.getElementById("theme-toggle");
    const themeMessage = document.getElementById("theme-message");

    function updateThemeButton() {
        if (!themeButton) return;

        const isLight =
            document.body.classList.contains("light-theme");

        themeButton.textContent = isLight
            ? "Switch to Dark Mode"
            : "Switch to Light Mode";
    }

    updateThemeButton();

    if (themeButton) {
        themeButton.addEventListener("click", function () {
            document.body.classList.toggle("light-theme");

            const isLight =
                document.body.classList.contains("light-theme");

            localStorage.setItem(
                "steelFitTheme",
                isLight ? "light" : "dark"
            );

            updateThemeButton();

            if (themeMessage) {
                themeMessage.textContent =
                    isLight
                        ? "Light theme enabled."
                        : "Dark theme enabled.";
            }
        });
    }
})();

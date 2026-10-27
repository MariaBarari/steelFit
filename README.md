# SteelFit

**SteelFit** is a web-based fitness tracking application developed using PHP, MySQL, HTML, CSS, and JavaScript. It helps users manage their workouts, track exercises, and monitor their fitness progress.

## Features

- **User Authentication** — Register and log in to your account.
- **Dashboard** — View workout statistics and recent activity.
- **Workout Management** — Create, view, and delete workouts.
- **Exercise Library** — Browse exercises and add custom exercises.
- **Workout Tracking** — Record exercises, sets, repetitions, and weights.
- **Workout Check-ins** — Record workout completion details.
- **User Profile** — Manage personal profile information.
- **Responsive Interface** — Access the application on different screen sizes.

## Technologies

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Local Development Environment:** XAMPP
- **Version Control:** Git and GitHub

## Requirements

- XAMPP or another environment with PHP and MySQL
- A web browser
- Git (optional, for version control)

## Installation

1. Clone or download the repository.
2. Place the project folder inside XAMPP's `htdocs` directory.
3. Start **Apache** and **MySQL** from the XAMPP Control Panel.
4. Create a MySQL database named `steelfit`.
5. Import or execute the database schema required by the application.
6. Configure the database connection in `db.php`.
7. Open the application in your browser:

   `http://localhost/SteelFit/`

## Database

SteelFit uses MySQL to store application data, including user accounts, workouts, exercises, and workout exercise details.

Make sure the database tables are created before using the application. The database connection settings can be configured in `db.php`.

## Project Structure

The project includes PHP pages for the dashboard, workout management, exercise library, user profile, and workout tracking, along with CSS stylesheets, JavaScript functionality, and database connection files.

## Future Improvements

- Enhanced progress charts and workout statistics
- Additional workout templates
- More exercise tracking options
- Further improvements to responsive design

## Author

Developed as a university web technologies project.

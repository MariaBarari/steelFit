
<?php
session_start();
if (!empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require_once 'db.php';

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Server-side validation
    if ($username === '') {
        $errors[] = 'Username-ul este obligatoriu.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = 'Username-ul trebuie să aibă între 3 și 50 de caractere.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Introdu o adresă de email validă.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Parola trebuie să aibă cel puțin 8 caractere.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Parolele nu coincid.';
    }

    if (empty($errors)) {
        try {
            // Check whether username or email already exists
            $stmt = $pdo->prepare(
                'SELECT id FROM users WHERE username = :username OR email = :email'
            );

            $stmt->execute([
                'username' => $username,
                'email' => $email
            ]);

            if ($stmt->fetch()) {
                $errors[] = 'Username-ul sau adresa de email este deja folosită.';
            } else {
                // Hash password before saving
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare(
                    'INSERT INTO users (username, email, password)
                     VALUES (:username, :email, :password)'
                );

                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword
                ]);

                $_SESSION['user_id'] = (int) $pdo->lastInsertId();
                $_SESSION['username'] = $username;

                header('Location: exercises.php');
                exit;
            }
        } catch (PDOException $e) {
            error_log('Registration error: ' . $e->getMessage());
            $errors[] = 'Înregistrarea nu a reușit. Încearcă din nou.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SteelFit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main class="register-container">
        <h1>Join SteelFit</h1>
        <p>Creează-ți contul pentru a-ți urmări antrenamentele.</p>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div>
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    maxlength="50"
                    value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div>
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    maxlength="100"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div>
                <label for="password">Parolă</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required
                >
            </div>

            <div>
                <label for="confirm_password">Confirmă parola</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    minlength="8"
                    required
                >
            </div>

            <button type="submit">Create Account</button>
        </form>

        <p>Ai deja cont? <a href="login.php">Login</a></p>
    </main>

</body>
</html>

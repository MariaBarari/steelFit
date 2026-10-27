<?php
session_start();
if (!empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/db.php';
$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Introdu o adresă de e-mail validă și parola.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id, username, email, password FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: exercises.php');
                exit;
            }
            $error = 'E-mail sau parolă incorectă.';
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            $error = 'Autentificarea nu este disponibilă momentan. Încearcă din nou.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>SteelFit - Login</title><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="light.css"></head>
<body><header><div class="logo"><a href="index.php"><img src="pozeex/logo2.png" alt="SteelFit Logo" width="150"></a></div></header>
<main><section><h1>Autentificare</h1><p>Conectează-te pentru a gestiona exercițiile SteelFit.</p>
<?php if ($error): ?><p role="alert" style="color:#c00"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post" action="login.php">
<label for="email">E-mail</label><br><input type="email" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required><br><br>
<label for="password">Parolă</label><br><input type="password" id="password" name="password" required><br><br>
<button type="submit">Login</button></form>
<p>Nu ai cont? <a href="register.php">Înregistrează-te</a></p></section></main></body></html>

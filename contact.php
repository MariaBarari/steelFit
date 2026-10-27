<?php
require_once __DIR__ . '/auth.php';
$errors = [];
$success = '';
$name = '';
$email = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (mb_strlen($name) < 2 || mb_strlen($name) > 80) $errors[] = 'Numele trebuie să aibă între 2 și 80 de caractere.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Introdu o adresă de e-mail validă.';
    if (mb_strlen($message) < 10 || mb_strlen($message) > 2000) $errors[] = 'Mesajul trebuie să aibă între 10 și 2000 de caractere.';

    if (!$errors) {
        $entry = [
            'date' => date('c'),
            'name' => $name,
            'email' => $email,
            'message' => $message
        ];
        $file = __DIR__ . '/data/messages.txt';
        $json = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json !== false && file_put_contents($file, $json . PHP_EOL, FILE_APPEND | LOCK_EX) !== false) {
            $success = 'Mesajul a fost salvat. Îți mulțumim că ne-ai contactat!';
            $name = $email = $message = '';
        } else {
            $errors[] = 'Mesajul nu a putut fi salvat. Verifică permisiunile folderului data.';
        }
    }
}
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html><html lang="ro"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>SteelFit - Contact</title><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="light.css"></head>
<body><header><div class="logo"><a href="index.php"><img src="pozeex/logo2.png" alt="SteelFit Logo" width="150"></a></div><nav><a href="index.php">Dashboard</a><a href="exercises.php">Exercises</a><a href="contact.php">Contact</a></nav></header>
<main><h1>Contact SteelFit</h1><p>Trimite-ne o întrebare sau un mesaj.</p>
<?php foreach ($errors as $error): ?><p role="alert" style="color:#c00"><?= e($error) ?></p><?php endforeach; ?>
<?php if ($success): ?><p role="status" style="color:green"><?= e($success) ?></p><?php endif; ?>
<form method="post" action="contact.php">
<label for="name">Nume</label><br><input id="name" name="name" maxlength="80" required value="<?= e($name) ?>"><br><br>
<label for="email">E-mail</label><br><input id="email" name="email" type="email" required value="<?= e($email) ?>"><br><br>
<label for="message">Mesaj</label><br><textarea id="message" name="message" rows="6" maxlength="2000" required><?= e($message) ?></textarea><br><br>
<button type="submit">Trimite mesajul</button></form></main></body></html>

<?php
// Conexiunea centralizată la baza de date SteelFit din XAMPP.
$host = '127.0.0.1';
$dbname = 'steelfit';
$dbUser = 'root';
$dbPassword = ''; // Modifică dacă ai setat o parolă pentru root în MySQL.

try {
    $pdo = new PDO(
        "mysql:host={$host};port=3306;dbname={$dbname};charset=utf8mb4",
        $dbUser,
        $dbPassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log('SteelFit DB connection error: ' . $e->getMessage());
    http_response_code(500);
    exit('Nu ne putem conecta momentan la baza de date. Verifică dacă MySQL rulează și dacă setările din db.php sunt corecte.');
}

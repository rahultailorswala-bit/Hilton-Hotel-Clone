<?php
$host = 'localhost';
$dbname = 'db9ts0rb0hkgbe';
$username = 'uzrprp3rmtdfr';
$password = '#[qI(M3@k1bz';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

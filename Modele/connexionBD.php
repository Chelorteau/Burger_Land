<?php

require_once 'codeDataBase.php';

try {
    $connexion = new PDO("mysql:host={$host};dbname={$dbname}", "{$login}", "{$password}");
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $message) {
    echo "Erreur de connexion : " . $message->getMessage();
}

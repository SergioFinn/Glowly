<?php

$host = getenv('GLOWLY_DB_HOST') ?: '127.0.0.1';
$dbname = getenv('GLOWLY_DB_NAME') ?: 'escolar_glowly';
$user = getenv('GLOWLY_DB_USER') ?: 'root';
$pass = getenv('GLOWLY_DB_PASS') !== false ? getenv('GLOWLY_DB_PASS') : '';
$port = getenv('GLOWLY_DB_PORT') ?: '3306';
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    error_log('Glowly: no se pudo conectar a MySQL.');
    http_response_code(503);
    exit('No se pudo conectar a Glowly. Comprueba MySQL y la importación de escolar_glowly.');
}

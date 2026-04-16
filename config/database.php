<?php
declare(strict_types=1);

function getDb(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = '10.30.5.44';
    $dbName = 'campus';
    $dbUser = 'campus_app';
    $dbPass = 'TU_PASSWORD_AQUI';

    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";

    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
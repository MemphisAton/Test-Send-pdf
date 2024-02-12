<?php
$host = 'localhost'; // исправлена опечатка
$username = 'root';
$password = '';
$db_name = 'download_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password); // убраны пробелы вокруг =
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit; // Добавлено, чтобы остановить выполнение скрипта в случае ошибки
}
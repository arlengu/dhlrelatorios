<?php

require 'config.php';

$config = require 'config.php';

/**
 * Cria uma conexão PDO com o banco de dados.
 *
 * @return PDO
 * @throws PDOException
 */
function createPdoConnection(array $config): PDO
{
    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8",
        $config['db_username'],
        $config['db_password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

/**
 * Cria uma conexão MySQLi com o banco de dados.
 *
 * @return mysqli
 */
function createMysqliConnection(array $config): mysqli
{
    $mysqli = new mysqli($config['db_host'], $config['db_username'], $config['db_password'], $config['db_name']);
    if ($mysqli->connect_error) {
        error_log("MySQLi connection failed: " . $mysqli->connect_error, 3, __DIR__ . '/errors.log');
        die("An error occurred while connecting to the database.");
    }
    return $mysqli;
}

try {
    // Conectando ao banco de dados com PDO
    $pdo = createPdoConnection($config);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage(), 3, __DIR__ . '/errors.log');
    die("An error occurred while connecting to the database.");
}

// Optionally, create a mysqli connection for legacy code or other use cases
$conexao = createMysqliConnection($config);

// Você pode usar $pdo e $conexao em outros arquivos conforme necessário


<?php

// Habilita a exibição de erros para depuração (opcional)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carrega as configurações do banco de dados
$config = require 'config.php';

/**
 * Cria uma conexão PDO com o banco de dados.
 *
 * @return PDO
 * @throws PDOException
 */
function createPdoConnection(array $config): PDO
{
    try {
        $options = [
            PDO::MYSQL_ATTR_SSL_CA     => $config['ssl_ca'], // Caminho para o certificado CA
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Desabilita a verificação do servidor (opcional)
        ];

        $pdo = new PDO(
            "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8",
            $config['db_username'],
            $config['db_password'],
            $options
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        error_log("PDO connection failed: " . $e->getMessage(), 3, __DIR__ . '/errors.log');
        throw $e; // Re-throw the exception for handling later
    }
}

/**
 * Cria uma conexão MySQLi com o banco de dados.
 *
 * @return mysqli
 */
function createMysqliConnection(array $config): mysqli
{
    $con = mysqli_init();

    // Configura o certificado SSL
    $caCertPath = $config['ssl_ca']; // Caminho para o seu certificado CA
    mysqli_ssl_set($con, NULL, NULL, $caCertPath, NULL, NULL);

    // Realiza a conexão com SSL
    if (!mysqli_real_connect($con, $config['db_host'], $config['db_username'], $config['db_password'], $config['db_name'], 3306, NULL, MYSQLI_CLIENT_SSL)) {
        error_log("MySQLi connection failed: " . mysqli_connect_error(), 3, __DIR__ . '/errors.log');
        die("An error occurred while connecting to the database.");
    }
    
    return $con; // Retorna a conexão MySQLi
}

try {
    // Conectando ao banco de dados com PDO
    $pdo = createPdoConnection($config);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Cria a conexão MySQLi
$conexao = createMysqliConnection($config);

<?php

// Inclui o autoloader do Composer para carregar dependências
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Cria uma instância do Dotenv e carrega as variáveis de ambiente do arquivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retorna as configurações do banco de dados como um array associativo
return [
    'db_host' => $_ENV['DB_HOST'],         // Endereço do servidor MySQL
    'db_username' => $_ENV['DB_USERNAME'], // Nome de usuário do banco de dados
    'db_password' => $_ENV['DB_PASSWORD'], // Senha do banco de dados
    'db_name' => $_ENV['DB_NAME'],         // Nome do banco de dados
    'ssl_ca' => $_ENV['SSL_CA'],           // Caminho para o certificado CA
];

<?php
// Inicializa a conexão
$con = mysqli_init();

// Configura o certificado SSL
$caCertPath = 'DigiCertGlobalRootCA.crt.pem'; // Caminho para o seu certificado CA
mysqli_ssl_set($con, NULL, NULL, $caCertPath, NULL, NULL);

// Realiza a conexão
$host = 'arlendbteste.mysql.database.azure.com';
$username = 'arlendbteste';
$password = '3KT8zx203@Brasil'; // Substitua pelo seu password
$database = 'tabela1'; // Substitua pelo seu nome do banco de dados

if (mysqli_real_connect($con, $host, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    
    // Obtém os dados do POST
    $sku = $_POST['sku'] ?? '';
    $placa = $_POST['placa'] ?? '';
    $invoice = $_POST['invoice'] ?? '';
    $lpn = $_POST['lpn'] ?? '';
    $comentario = $_POST['comment'] ?? '';
    $usuario = null; // Campo de usuário deixado vazio por enquanto

    // Prepara a data e hora atual
    $data_registro = date('Y-m-d H:i:s');

    // Prepara a consulta
    $query = "INSERT INTO comentarios (sku, placa, invoice, lpn, usuario, data_registro, comentario) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    
    // Verifica se a preparação da consulta foi bem-sucedida
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssss", $sku, $placa, $invoice, $lpn, $usuario, $data_registro, $comentario);
        
        // Executa a consulta
        if (mysqli_stmt_execute($stmt)) {
            // Sucesso ao salvar
            echo json_encode(['success' => true]);
        } else {
            // Erro na execução da consulta
            echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
        }
        
        // Fecha a declaração
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
    }

    // Fecha a conexão
    mysqli_close($con);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_connect_error()]);
}
?>

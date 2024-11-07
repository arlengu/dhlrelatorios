<?php
// Inclui o arquivo de conexão
require 'Configuracoes/database.php'; // Este arquivo contém a conexão MySQLi configurada

// Obtém os dados do POST
$sku = $_POST['sku'] ?? '';
$placa = $_POST['placa'] ?? '';
$invoice = $_POST['invoice'] ?? '';
$status = $_POST['status'] ?? '';  // Pega o status (Falta ou Sobra)
$comentario = $_POST['comment'] ?? '';
$usuario = null; // Campo de usuário deixado vazio por enquanto

// Define o valor do LPN como "Comentário geral - [Status]"
$lpn = $status;  // Concatenando o status no LPN

// Prepara a data e hora atual
$data_registro = date('Y-m-d H:i:s');

// Prepara a consulta
$query = "INSERT INTO comentarios (sku, placa, invoice, lpn, usuario, data_registro, comentario) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexao->prepare($query);

// Verifica se a preparação da consulta foi bem-sucedida
if ($stmt) {
    $stmt->bind_param("sssssss", $sku, $placa, $invoice, $lpn, $usuario, $data_registro, $comentario);
    
    // Executa a consulta
    if ($stmt->execute()) {
        // Sucesso ao salvar
        echo json_encode(['success' => true]);
    } else {
        // Erro na execução da consulta
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    
    // Fecha a declaração
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => $conexao->error]);
}

// Fecha a conexão
$conexao->close();
?>

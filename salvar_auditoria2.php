<?php
// Inclui o arquivo de conexão
require 'Configuracoes/database.php'; // Este arquivo contém a conexão MySQLi configurada

// Recebe os dados do formulário
$q1 = $_POST['q4'];
$q2 = $_POST['q5'];
$q3 = $_POST['q6'];
$placa = $_POST['placa'];
$invoice = $_POST['invoice'];
$usuario = $_POST['usuario'];
$data_da_validacao = date('Y-m-d H:i:s'); // Captura a data atual

// Prepara a consulta
$query = "INSERT INTO relatorio2 (pergunta4, pergunta5, pergunta6, usuario, data_da_validacao, invoice, placa) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, 'sssssss', $q1, $q2, $q3, $usuario, $data_da_validacao, $invoice, $placa);

// Executa a consulta
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conexao)]);
}

// Fecha a conexão
mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>

<?php
// Inclui o arquivo de conexão
require 'Configuracoes/database.php'; // Este arquivo contém a conexão MySQLi configurada

// Obtém os dados do POST
$placa = isset($_POST['placa']) ? mysqli_real_escape_string($conexao, $_POST['placa']) : 'RHI8A70-586251';
$invoice = isset($_POST['invoice']) ? mysqli_real_escape_string($conexao, $_POST['invoice']) : '8802889342';

// Verifica se placa e invoice não estão vazios
if (!empty($placa) && !empty($invoice)) {
    // Consulta os dados da tabela relatorio1
    $query = "SELECT pergunta1, pergunta2, pergunta3 FROM relatorio1 WHERE placa = '$placa' AND invoice = '$invoice'";
    $result = mysqli_query($conexao, $query);

    if ($result) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data); // Retorna os dados em formato JSON
    } else {
        echo json_encode(['error' => 'Erro na consulta: ' . mysqli_error($conexao)]);
    }
} else {
    echo json_encode(['error' => 'Placa ou invoice não fornecidos.']);
}

// Fecha a conexão (opcional, você pode querer manter a conexão aberta se for fazer múltiplas operações)
// mysqli_close($conexao); // Descomente se desejar fechar a conexão aqui
?>

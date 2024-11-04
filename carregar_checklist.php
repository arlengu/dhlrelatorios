<?php
// Inclui o arquivo de conexão
require 'Configuracoes/database.php'; // Este arquivo contém a conexão MySQLi configurada

// Consulta os dados da tabela checklist
$query = "SELECT * FROM checklist";
$result = mysqli_query($conexao, $query);

// Cria um array para armazenar os dados
$checklistData = [];

// Loop pelos resultados e armazena no array
while ($row = mysqli_fetch_assoc($result)) {
    $checklistData[] = $row;
}

// Retorna os dados em formato JSON
echo json_encode($checklistData);

// Verifica se a consulta foi bem-sucedida
if (!$result) {
    echo json_encode(["error" => "Erro na consulta: " . mysqli_error($conexao)]);
}

// Fecha a conexão (opcional)
mysqli_close($conexao);
?>

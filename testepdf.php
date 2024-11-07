<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de Conferência de Recebimento</title>
    <?php
        include 'Configuracoes/headgerais.php';
        renderHead("Check-list de veículo");
    ?>
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
            box-sizing: border-box;
        }

        .btn {
            display: none; /* Esconde os botões na impressão */
        }
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background: #f2f2f2;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative; /* Para posicionar os botões */
    }

    .a4-container {
        width: 297mm;
        height: 210mm;
        background: white;
        padding: 20px;
        margin-bottom: 10px;
        box-sizing: border-box;
        page-break-after: always;
        position: relative;
    }

    h1 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #333;
        padding: 10px;
        text-align: center;
        width: 14%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    th {
        background-color: #f2f2f2;
    }

    .signature {
        margin-top: 40px; /* Espaço abaixo da tabela */
        display: flex;
        justify-content: space-between;
    }

    .signature div {
        text-align: center;
    }

    .line {
        border-top: 1px solid #000;
        margin-top: 5px;
        width: 200px;
    }

    .total-row {
        background-color: #f2f0f0;
        font-weight: bold;
    }

    .buttons {
        position: fixed; /* Usar fixed para que fiquem flutuantes em relação à janela */
        left: 20px; /* Posição à esquerda */
        top: 20px; /* Distância do topo */
        display: flex;
        flex-direction: column;
        z-index: 1000; /* Valor alto para garantir que os botões fiquem acima de outros elementos */
    }

    .button {
        margin-bottom: 10px;
        padding: 10px 15px;
        background-color: #007BFF; /* Cor do botão */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
    }

    .button:hover {
        background-color: #0056b3; /* Cor ao passar o mouse */
    }

    /* Responsividade para tablets grandes e telas de desktop */
    @media (min-width: 992px) {
        .a4-container {
            padding: 30px; /* Maior espaçamento para telas grandes */
        }

        h1 {
            font-size: 30px;
        }

        table {
            font-size: 16px;
        }

        th, td {
            padding: 12px;
        }
    }

    /* Responsividade para dispositivos móveis e tablets pequenos */
    @media (max-width: 992px) {
        .a4-container {
            width: 100%;
            max-width: none; /* Remove a restrição do tamanho A4 */
            padding: 10px;
        }

        h1 {
            font-size: 24px;
        }

        table {
            font-size: 12px;
            overflow-x: auto;
            display: block; /* Permite rolagem horizontal */
        }

        th, td {
            padding: 8px;
        }

        .signature {
            flex-direction: column;
            align-items: flex-start;
        }

        .buttons {
            left: 10px; /* Manter a posição relativa */
            top: 10px;
        }

        .button {
            margin-bottom: 5px;
            padding: 8px 12px;
            font-size: 14px;
        }
    }

    /* Responsividade para dispositivos móveis pequenos */
    @media (max-width: 480px) {
        .a4-container {
            width: 100%;
            padding: 10px;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        table {
            font-size: 10px;
        }

        th, td {
            padding: 5px;
            font-size: 9px;
        }

        .signature div {
            font-size: 10px;
        }

        .buttons {
            left: 5px; /* Manter a posição relativa */
            top: 5px;
        }

        .button {
            margin-bottom: 5px;
            padding: 5px 8px;
            font-size: 12px;
        }
    }
</style>

</head>
<body>

<div class="buttons">
    <button class="btn btn-primary" onclick="window.history.back()"><i class="fa-solid fa-left-long"></i></button>
    <button class="btn btn-primary" style="margin-top: 20px;" onclick="window.print()"><i class="fa-solid fa-print"></i></button>
</div>

<?php
// Inclui o arquivo de conexão
require 'Configuracoes/database.php'; // Este arquivo contém a conexão MySQLi configurada

// Obtenha os dados enviados
$invoice = $_POST['invoice'] ?? '';

// Consulta SQL
$query = "SELECT lpn, sku, local, lote, quantidade, status_recebimento, 
          DATE_FORMAT(data_vencimento, '%d/%m/%Y') as data_vencimento, quantidade_esperada_total, 
          quantidade_identificada_total FROM relatorio WHERE invoice = ? ORDER BY sku";

// Prepara e executa a consulta
$stmt = $conexao->prepare($query);
$stmt->bind_param('s', $invoice);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) > 0) {
    $currentSKU = null;
    $totalExpected = 0;
    $totalReceived = 0;
    $rows = [];
    $pages = []; // Array para armazenar as páginas

    while ($row = mysqli_fetch_assoc($result)) {
        $sku = $row['sku'];

        // Se mudamos de SKU, processamos a página anterior
        if ($currentSKU && $currentSKU !== $sku) {
            // Adiciona a página atual ao array de páginas
            $pages[] = [
                'sku' => $currentSKU,
                'rows' => $rows,
                'total_expected' => $totalExpected,
                'total_received' => $totalReceived,
            ];
            
            // Reset para o novo SKU
            $rows = [];
            $totalExpected = 0;
            $totalReceived = 0;
        }

        // Atualiza o SKU atual e incrementa os totais
        $currentSKU = $sku;
        $totalExpected = $row['quantidade_esperada_total'];
        $totalReceived = $row['quantidade_identificada_total'];

        // Adiciona a linha de dados do item
        $rows[] = $row;

        // Verifica se já temos um número máximo de linhas por página
        if (count($rows) >= 12) { // Limite de 15 linhas por página
            // Adiciona a página atual ao array de páginas
            $pages[] = [
                'sku' => $currentSKU,
                'rows' => $rows,
                'total_expected' => $totalExpected,
                'total_received' => $totalReceived,
            ];

            // Reseta as variáveis para a nova página
            $rows = [];
            $totalExpected = 0;
            $totalReceived = 0;
        }
    }

    // Renderiza a última página, se houver linhas restantes
    if (!empty($rows)) {
        $pages[] = [
            'sku' => $currentSKU,
            'rows' => $rows,
            'total_expected' => $totalExpected,
            'total_received' => $totalReceived,
        ];
    }

    // Renderiza todas as páginas
    foreach ($pages as $index => $page) {
        echo '<div class="a4-container">';
        echo '<h1>Conferência de Recebimento - SKU: ' . htmlspecialchars($page['sku']) . '</h1>';
        echo '<table>';
        echo '<thead><tr><th>LPN</th><th>SKU</th><th>Local</th><th>Lote</th><th>Quantidade</th><th>Status</th><th>Data de Vencimento</th></tr></thead>';
        echo '<tbody>';

        foreach ($page['rows'] as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['lpn']) . '</td>';
            echo '<td>' . htmlspecialchars($row['sku']) . '</td>';
            echo '<td>' . htmlspecialchars($row['local']) . '</td>';
            echo '<td>' . htmlspecialchars($row['lote']) . '</td>';
            echo '<td>' . htmlspecialchars($row['quantidade']) . '</td>';
            echo '<td>' . htmlspecialchars($row['status_recebimento']) . '</td>';
            echo '<td>' . htmlspecialchars($row['data_vencimento']) . '</td>';
            echo '</tr>';
        }

        // Total para o SKU na última linha
        echo '<tr class="total-row">';
        echo '<td colspan="3">Total para o SKU: ' . htmlspecialchars($page['sku']) . '</td>';
        echo '<td colspan="2">Total esperado: ' . $page['total_expected'] . '</td>';
        echo '<td colspan="2">Total recebido: ' . $page['total_received'] . '</td>';
        echo '</tr>';

        echo '</tbody></table>';

        // Verifica se a última página tem mais de 13 linhas
        if ($index === count($pages) - 1 && count($page['rows']) > 13) {
            // Se tiver, adiciona uma nova página para a assinatura
            echo '</div>'; // Fecha o container da página
            echo '<div class="a4-container">'; // Nova página para a assinatura
            echo '<h1>Assinaturas</h1>'; // Título opcional
        }

        // Assinatura somente na última página
        if ($index === count($pages) - 1) {
            echo '<div class="signature">';
            echo '<div><div style="font-weight: bold;">Nome do conferente:</div> João Pedro da Silva</div>';
            echo '<div><div style="font-weight: bold;">Data da conferência:</div> 10/10/2024 às 14:35</div>';
            echo '</div>';
        }

        echo '</div>'; // Fecha o container
    }
} else {
    echo '<p>Nenhum dado encontrado na tabela relatorio.</p>';
}

mysqli_close($conexao);
?>

</body>
</html>

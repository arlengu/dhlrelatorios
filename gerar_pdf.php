<?php
require('fpdf/fpdf.php'); // Ajuste o caminho conforme necessário

// Função para obter dados do banco
function obterDados($query) {
    // Conexão com o banco de dados usando suas configurações
    $conn = mysqli_init();
    $caCertPath = 'DigiCertGlobalRootCA.crt.pem'; // Caminho para o seu certificado CA
    mysqli_ssl_set($conn, NULL, NULL, $caCertPath, NULL, NULL);

    $host = 'arlendbteste.mysql.database.azure.com';
    $username = 'arlendbteste';
    $password = '3KT8zx203@Brasil'; // Substitua pelo seu password
    $database = 'tabela1'; // Substitua pelo seu nome do banco de dados

    if (!mysqli_real_connect($conn, $host, $username, $password, $database)) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    $result = mysqli_query($conn, $query);
    $dados = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $dados[] = $row;
    }

    mysqli_close($conn);
    return $dados;
}

// Inicialização do PDF
$pdf = new FPDF();
$pdf->AddPage();

// Capa com informações do checklist
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Checklist', 0, 1, 'C');
$pdf->Ln(10); // Espaço entre a capa e o conteúdo

// Obter dados do checklist
$checklistQuery = "SELECT * FROM checklist";
$checklistData = obterDados($checklistQuery);
foreach ($checklistData as $item) {
    $pdf->Cell(0, 10, "Placa: {$item['placa']}, Invoice: {$item['invoice']}", 0, 1);
}

// Auditoria de veículo
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Auditoria de Veículo', 0, 1, 'C');
$pdf->Ln(10); // Espaço entre o título e o conteúdo

// Obter dados do relatorio1
$relatorio1Query = "SELECT * FROM relatorio1";
$relatorio1Data = obterDados($relatorio1Query);

foreach ($relatorio1Data as $item) {
    $pdf->Cell(0, 10, "Pergunta 1: {$item['pergunta1']}, Usuário: {$item['usuario']}", 0, 1);
}

// Adiciona uma nova página para o Relatório 2
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Relatório 2', 0, 1, 'C');
$pdf->Ln(10); // Espaço entre o título e o conteúdo

// Obter dados do relatorio2
$relatorio2Query = "SELECT * FROM relatorio2";
$relatorio2Data = obterDados($relatorio2Query);

foreach ($relatorio2Data as $item) {
    $pdf->Cell(0, 10, "Pergunta 4: {$item['pergunta4']}, Usuário: {$item['usuario']}", 0, 1);
}

// Relatório de recebimento
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Relatório de Recebimento', 0, 1, 'C');
$pdf->Ln(10); // Espaço entre o título e o conteúdo

// Obter dados do relatório de recebimento
$relatorioQuery = "SELECT * FROM relatorio";
$relatorioData = obterDados($relatorioQuery);

foreach ($relatorioData as $item) {
    $pdf->Cell(0, 10, "SKU: {$item['sku']}, Quantidade: {$item['quantidade']}", 0, 1);
}

// Fechar e baixar o PDF
$pdf->Output('D', 'relatorio.pdf');
?>

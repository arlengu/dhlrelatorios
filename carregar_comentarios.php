<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários da Conferência</title>
    <?php
        include 'Configuracoes/headgerais.php';
        renderHead("Check-list de veículo");
    ?>
    <style>
        /* Estilos para o formato A4 */
        @media print {
            @page {
                size: A4; /* Define o tamanho da página como A4 */
                margin: 10mm; /* Define a margem da página */
            }
            body {
                margin: 0;
                padding: 0;
                width: 210mm; /* Largura para o formato A4 */
                height: 297mm; /* Altura para o formato A4 */
                box-sizing: border-box;
            }
            .buttons {
                display: none !important; /* Garante que os botões sejam escondidos na impressão */
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
            position: relative;
        }

        .a4-container {
            width: 210mm;
            min-height: 297mm;
            background: white;
            padding: 20px;
            margin-bottom: 10px;
            box-sizing: border-box;
            position: relative;
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
        }

        th {
            background-color: #f2f2f2;
        }

        .buttons {
            position: absolute;
            left: 20px;
            top: 20px;
            display: flex;
            flex-direction: column;
        }

        .button {
            margin-bottom: 10px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="buttons">
    <button class="btn btn-primary" onclick="window.history.back()"><i class="fa-solid fa-left-long"></i></button>
    <button class="btn btn-primary" style="margin-top: 20px;" onclick="window.print()"><i class="fa-solid fa-print"></i></button>
</div>

<h1>Comentários Relacionados à Conferência</h1>

<?php
// Conexão com o banco de dados
$con = mysqli_init();
$caCertPath = 'DigiCertGlobalRootCA.crt.pem';
mysqli_ssl_set($con, NULL, NULL, $caCertPath, NULL, NULL);

$host = 'arlendbteste.mysql.database.azure.com';
$username = 'arlendbteste';
$password = '3KT8zx203@Brasil';
$database = 'tabela1';

if (mysqli_real_connect($con, $host, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL)) {

    // Obtenha os dados enviados
    $placa = $_POST['placa'] ?? '';
    $invoice = $_POST['invoice'] ?? '';

    // Consulta SQL para carregar os comentários baseados em placa e invoice
    $query = "SELECT sku, lpn, usuario, DATE_FORMAT(data_registro, '%d/%m/%Y %H:%i') as data_registro, comentario 
              FROM comentarios 
              WHERE placa = ? AND invoice = ? 
              ORDER BY data_registro DESC";

    // Prepara e executa a consulta
    $stmt = $con->prepare($query);
    $stmt->bind_param('ss', $placa, $invoice);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se existem comentários
    if ($result->num_rows > 0) {
        echo '<div class="a4-container">';
        echo '<table>';
        echo '<thead><tr><th>SKU</th><th>LPN</th><th>Usuário</th><th>Data do Registro</th><th>Comentário</th></tr></thead>';
        echo '<tbody>';

        // Exibe cada linha de resultado
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['sku']) . '</td>';
            echo '<td>' . htmlspecialchars($row['lpn']) . '</td>';
            echo '<td>' . htmlspecialchars($row['usuario']) . '</td>';
            echo '<td>' . htmlspecialchars($row['data_registro']) . '</td>';
            echo '<td>' . htmlspecialchars($row['comentario']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>'; // Fecha o container da página
    } else {
        echo '<p>Nenhum comentário encontrado para a placa e invoice especificados.</p>';
    }
} else {
    echo '<p>Erro na conexão: ' . mysqli_connect_error() . '</p>';
}

// Fecha a conexão
mysqli_close($con);
?>

</body>
</html>  

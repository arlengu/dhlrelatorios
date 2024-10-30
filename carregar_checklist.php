<?php
// Conexão com o banco de dados
$con = mysqli_init();
$caCertPath = 'DigiCertGlobalRootCA.crt.pem'; // Caminho para o seu certificado CA
mysqli_ssl_set($con, NULL, NULL, $caCertPath, NULL, NULL);

$host = 'arlendbteste.mysql.database.azure.com';
$username = 'arlendbteste';
$password = '3KT8zx203@Brasil'; // Substitua pelo seu password
$database = 'tabela1'; // Substitua pelo seu nome do banco de dados

if (mysqli_real_connect($con, $host, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    // Consulta os dados da tabela checklist
    $query = "SELECT * FROM checklist";
    $result = mysqli_query($con, $query);
    
    // Cria um array para armazenar os dados
    $checklistData = [];

    // Loop pelos resultados e armazena no array
    while ($row = mysqli_fetch_assoc($result)) {
        $checklistData[] = $row;
    }

    // Retorna os dados em formato JSON
    echo json_encode($checklistData);
} else {
    echo json_encode(["error" => "Erro na conexão: " . mysqli_connect_error()]);
}

mysqli_close($con);
?>

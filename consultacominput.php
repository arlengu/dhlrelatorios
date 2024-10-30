<?php
header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON

$url = 'https://score-msc.mysupplychain.dhl.com/score_msc/external/V1/report/160590/run/sync?Content-Type=application%2Fjson&Accept=text%2Fcsv';

// Verifica se o número da nota foi enviado via POST
$invnum = $_POST['invnum']; // Captura o valor do POST

$data = array(
    'myQuery' => ["WITH nota_carreta AS (
        SELECT
        TRLR_ID,
        LISTAGG(NOTTXT, '
        ') WITHIN GROUP (ORDER BY NOTLIN) AS NOTTXT
        FROM TRLR_NOTE
        GROUP BY TRLR_ID
    )
    SELECT
        rci.invnum,
        tr.trlr_num, 
        dsts.lngdsc AS trlr_stat, 
        tr.trlr_broker, 
        tr.driver_nam, 
        tr.DRIVER_LIC_NUM, 
        dstt.LNGDSC AS trlr_typ, 
        TR.TRLR_ID,
        ntc.NOTTXT, 
        TR.YARD_LOC,  
        TR.TRACTOR_NUM, 
        TR.TRLR_SEAL1, 
        TR.TRLR_SEAL2, 
        TR.TRLR_SEAL3
    FROM
        rcvinv rci
    LEFT JOIN 
        RCVtrk rct ON rci.TRKNUM = rct.TRKNUM
    LEFT JOIN 
        trlr tr ON tr.trlr_id = rct.trlr_id
    LEFT JOIN 
        dscmst dsts ON dsts.colval = tr.trlr_stat AND dsts.colnam = 'trlr_stat' AND dsts.locale_id = 'US_ENGLISH'
    LEFT JOIN 
        dscmst dstt ON dstt.colnam = 'trlr_typ' AND dstt.LOCALE_ID = 'US_ENGLISH' AND dstt.colval = tr.trlr_typ
    LEFT JOIN 
        nota_carreta ntc ON ntc.TRLR_ID = tr.trlr_id
    WHERE
        rci.invnum = '{$invnum}'"]
);

$user = 'arbarret';
$password = '3KT8zx203@Brasil1';
$credenciais = $user . ':' . $password;
$credenciaisBase64 = base64_encode($credenciais);

$headers = array(
    'Authorization: Basic ' . $credenciaisBase64,
    'Content-Type: application/json'
);

$options = array(
    'http' => array(
        'header'  => $headers,
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true,
    )
);

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(["error" => "Erro na requisição"]);
    exit; // Para evitar continuar a execução
}

$rows = str_getcsv($response, "\n");
$header = str_getcsv(array_shift($rows)); // Pega o cabeçalho
$dataArray = [];

foreach ($rows as $row) {
    $rowData = str_getcsv($row);
    $dataArray[] = array_combine($header, $rowData); // Combina cabeçalho com os dados
}

// Inicializa a conexão com o banco de dados
$con = mysqli_init();
$caCertPath = 'DigiCertGlobalRootCA.crt.pem'; // Caminho para o seu certificado CA
mysqli_ssl_set($con, NULL, NULL, $caCertPath, NULL, NULL);

$host = 'arlendbteste.mysql.database.azure.com';
$username = 'arlendbteste';
$password = 'suasenha'; // Substitua pelo seu password
$database = 'tabela1'; // Substitua pelo seu nome do banco de dados

if (mysqli_real_connect($con, $host, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    foreach ($dataArray as $data) {
        $placa = mysqli_real_escape_string($con, $data['TRLR_NUM'] ?? '');
        $invoice = mysqli_real_escape_string($con, $data['INVNUM'] ?? '');

        // Verifica se a invoice ou a placa já existem
        $checkQuery = "SELECT * FROM checklist WHERE invoice = '$invoice' OR placa = '$placa'";
        $checkResult = mysqli_query($con, $checkQuery);

        if (mysqli_num_rows($checkResult) == 0) {
            // Se não existir, insere no banco
            $transportadora = mysqli_real_escape_string($con, $data['TRLR_BROKER'] ?? '');
            $motorista = mysqli_real_escape_string($con, $data['DRIVER_NAM'] ?? '');
            $carteira_motorista = mysqli_real_escape_string($con, $data['DRIVER_LIC_NUM'] ?? '');
            $tipo_veiculo = mysqli_real_escape_string($con, $data['TRLR_TYP'] ?? '');
            $comentario = mysqli_real_escape_string($con, $data['NOTTXT'] ?? '');
            $doca = mysqli_real_escape_string($con, $data['YARD_LOC'] ?? '');
            $pager = mysqli_real_escape_string($con, $data['TRACTOR_NUM'] ?? '');
            $lacre1 = mysqli_real_escape_string($con, $data['TRLR_SEAL1'] ?? '');
            $lacre2 = mysqli_real_escape_string($con, $data['TRLR_SEAL2'] ?? '');
            $lacre3 = mysqli_real_escape_string($con, $data['TRLR_SEAL3'] ?? '');

            $insertQuery = "INSERT INTO checklist (placa, invoice, transportadora, motorista, carteira_motorista, tipo_veiculo, comentario, doca, pager, lacre1, lacre2, lacre3) 
                            VALUES ('$placa', '$invoice', '$transportadora', '$motorista', '$carteira_motorista', '$tipo_veiculo', '$comentario', '$doca', '$pager', '$lacre1', '$lacre2', '$lacre3')";

            if (!mysqli_query($con, $insertQuery)) {
                echo json_encode(["error" => "Erro na inserção: " . mysqli_error($con)]);
                exit; // Para evitar continuar a execução
            }
        }
    }
    
    // Fecha a conexão
    mysqli_close($con);
} else {
    echo json_encode(["error" => "Erro na conexão: " . mysqli_connect_error()]);
    exit; // Para evitar continuar a execução
}

// Retorna os dados em formato JSON
echo json_encode($dataArray);
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$url = 'https://score-msc.mysupplychain.dhl.com/score_msc/external/V1/report/160590/run/sync?Content-Type=application%2Fjson&Accept=text%2Fcsv';

$invnum = '8802820568';

$data = array(
    'myQuery' => [" 
    WITH resumo AS (
        SELECT
            rcl.prtnum, --SKU
            SUM(rcl.EXPQTY) AS EXPQTY_tt, --QUANTIDADE ESPERADA TOTAL
            SUM(rcl.IDNQTY) AS IDNQTY_tt, --QUANTIDADE IDENTIFICADA TOTAL
            rcl.invnum
        FROM
            rcvlin rcl
        LEFT JOIN prtdsc prd ON prd.locale_id = 'US_ENGLISH' AND prd.colval = rcl.prtnum || '|RBCCID|RCKT'
        LEFT JOIN rcvinv rci ON rci.invnum = rcl.invnum
        LEFT JOIN RCVtrk rct ON rci.TRKNUM = rct.TRKNUM
        LEFT JOIN trlr tr ON tr.trlr_id = rct.trlr_id
        LEFT JOIN dscmst dsts ON dsts.colval = tr.trlr_stat AND dsts.colnam = 'trlr_stat' AND dsts.locale_id = 'US_ENGLISH'
        LEFT JOIN dscmst dsis ON dsis.colnam = 'invsts' AND dsis.colval = rcl.rcvsts AND dsis.LOCALE_ID = 'US_ENGLISH'
        GROUP BY rcl.invnum, rcl.prtnum
    )
    
    SELECT DISTINCT
        ivs.lodnum, --LPN
        rcl.prtnum, --SKU
        ivl.stoloc, --LOCAL
        rcl.lotnum, --LOTE
        rcl.rcvqty, --QUANTIDADE
        dsis.LNGDSC rcvsts, --STATUS
        TO_CHAR(ivd.expire_dte, 'DD/MM/YYYY'), --DATA DE VENCIMENTO
        RCL.EXPQTY,
        RCL.IDNQTY,
        RESUMO.EXPQTY_tt, --QUANTIDADE ESPERADA TOTAL
        RESUMO.IDNQTY_tt, --QUANTIDADE IDENTIFICADA TOTAL
        'cs' uom,
        prd.LNGDSC descricao, --DESCRIÇÃO DO SKU
        rcl.invnum,
        SUM(rcl.rcvqty) OVER (PARTITION BY rcl.invnum, rcl.prtnum) AS total,
        rci.waybil, tr.trlr_num, dsts.lngdsc trlr_stat
    FROM
        rcvlin rcl
    LEFT JOIN resumo ON RESUMO.invnum = rcl.invnum AND RESUMO.PRTNUM = rcl.prtnum
    LEFT JOIN invdtl ivd ON rcl.rcvkey = ivd.rcvkey
    LEFT JOIN invsub ivs ON ivs.subnum = ivd.subnum
    LEFT JOIN invlod ivl ON ivl.lodnum = ivs.lodnum
    LEFT JOIN prtdsc prd ON prd.locale_id = 'US_ENGLISH' AND prd.colval = rcl.prtnum || '|RBCCID|RCKT'
    LEFT JOIN rcvinv rci ON rci.invnum = rcl.invnum
    LEFT JOIN RCVtrk rct ON rci.TRKNUM = rct.TRKNUM
    LEFT JOIN trlr tr ON tr.trlr_id = rct.trlr_id
    LEFT JOIN dscmst dsts ON dsts.colval = tr.trlr_stat AND dsts.colnam = 'trlr_stat' AND dsts.locale_id = 'US_ENGLISH'
    LEFT JOIN dscmst dsis ON dsis.colnam = 'invsts' AND dsis.colval = rcl.rcvsts AND dsis.LOCALE_ID = 'US_ENGLISH'
    WHERE
        rcl.invnum = '{$invnum}'
    ORDER BY
        rcl.prtnum
    "]
);

$user = 'arbarret';
$password = '3KT8zx203@Brasil1';
$credenciais = base64_encode("{$user}:{$password}");

$headers = array(
    'Authorization: Basic ' . $credenciais,
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
    exit;
}

// Processa a resposta CSV
$rows = str_getcsv($response, "\n");
$header = str_getcsv(array_shift($rows)); // Pega o cabeçalho
$dataArray = [];

// Verifica se a resposta está vazia ou contém erro
if (empty($rows) || count($rows) < 1) {
    echo json_encode(["error" => "Relatório não encontrado."]);
    exit;
}

foreach ($rows as $row) {
    $rowData = str_getcsv($row);
    $dataArray[] = $rowData; // Armazena os dados sem combinar com o cabeçalho
}

// Inicializa a conexão com o banco de dados
$con = mysqli_init();
$caCertPath = 'DigiCertGlobalRootCA.crt.pem'; // Caminho para o seu certificado CA
mysqli_ssl_set($con, NULL, NULL, $caCertPath, NULL, NULL);

$host = 'arlendbteste.mysql.database.azure.com';
$username = 'arlendbteste';
$password = '3KT8zx203@Brasil'; // Substitua pelo seu password
$database = 'tabela1'; // Substitua pelo seu nome do banco de dados

if (mysqli_real_connect($con, $host, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    echo "Conexão bem-sucedida ao banco de dados!<br>";

    // Inserção de dados na tabela relatorio
    foreach ($dataArray as $data) {
        // Use os índices para acessar os dados
        $invoice = mysqli_real_escape_string($con, $data[11] ?? ''); // 'invnum'
        $placa = mysqli_real_escape_string($con, $data[14] ?? ''); // 'TRLR_NUM'
        $lpn = mysqli_real_escape_string($con, $data[0] ?? ''); // 'LODNUM'
        $sku = mysqli_real_escape_string($con, $data[1] ?? ''); // 'PRTNUM'
        $local = mysqli_real_escape_string($con, $data[2] ?? ''); // 'STOLOC'
        $lote = mysqli_real_escape_string($con, $data[3] ?? ''); // 'LOTNUM'
        $quantidade = mysqli_real_escape_string($con, $data[4] ?? 0); // 'RCVQTY'
        $status_recebimento = mysqli_real_escape_string($con, $data[5] ?? ''); // 'RCVSTS'
        $teste = mysqli_real_escape_string($con, $data[6] ?? ''); // 'expire_dte'

        echo "Data de Vencimento: $teste <br>";

        // Converte a data de vencimento para o formato correto
        $data_vencimento_raw = mysqli_real_escape_string($con, $data[6] ?? ''); // 'expire_dte'
        $data_vencimento = 'NULL'; // Inicializa como NULL por padrão

        if (!empty($data_vencimento_raw)) {
            $dateParts = explode('/', $data_vencimento_raw);
            if (count($dateParts) === 3) {
                // Formata a data para YYYY-MM-DD
                $data_vencimento = sprintf("'%s-%s-%s'", $dateParts[2], $dateParts[1], $dateParts[0]); // YYYY-MM-DD
            }
        } else {
            $data_vencimento = 'NULL'; // Se a data for vazia, mantenha como NULL
        }

        echo "Data de Vencimento: $data_vencimento_raw => $data_vencimento<br>";

        // Atribui valores de quantidade esperada e identificada
        $quantidade_esperada_total = mysqli_real_escape_string($con, $data[9] ?? 0); // 'EXPQTY_tt'
        $quantidade_identificada_total = mysqli_real_escape_string($con, $data[10] ?? 0); // 'IDNQTY_tt'

        // Monta a query de inserção
        $insertQuery = "INSERT INTO relatorio (Invoice, placa, lpn, sku, local, lote, quantidade, status_recebimento, data_vencimento, quantidade_esperada_total, quantidade_identificada_total) 
                        VALUES ('$invoice', '$placa', '$lpn', '$sku', '$local', '$lote', $quantidade, '$status_recebimento', $data_vencimento, $quantidade_esperada_total, $quantidade_identificada_total)";

        if (!mysqli_query($con, $insertQuery)) {
            echo "Erro na inserção: " . mysqli_error($con) . "<br>";
        }
    }

    // Fecha a conexão
    mysqli_close($con);
} else {
    echo "Erro na conexão: " . mysqli_connect_error();
}
?>

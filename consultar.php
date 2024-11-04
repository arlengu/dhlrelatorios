<?php

// Habilita exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o autoloader do Composer e o arquivo de configuração
require 'vendor/autoload.php';
$config = require 'Configuracoes/config.php';

// Acessa as configurações do banco de dados
$password = $config['api_password']; // Aqui está a senha da API
$user = $config['api_user'];

// URL da API
$url = 'https://score-msc.mysupplychain.dhl.com/score_msc/external/V1/report/160590/run/sync?Content-Type=application%2Fjson&Accept=text%2Fcsv';

// Verifica se o número da nota foi enviado via POST
if (!isset($_POST['invnum'])) {
    echo json_encode(["error" => "Número da nota não enviado."]);
    exit;
}
$invnum = $_POST['invnum']; // Captura o valor do POST

$data = array(
    'myQuery' => ["WITH nota_carreta AS (
        SELECT
            TRLR_ID,
            LISTAGG(NOTTXT, '
            ') WITHIN GROUP (ORDER BY NOTLIN) NOTTXT
        FROM TRLR_NOTE
        GROUP BY TRLR_ID
    ),
    
    resumo AS (
        SELECT
            SUM(rcl.EXPQTY) EXPQTY_tt, -- QUANTIDADE ESPERADA TOTAL
            SUM(rcl.IDNQTY) IDNQTY_tt, -- QUANTIDADE IDENTIFICADA TOTAL
            rcl.invnum
        FROM rcvlin rcl
        LEFT JOIN prtdsc prd ON prd.locale_id = 'US_ENGLISH' AND prd.colval = rcl.prtnum || '|RBCCID|RCKT'
        LEFT JOIN rcvinv rci ON rci.invnum = rcl.invnum
        LEFT JOIN RCVtrk rct ON rci.TRKNUM = rct.TRKNUM
        LEFT JOIN trlr tr ON tr.trlr_id = rct.trlr_id
        LEFT JOIN dscmst dsts ON dsts.colval = tr.trlr_stat AND dsts.colnam = 'trlr_stat' AND dsts.locale_id = 'US_ENGLISH'
        LEFT JOIN dscmst dsis ON dsis.colnam = 'invsts' AND dsis.colval = rcl.rcvsts AND dsis.LOCALE_ID = 'US_ENGLISH'
        GROUP BY rcl.invnum
    )
    
    SELECT
        rci.invnum,
        tr.trlr_num, dsts.lngdsc trlr_stat, tr.trlr_broker, tr.driver_nam,
        tr.DRIVER_LIC_NUM, dstt.LNGDSC trlr_typ, TR.TRLR_ID,
        ntc.NOTTXT, TR.YARD_LOC, TR.TRACTOR_NUM, TR.TRLR_SEAL1,
        TR.TRLR_SEAL2, TR.TRLR_SEAL3,
        RESUMO.EXPQTY_tt, -- QUANTIDADE ESPERADA TOTAL
        RESUMO.IDNQTY_tt -- QUANTIDADE IDENTIFICADA TOTAL
    FROM rcvinv rci
    LEFT JOIN resumo ON RESUMO.invnum = rci.invnum
    LEFT JOIN RCVtrk rct ON rci.TRKNUM = rct.TRKNUM
    LEFT JOIN trlr tr ON tr.trlr_id = rct.trlr_id
    LEFT JOIN dscmst dsts ON dsts.colval = tr.trlr_stat AND dsts.colnam = 'trlr_stat' AND dsts.locale_id = 'US_ENGLISH'
    LEFT JOIN dscmst dstt ON dstt.colnam = 'trlr_typ' AND dstt.LOCALE_ID = 'US_ENGLISH' AND dstt.colval = tr.trlr_typ
    LEFT JOIN nota_carreta ntc ON ntc.TRLR_ID = tr.trlr_id
    WHERE rci.invnum = '{$invnum}'"]
);

// Credenciais para autenticação
$credenciais = $user . ':' . $password;
$credenciaisBase64 = base64_encode($credenciais);

// Cabeçalhos da requisição
$headers = array(
    'Authorization: Basic ' . $credenciaisBase64,
    'Content-Type: application/json'
);

// Configurações da requisição
$options = array(
    'http' => array(
        'header' => $headers,
        'method' => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true,
    )
);

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(["error" => "Erro na requisição"]);
} else {
    $rows = str_getcsv($response, "\n");
    $header = str_getcsv(array_shift($rows)); // Pega o cabeçalho
    $dataArray = [];

    foreach ($rows as $row) {
        $rowData = str_getcsv($row);
        $dataArray[] = array_combine($header, $rowData); // Combina cabeçalho com os dados
    }

    // Retorna os dados em formato JSON
    echo json_encode($dataArray);
}
?>

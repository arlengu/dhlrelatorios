<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$url = 'https://score-msc.mysupplychain.dhl.com/score_msc/external/V1/report/160590/run/sync?Content-Type=application%2Fjson&Accept=text%2Fcsv';

$data = array(
    'myQuery' => [" 
    WITH nota_carreta AS (
        SELECT
            TRLR_ID,
            LISTAGG(NOTTXT, '
            '  ) WITHIN GROUP (ORDER BY NOTLIN) AS NOTTXT
        FROM TRLR_NOTE
        GROUP BY TRLR_ID
    ),
    
    resumo AS (
        SELECT
            SUM(rcl.EXPQTY) AS EXPQTY_tt, 
            SUM(rcl.IDNQTY) AS IDNQTY_tt, 
            rcl.invnum
        FROM
            rcvlin rcl
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
    LEFT JOIN resumo ON RESUMO.invnum = rci.invnum
    LEFT JOIN RCVtrk rct ON rci.TRKNUM = rct.TRKNUM
    LEFT JOIN trlr tr ON tr.trlr_id = rct.trlr_id
    LEFT JOIN dscmst dsts ON dsts.colval = tr.trlr_stat AND dsts.colnam = 'trlr_stat' AND dsts.locale_id = 'US_ENGLISH'
    LEFT JOIN dscmst dstt ON dstt.colnam = 'trlr_typ' AND dstt.LOCALE_ID = 'US_ENGLISH' AND dstt.colval = tr.trlr_typ
    LEFT JOIN nota_carreta ntc ON ntc.TRLR_ID = tr.trlr_id
    WHERE
        trlr_stat <> 'D' AND trlr_stat <> 'C'
    "],
    'body' => ['']
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
    exit;
}

file_put_contents('response_log.txt', $response);

if (strpos($response, '<html') !== false) {
    echo json_encode(["error" => "Erro no serviço externo: resposta HTML recebida."]);
    exit;
}

$rows = str_getcsv($response, "\n");
$dataArray = [];

if (empty($rows) || count($rows) < 2) {
    echo json_encode(["error" => "Relatório não encontrado."]);
    exit;
}

foreach ($rows as $row) {
    $dataArray[] = str_getcsv($row);
}

echo json_encode($dataArray);
?>

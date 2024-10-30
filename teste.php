<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$url = 'https://score-msc.mysupplychain.dhl.com/score_msc/external/V1/report/160590/run/sync?Content-Type=application%2Fjson&Accept=text%2Fcsv';

// Verifica se o número da nota foi enviado via POST
if (!isset($_POST['invnum'])) {
    echo json_encode(["error" => "Número da nota não informado."]);
    exit;
}

$invnum = $_POST['invnum']; // Captura o valor do POST

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

// Adiciona a resposta em um log para depuração
file_put_contents('response_log.txt', $response);

// Verifica se a resposta é HTML (o que indica erro)
if (strpos($response, '<html') !== false) {
    echo json_encode(["error" => "Erro no serviço externo: resposta HTML recebida."]);
    exit;
}

// Processa a resposta CSV
$rows = str_getcsv($response, "\n");
$dataArray = [];

// Verifica se a resposta está vazia ou contém erro
if (empty($rows) || count($rows) < 2) {
    echo json_encode(["error" => "Relatório não encontrado."]);
    exit;
}

foreach ($rows as $row) {
    $dataArray[] = str_getcsv($row);
}

// Retorna os dados em formato JSON
echo json_encode($dataArray);
?>

<?php
$url = 'https://score-msc.mysupplychain.dhl.com/score_msc/external/V1/report/160590/run/sync?Content-Type=application%2Fjson&Accept=text%2Fcsv';

// Verifica se o número da nota foi enviado via POST
$invnum = $_POST['invnum']; // Captura o valor do POST

$data = array(
    'myQuery' => ["WITH nota_carreta  AS (
    select
    TRLR_ID,
    LISTAGG( NOTTXT, '
    '  ) WITHIN GROUP (ORDER BY NOTLIN  ) NOTTXT
 
    from TRLR_NOTE
    --WHERE TRLR_ID = 'TRL0550169'
    GROUP BY TRLR_ID
),
 
resumo AS (
 
select
 
SUM(rcl.EXPQTY)  EXPQTY_tt, --QUANTIDADE ESPERADA TOTAL
SUM(rcl.IDNQTY)  IDNQTY_tt, --QUANTIDADE IDENTIFICADA TOTAL
rcl.invnum
 FROM
rcvlin rcl
left join prtdsc prd on  prd.locale_id = 'US_ENGLISH' and prd.colval = rcl.prtnum || '|RBCCID|RCKT'
left join rcvinv rci on rci.invnum = rcl.invnum
left join RCVtrk rct on rci.TRKNUM = rct.TRKNUM
left join trlr tr on tr.trlr_id = rct.trlr_id
left join dscmst dsts on dsts.colval = tr.trlr_stat and dsts.colnam = 'trlr_stat' and dsts.locale_id = 'US_ENGLISH'
left join dscmst dsis  on dsis.colnam = 'invsts' and dsis.colval = rcl.rcvsts AND dsis.LOCALE_ID = 'US_ENGLISH'
 
 GROUP BY rcl.invnum
 )
 
select
rci.invnum,
 tr.trlr_num, dsts.lngdsc trlr_stat, tr.trlr_broker, tr.driver_nam, tr.DRIVER_LIC_NUM, dstt.LNGDSC trlr_typ, TR.TRLR_ID ,
 ntc.NOTTXT , TR.YARD_LOC,  TR.TRACTOR_NUM, TR.TRLR_SEAL1, TR.TRLR_SEAL2, TR.TRLR_SEAL3,
RESUMO.EXPQTY_tt, --QUANTIDADE ESPERADA TOTAL
RESUMO.IDNQTY_tt --QUANTIDADE IDENTIFICADA TOTAL
 FROM
rcvinv rci
LEFT JOIN resumo ON RESUMO.invnum  = rci.invnum
left join RCVtrk rct on rci.TRKNUM = rct.TRKNUM
left join trlr tr on tr.trlr_id = rct.trlr_id
left join dscmst dsts on dsts.colval = tr.trlr_stat and dsts.colnam = 'trlr_stat' and dsts.locale_id = 'US_ENGLISH'
LEFT JOIN dscmst dstt ON dstt.colnam = 'trlr_typ' and dstt.LOCALE_ID = 'US_ENGLISH' and dstt.colval = tr.trlr_typ
left join nota_carreta ntc on ntc.TRLR_ID = tr.trlr_id
where
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
} else {
    $rows = str_getcsv($response, "\n");
    $header = str_getcsv(array_shift($rows)); // Pega o cabeçalho
    $dataArray = [];

    foreach ($rows as $row) {
        $rowData = str_getcsv($row);
        $dataArray[] = array_combine($header, $rowData); // Combina cabeçalho com os dados
    }

    // Retorna os dados em formato JSON, considerando que o primeiro item é o que você quer
    echo json_encode($dataArray);
}
?>



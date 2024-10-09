<?php
$url = 'https://score-msc.mysupplychain.dhl.com/score_msc/external/V1/report/160590/run/sync';

// Verifica se o número da nota foi enviado via POST
$invnum = isset($_POST['invnum']) ? $_POST['invnum'] : '8802889342'; // Valor padrão se não houver entrada

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
    rci.invnum = '{$invnum}'"],
    'body' => ['']
);

$user = 'arbarret';
$password = '3KT8zx203@Brasil1';
$credenciais = $user . ':' . $password;
$credenciaisBase64 = base64_encode($credenciais);

$headers = array(
    'Authorization: Basic ' . $credenciaisBase64,
    'Content-Type: application/json',
    'Accept: text/csv'
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
    echo json_encode(['error' => 'Erro na requisição']);
} else {
    // Converter CSV para JSON e retornar as colunas necessárias
    $lines = explode(PHP_EOL, $response);
    $header = str_getcsv(array_shift($lines));
    $result = [];

    foreach ($lines as $line) {
        if (!empty($line)) {
            $row = array_combine($header, str_getcsv($line));
            // Adiciona as colunas desejadas ao resultado
            $result[] = [
                'trlr_num' => $row['TRLR_NUM'] ?? '',
                'invnum' => $row['INVNUM'] ?? '',
                'trlr_broker' => $row['TRLR_BROKER'] ?? '',
                'driver_nam' => $row['DRIVER_NAM'] ?? '',
                'DRIVER_LIC_NUM' => $row['DRIVER_LIC_NUM'] ?? '',
                'trlr_typ' => $row['TRLR_TYP'] ?? '',
                'NOTTXT' => $row['NOTTXT'] ?? '',
                'YARD_LOC' => $row['YARD_LOC'] ?? '',
                'TRACTOR_NUM' => $row['TRACTOR_NUM'] ?? '',
                'TRLR_SEAL1' => $row['TRLR_SEAL1'] ?? '',
                'TRLR_SEAL2' => $row['TRLR_SEAL2'] ?? '',
                'TRLR_SEAL3' => $row['TRLR_SEAL3'] ?? ''
            ];
        }
    }

    // Exibir a resposta em formato JSON
    echo json_encode($result);
}
?>

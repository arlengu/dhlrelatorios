<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist de Veículo</title>
    <?php
        include 'Configuracoes/headgerais.php';
        renderHead("Check-list de veículo");
    ?>
    <style>
        @media print {
            @page {
                margin: 0; /* Remove margens padrão da impressão */
            }
            body {
                margin: 0;
                padding: 0; /* Remove o padding para preencher totalmente a página */
                width: 210mm; /* A4 */
                height: 297mm; /* A4 */
                box-sizing: border-box;
            }
            .buttons {
                display: none !important; /* Esconde os botões na impressão */
            }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative; /* Para posicionar os botões */
        }
        .a4-container {
            width: 210mm; /* A4 */
            height: 297mm; /* A4 */
            background: white;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 20px;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .left {
            flex: 3;
            padding-right: 20px;
            border-right: 2px;
        }
        .right {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .box {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
            background-color: #fff;
            height: auto; /* Altura ajustável */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
        }
        .signature {
            margin-top: 20px;
            text-align: start;
        }
        .line {
            border-bottom: 1px solid #000;
            margin: 5px 0;
            width: 100%;
            text-align: start;
        }
        .buttons {
            position: absolute; /* Posiciona os botões em relação ao corpo */
            top: 20px; /* Distância do topo */
            left: 20px; /* Distância da esquerda */
            z-index: 1000; /* Garante que os botões fiquem acima de outros elementos */
        }
        .button {
            display: block; /* Faz os botões ficarem em bloco */
            width: 100%; /* Largura total do botão */
            padding: 10px 15px;
            background-color: #007BFF; /* Cor do botão */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px; /* Espaço entre os botões */
        }
        .button:hover {
            background-color: #0056b3; /* Cor ao passar o mouse */
        }
    </style>
</head>
<body>

<div class="buttons">
    <button class="button btn btn-primary" onclick="window.history.back()"><i class="fa-solid fa-left-long"></i></button>
    <button class="button btn btn-primary" style="margin-top: 20px;" onclick="window.print()"><i class="fa-solid fa-print"></i></button>
</div>

    <div class="a4-container">
        <h1>Check List do veículo - Inbound</h1>

        <div class="container">
            <div class="left">
                <p style="font-size: 12px;"><strong>Data e hora da impressão:</strong> <?= date('d/m/Y H:i'); ?> <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Check-in:</strong> <?= date('d/m/Y H:i'); ?></p>
                <p style="font-size: 12px; padding-top: 20px;"><strong>Placa da carreta:</strong> <?= htmlspecialchars($_POST['placa'] ?? 'N/A'); ?></p>
                <p style="font-size: 12px;"><strong>Invoiced:</strong> <?= htmlspecialchars($_POST['invoice'] ?? 'N/A'); ?></p>
                <p style="font-size: 12px; padding-top: 20px;"><strong>Load:</strong> N/A</p>
                <p style="font-size: 12px;"><strong>Shipment(s):</strong> N/A</p>
                <p style="font-size: 12px; padding-top: 20px;"><strong>Local do veículo:</strong> <?= htmlspecialchars($_POST['doca'] ?? 'N/A'); ?></p>
                <p style="font-size: 12px;"><strong>Nome da transportadora:</strong> <?= htmlspecialchars($_POST['transportadora'] ?? 'N/A'); ?></p>
                <p style="font-size: 12px;"><strong>Tipo veículo:</strong> CARRETA BAU</p>
                <p style="font-size: 12px;"><strong>Nome do motorista:</strong> FABIO FERNANDO DA SILVA</p>
                <p style="font-size: 12px;"><strong>CNH:</strong> 99999999999</p>
                <p style="font-size: 12px;"><strong>Comentario:</strong> <?= htmlspecialchars($_POST['comentario'] ?? 'N/A'); ?></p>
                <p>_____________________________________________</p>
                <p style="font-size: 12px; padding-top: 10px;"><strong>Data do agendamento:</strong> N/A</p>
                <p style="font-size: 12px;"><strong>Comentário:</strong> NF 101010 - 102030 TODDY 7925</p>
                <p style="font-size: 12px;padding-top: 10px;"><strong>N Lacre:</strong> 602943</p>
                <p style="font-size: 12px;"><strong>Lacre:</strong> 602579</p>
                <p style="font-size: 12px;"><strong>Lacre:</strong> 50979</p>
                <p style="font-size: 12px;"><strong>Quantidade Pallets Cheap:</strong> 65</p>

                <div class="signature" style="padding-top: 2px;">
                    <p style="font-size: 12px;">Assinatura do Motorista</p>
                    <img src="66bcef6d8e508_signature.png" alt="Assinatura do Motorista" style="height: 40px;"/> <!-- Ajuste a altura conforme necessário -->
                    <p style="font-size: 12px;">23/05/2024 às 14:27</p>
                    <p style="font-size: 12px; padding-top: 10px;">Assinatura Operações</p>
                    <img src="66be0d417c0d6_signature.png" alt="Assinatura Operações" style="height: 40px;"/> <!-- Ajuste a altura conforme necessário -->
                    <p style="font-size: 12px;">23/05/2024 às 14:00</p>
                </div>

            </div>

            <div class="right">
                <div class="box">
                    Controle - Pallets (Operação)
                    <table style="padding-top: 10px;">
                        <thead>
                            <tr>
                                <th>DT</th>
                                <th>CHEAP</th>
                                <th>ECOBOX</th>
                                <th>PBR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10602963</td>
                                <td>28</td>
                                <td>N/A</td>
                                <td>N/A</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box">
                    Vistoria / Entrada - Segurança Patrimonial
                    <div class="line" style="padding-top: 10px;">Nome: Diogo Silva Cardoso</div>
                    <div class="line">Data: 23/09/2024</div>
                    <div class="line">Hora: 18:05</div>
                </div>
                <div class="box">
                    Recebimento Frontline
                    <div class="line" style="padding-top: 10px;">Nome: Gustavo Ferreira Silva</div>
                    <div class="line">Data: 23/09/2024</div>
                    <div class="line">RE: 989898</div>
                    <div class="line">Hora Início: 09:00</div>
                    <div class="line">Hora Término: 10:00</div>
                    <div class="line">Turno: 2° Turno</div>
                </div>
                <div class="box">
                    Despacho / Checkout
                    <div class="line" style="padding-top: 10px;">Nome: Ricardo Ferraz Torres</div>
                    <div class="line">Data: 21/09/2024</div>
                    <div class="line">RE: 985697</div>
                    <div class="line">Hora: 13:21</div>
                    <div class="line">Turno: 1° Turno</div>
                </div>
                <div class="box">
                    Validação de Lacre - Segurança Patrimonial
                    <div class="line" style="padding-top: 10px;">Nome: Silvana Pereira Silva</div>
                    <div class="line">Data: 23/09/2024</div>
                    <div class="line">Hora: 23:50</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

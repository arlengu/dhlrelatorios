
<?php

// Proteção contra XSS
// Função para escapar caracteres especiais em HTML. Isso ajuda a prevenir ataques Cross-Site Scripting (XSS) ao garantir que caracteres especiais em strings não sejam interpretados como código HTML.
function escape_html($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include 'Configuracoes/headgerais.php';
        renderHead("Usuários");
    ?>

    <style>
        .pagination {
            display: flex;
            justify-content: end;
            margin-top: 20px;
        }
        .signature-wrapper {
            border: 2px solid #ccc;
            border-radius: 5px;
            position: relative;
            display: none;
            padding: 10px;
            width: 100%;
        }
        .signature-pad {
            width: 100%;
            height: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .icon-btn {
            position: relative; /* Para posicionar a tooltip corretamente */
            margin-left: 10px;
            width: 30px; /* Tamanho do círculo */
            height: 30px; /* Tamanho do círculo */
            border-radius: 50%;
            border: 1px solid #ddd;
            background-color: #fff;
            transition: background-color 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .icon-btn i {
            color: orange;
            font-size: 16px; /* Tamanho do ícone */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        .icon-btn:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<div id="global-loader">
    <div class="whirly-loader"></div>
</div>

<div class="main-wrapper">
    <!-- Header -->
    <?php include 'Configuracoes/header.php'; ?>
    <!-- /Header -->

    <!-- Sidebar -->
    <?php include 'Configuracoes/menulateral.php'; ?>
    <!-- /Sidebar -->

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Cadastrar</h4>
                    <h6>Cadastre um novo EPI</h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="epiForm" enctype="multipart/form-data">
                        <!-- Campo CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '', ENT_QUOTES, 'UTF-8'); ?>">


                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Digite a Invoice / Placa</label>
                                    <input type="text" id="invnum" name="invnum" class="form-control">
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="form-group d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" onclick="pesquisar()"> Pesquisar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="epiForm" enctype="multipart/form-data">
                        <!-- Campo CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '', ENT_QUOTES, 'UTF-8'); ?>">


                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Placa do veículo</label>
                                    <input type="text" id="trlr_num" name="trlr_num" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Invoice</label>
                                    <input type="text" id="invoice" name="invoice" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Transportadora</label>
                                    <input type="text" id="trlr_broker" name="trlr_broker" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Motorista</label>
                                    <input type="text" id="driver_nam" name="driver_nam" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Carteira do motorista</label>
                                    <input type="text" id="driver_lic_num" name="driver_lic_num" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Tipo do veículo</label>
                                    <input type="text" id="trlr_typ" name="trlr_typ" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Comentário</label>
                                    <input type="text" id="nottxt" name="nottxt" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Doca</label>
                                    <input type="text" id="yard_loc" name="yard_loc" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Pager</label>
                                    <input type="text" id="tractor_num" name="tractor_num" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Lacre 1</label>
                                    <input type="text" id="trlr_seal1" name="trlr_seal1" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Lacre 2</label>
                                    <input type="text" id="trlr_seal2" name="trlr_seal2" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="codigoEpi"> Lacre 3</label>
                                    <input type="text" id="trlr_seal3" name="trlr_seal3" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adicionar o estilo CSS -->
<style>
    .modal-content {
        background: #fff;
    }
    .modal-header {
        background: #fff;
        border-bottom: 1px solid #dee2e6;
    }
    .modal-title {
        font-weight: 600;
    }
    .modal-body {
        text-align: center;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }
    .img-fluid {
        max-height: 80vh; /* Ajuste o valor conforme necessário */
        object-fit: contain;
    }
</style>
        
<?php
    include 'Configuracoes/scriptsgerais.php';
?>

<script>
        function pesquisar() {
            const invnum = document.getElementById('invnum').value;

            const resultadoInputs = {
                trlr_num: document.getElementById('trlr_num'),
                invnum: document.getElementById('invoice'),
                trlr_broker: document.getElementById('trlr_broker'),
                driver_nam: document.getElementById('driver_nam'),
                driver_lic_num: document.getElementById('driver_lic_num'),
                trlr_typ: document.getElementById('trlr_typ'),
                nottxt: document.getElementById('nottxt'),
                yard_loc: document.getElementById('yard_loc'),
                tractor_num: document.getElementById('tractor_num'),
                trlr_seal1: document.getElementById('trlr_seal1'),
                trlr_seal2: document.getElementById('trlr_seal2'),
                trlr_seal3: document.getElementById('trlr_seal3'),
            };

            fetch('consulta.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `invnum=${encodeURIComponent(invnum)}`
            })
            .then(response => response.json())
            .then(data => {
                console.log('Dados retornados:', data);
                if (data.length > 0) {
                    const result = data[0];
                    resultadoInputs.trlr_num.value = result.trlr_num || 'N/A';
                    resultadoInputs.invnum.value = result.invnum || 'N/A';
                    resultadoInputs.trlr_broker.value = result.trlr_broker || 'N/A';
                    resultadoInputs.driver_nam.value = result.driver_nam || 'N/A';
                    resultadoInputs.driver_lic_num.value = result.DRIVER_LIC_NUM || 'N/A';
                    resultadoInputs.trlr_typ.value = result.trlr_typ || 'N/A';
                    resultadoInputs.nottxt.value = result.NOTTXT || 'N/A';
                    resultadoInputs.yard_loc.value = result.YARD_LOC || 'N/A';
                    resultadoInputs.tractor_num.value = result.TRACTOR_NUM || 'N/A';
                    resultadoInputs.trlr_seal1.value = (result.TRLR_SEAL1 === 'NA') ? 'N/A' : result.TRLR_SEAL1;
                    resultadoInputs.trlr_seal2.value = (result.TRLR_SEAL2 === 'NA') ? 'N/A' : result.TRLR_SEAL2;
                    resultadoInputs.trlr_seal3.value = (result.TRLR_SEAL3 === 'NA') ? 'N/A' : result.TRLR_SEAL3;
                } else {
                    Object.values(resultadoInputs).forEach(input => input.value = 'N/A');
                    alert('Nenhum resultado encontrado');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro na pesquisa');
            });
        }
    </script>



</body>
</html>

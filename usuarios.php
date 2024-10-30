


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
            margin-top: 20px; /* Espaço entre a paginação e a tabela */
        }
    </style>

    <style>
.dataTables_filter {
    display: none; /* Oculta o campo de busca padrão do DataTable */
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

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Lista de Usuários</h4>
                    <h6>Gerenciamento de usuários cadastrados</h6>
                </div>
                <!--
                <div class="page-btn">
                    <button type="button" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#cadastrarusuariomodal">
                        <img src="assets/img/icons/plus.svg" alt="img"> Cadastrar Usuário
                    </button>
                </div>-->
            </div>

            <!-- User Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-path">
                                <a class="btn btn-filter" id="filter_search">
                                    <img src="assets/img/icons/filter.svg" alt="img">
                                    <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                                </a>
                            </div>
                            <div>
                                <input type="text" class="form-control" id="global_search" placeholder="Buscar na tabela...">
                            </div>
                        </div>
                        <div class="wordset">
                            <ul>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="assets/img/icons/pdf.svg" alt="img"></a>
                                </li>
                                <li>
                                    <a id="excelButton" data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="assets/img/icons/excel.svg" alt="img"></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="assets/img/icons/printer.svg" alt="img"></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" id="filter_nome" placeholder="Digite o nome">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" id="filter_email" placeholder="Digite o e-mail">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" id="filter_status" placeholder="Digite o status">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="text" id="filter_data_criacao" class="datetimepicker cal-icon" placeholder="Escolha a data de criação">
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                                    <div class="form-group">
                                        <a class="btn btn-filters ms-auto" id="apply_filters"><img src="assets/img/icons/search-whites.svg" alt="img"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="userTable">
                            <thead>
                                <tr>
                                    <th>
                                    <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                    </label>
                                    </th>
                                    <th>LPN</th>
                                    <th>SKU</th>
                                    <th>Local</th>
                                    <th>Lote</th>
                                    <th>Quantidade</th>
                                    <th>Status Rec</th>
                                    <th>Data de venc</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                            
</tbody>

                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="pagination-container">
                    <ul class="pagination">
                            <!-- Links de página serão adicionados dinamicamente -->
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
</div>


<script src="assets/js/jquery-3.6.0.min.js"></script>

<script src="assets/js/feather.min.js"></script>

<script src="assets/js/jquery.slimscroll.min.js"></script>

<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/dataTables.bootstrap4.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/plugins/select2/js/select2.min.js"></script>

<script src="assets/js/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
<script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

<script src="assets/js/script.js"></script>

<script>
$(document).ready(function() {
    // Função para inicializar o DataTable
    function inicializarDataTable() {
        const table = $('#userTable').DataTable({
            paging: false,
            searching: true,
            info: true,
            lengthChange: false,
            pageLength: 10,
            language: {
                paginate: {
                    previous: "Anterior",
                    next: "Próximo"
                },
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "Nenhum registro encontrado",
                emptyTable: "Nenhum dado disponível na tabela",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros totais)"
            }
        });

        // Evento de busca customizado
        $('#global_search').on('keyup', function() {
            table.search(this.value).draw();
        });
    }

    // Função para carregar dados na tabela
    function loadData() {
    $('#global-loader').show();

    $.ajax({
        url: 'teste.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            var tbody = $('#userTable tbody');
            tbody.empty();

            let currentSKU = null;
            let totalExpected = 0;
            let totalReceived = 0;
            let description = '';

            $.each(data, function(index, row) {
                if (index === 0) return; // Ignora a primeira linha (cabeçalho)

                const sku = row[1]; // SKU
                const quantidade = parseInt(row[4]); // QUANTIDADE
                const status = row[5]; // STATUS
                const local = row[2]; // LOCAL
                const lote = row[3]; // LOTE
                const dataVencimento = row[6]; // DATA VENCIMENTO
                
                // Se mudamos de SKU, adicionamos a linha de totais
                if (currentSKU && currentSKU !== sku) {
                    tbody.append(`
                        <tr style="background-color: #f2f0f0">
                            <td><label class="checkboxs" style="display: none"><input type="checkbox"><span class="checkmarks"></span></label></td>
                            <td><strong>Descrição do SKU:</strong> ${description}</td>
                            <td><strong>Total esperado:</strong> ${totalExpected}</td>
                            <td><strong>Total recebido:</strong> ${totalReceived}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    `);
                    
                    // Resetar totais
                    totalExpected = 0;
                    totalReceived = 0;
                }

                // Atualizar o SKU atual e somar as quantidades
                currentSKU = sku;
                description = row[12]; // DESCRIÇÃO DO SKU
                totalExpected = row[9]; // TOTAL ESPERADO
                totalReceived = row[10]; // TOTAL RECEBIDO
                // Adicionar linha do item
                tbody.append(`
                    <tr>
                        <td><label class="checkboxs"><input type="checkbox"><span class="checkmarks"></span></label></td>
                        <td>${row[0]}</td>
                        <td>${sku}</td>
                        <td>${local}</td>
                        <td>${lote}</td>
                        <td>${quantidade}</td>
                        <td>${status}</td>
                        <td>${dataVencimento}</td>
                        <td class="text-center">
                            <a class="action-set" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="si si-user me-2" style="font-size: 20px;"></i>Adicionar comentário
                                </a>
                            </div>
                        </td>
                    </tr>
                `);
            });

            // Adicionar a última linha de totais
            if (currentSKU) {
                tbody.append(`
                    <tr style="background-color: #f2f0f0">
                        <td><label class="checkboxs" style="display: none"><input type="checkbox"><span class="checkmarks"></span></label></td>
                        <td><strong>Descrição do SKU:</strong> ${description}</td>
                        <td><strong>Total esperado:</strong> ${totalExpected}</td>
                        <td><strong>Total recebido:</strong> ${totalReceived}</td>
                        <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                    </tr>
                `);
            }

            inicializarDataTable(); // Inicializa o DataTable após preencher os dados
        },
        error: function(xhr, status, error) {
            console.error("Erro ao carregar os dados: " + error);
        },
        complete: function() {
            $('#global-loader').hide();
        }
    });
}


    // Carregar os dados na tabela ao iniciar a página
    loadData();
});
</script>






</body>
</html>

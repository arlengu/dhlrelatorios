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
        renderHead("Check-list de veículo");
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
        .dataTables_filter {
    display: none;
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

<div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; text-align:center; padding-top:20%;">
    <div style="color:white; font-size:20px;">Carregando...</div>
</div>


    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Auditoria</h4>
                    <h6>Relatório de auditoria de inbound</h6>
                </div>
            </div>

<!-- User Table -->
<div class="card tabela">
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
                                    <th>Placa</th>
                                    <th>Invoice</th>
                                    <th>Doca</th>
                                    <th>Transportadora</th>
                                    <th>Status</th>
                                    <th>Comentarios</th>
                                    <th>Checklist</th>
                                    <th>Auditoria</th>
                                    <th>Comentários</th>
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
    function inicializarDataTable() {
        const table = $('#userTable').DataTable({
            paging: false,
            searching: true,
            info: true,
            lengthChange: false,
            pageLength: 10,
            language: {
                paginate: { previous: "Anterior", next: "Próximo" },
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                zeroRecords: "Nenhum registro encontrado",
                emptyTable: "Nenhum dado disponível na tabela",
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                infoFiltered: "(filtrado de _MAX_ registros totais)"
            }
        });

        $('#global_search').on('keyup', function() {
            table.search(this.value).draw();
        });
    }

    $.ajax({
        url: 'carregar_checklist.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                Swal.fire("Erro", data.error, "error");
                return;
            }
            var tableBody = $('#userTable tbody');
            tableBody.empty(); 

            $.each(data, function(index, item) {
                var row = `
                    <tr data-placa="${escape_html(item.placa)}" 
                        data-invoice="${escape_html(item.invoice)}" 
                        data-doca="${escape_html(item.doca)}" 
                        data-transportadora="${escape_html(item.transportadora)}" 
                        data-status="${escape_html(item.status)}" 
                        data-comentario="${escape_html(item.comentario)}">
                        <td>
                            <label class="checkboxs">
                                <input type="checkbox">
                                <span class="checkmarks"></span>
                            </label>
                        </td>
                        <td>${escape_html(item.placa)}</td>
                        <td>${escape_html(item.invoice)}</td>
                        <td>${escape_html(item.doca)}</td>
                        <td>${escape_html(item.transportadora)}</td>
                        <td>${escape_html(item.status)}</td>
                        <td>${escape_html(item.comentario)}</td>
                        <td>
                            <button class="btn btn-primary view-doc">
                                <i class="fa-solid fa-file-lines" style="color: white;"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-primary send-pdf">
                                <i class="fa-solid fa-check" style="color: white;"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-primary view-comment">
                                <i class="fa-solid fa-comment"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

            inicializarDataTable();

            $('.view-doc, .view-comment, .send-pdf').click(function() {
                var row = $(this).closest('tr');
                var placa = row.data('placa');
                var invoice = row.data('invoice');
                var doca = row.data('doca');
                var transportadora = row.data('transportadora');
                var status = row.data('status');
                var comentario = row.data('comentario');

                // Cria um formulário para enviar os dados via POST
                var action;
                if ($(this).hasClass('view-doc')) {
                    action = 'doc.php';
                } else if ($(this).hasClass('view-comment')) {
                    action = 'carregar_comentarios.php';
                } else if ($(this).hasClass('send-pdf')) {
                    action = 'testepdf.php';
                }

                var form = $('<form>', { action: action, method: 'POST' })
                    .append($('<input>', { type: 'hidden', name: 'placa', value: placa }))
                    .append($('<input>', { type: 'hidden', name: 'invoice', value: invoice }))
                    .append($('<input>', { type: 'hidden', name: 'doca', value: doca }))
                    .append($('<input>', { type: 'hidden', name: 'transportadora', value: transportadora }))
                    .append($('<input>', { type: 'hidden', name: 'status', value: status }))
                    .append($('<input>', { type: 'hidden', name: 'comentario', value: comentario }));

                $('body').append(form);
                form.submit().remove(); // Envia e remove o formulário do DOM
            });
        },
        error: function() {
            Swal.fire("Erro", "Não foi possível carregar os dados.", "error");
        }
    });

    function escape_html(string) {
        return string ? string.replace(/&/g, "&amp;")
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;")
                            .replace(/"/g, "&quot;")
                            .replace(/'/g, "&#039;") : '';
    }
});
</script>





</script>

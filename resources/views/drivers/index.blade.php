@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Conteúdo principal da página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Motoristas</h2>
        <a href="{{ route('drivers.create') }}" class="btn btn-success">➕ Adicionar Motorista</a>
    </div>

    <table id="drivers-table" class="table table-striped">
        <!-- Cabeçalho da tabela de motoristas -->
        <thead>
            <tr>
                <th></th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>RG</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modais (mantidos da versão anterior) -->
@include('modals.image_modal')
@include('modals.analyze_modal')
@include('modals.block_modal')
@include('modals.balance_modal')


<!-- Estilos e Scripts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.4/dataRender/datetime.js"></script>
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
    /* Estilos para a tabela de motoristas */
    td.dt-control::before {
        content: "+";
        font-weight: bold;
        font-size: 18px;
        color: #198754;
        display: inline-block;
        text-align: center;
        width: 20px;
        cursor: pointer;
    }
    tr.shown td.dt-control::before {
        content: "−";
        color: #dc3545;
    }
    .password-hidden {
        font-family: 'monospace';
        letter-spacing: 2px;
    }
    
    /* Estilos para a tabela de transferências */
    tr.group-header {
        background-color: #f8f9fa !important;
        cursor: pointer;
    }
    tr.group-header:hover {
        background-color: #e9ecef !important;
    }
    tr.group-header td {
        font-weight: bold;
        font-size: 1.1em;
        padding: 8px 10px;
    }
    tr.group-day-header {
        background-color: #f1f1f1 !important;
        cursor: pointer;
    }
    tr.group-day-header:hover {
        background-color: #e2e2e2 !important;
    }
    tr.group-day-header td {
        font-weight: 600;
        padding: 6px 10px 6px 30px;
    }
    tr.group-detail {
        display: none;
    }
    tr.group-detail.shown {
        display: table-row;
    }
    .badge-pix {
        background-color: #20c997;
    }
    .badge-ted {
        background-color: #0d6efd;
    }
    .badge-doc {
        background-color: #0dcaf0;
    }
    .badge-internal {
        background-color: #6c757d;
    }
</style>

<script>
// Funções auxiliares (mantidas da versão anterior)
function maskRG(value) { /* ... */ }
function maskPhone(value) { /* ... */ }
function maskCPF(cpf) { /* ... */ }
function formatDateBR(dateStr) { /* ... */ }
function formatCurrency(value) { /* ... */ }
function openImageModal(src) { /* ... */ }
function renderImageColumn(title, src) { /* ... */ }
function updateDriverStatus(id, status) { /* ... */ }
function activateDriver(id, status) { /* ... */ }
function analyzeDriver(driverId) { /* ... */ }
function togglePassword(id, password) { /* ... */ }
function getStatusLabel(status) { /* ... */ }
function openWhatsApp(phone) { /* ... */ }
function format(d) { /* ... */ }

// Função para mostrar o modal de saldo com agrupamento por dia
function showBalanceModal(driverId) {
    const modal = new bootstrap.Modal('#balanceModal');
    
    // Limpa a tabela se já existir
    if ($.fn.DataTable.isDataTable('#transfersTable')) {
        $('#transfersTable').DataTable().destroy();
    }
    
    // Mostra loading
    $('#transfersTable tbody').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Carregando transferências...</p></td></tr>');
    modal.show();
    
    $.get(`/drivers/${driverId}/balance-data`, function(data) {
        // Atualiza os cards de saldo
        $('#asaasIdentifier').text(data.account.asaas_identifier || 'Não informado');
        $('#totalBalance').text(formatCurrency(data.account.total_balance));
        $('#blockedBalance').text(formatCurrency(data.account.blocked_balance));
        $('#availableBalance').text(formatCurrency(data.account.available_balance));
        
        // Agrupa transferências por data (ano-mês-dia)
        const groupedTransfers = {};
        data.transfers.forEach(transfer => {
            const dateKey = transfer.date_group;
            if (!groupedTransfers[dateKey]) {
                groupedTransfers[dateKey] = {
                    date: transfer.transfer_date,
                    day_name: transfer.day_name,
                    transfers: []
                };
            }
            groupedTransfers[dateKey].transfers.push(transfer);
        });

        // Ordena as datas (mais recente primeiro)
        const sortedDates = Object.keys(groupedTransfers).sort().reverse();
        
        // Prepara os dados para a DataTable
        const tableData = [];
        sortedDates.forEach(dateKey => {
            const group = groupedTransfers[dateKey];
            
            // Adiciona linha de grupo (dia)
            tableData.push({
                type: 'group',
                date: group.date,
                title: group.day_name,
                count: group.transfers.length,
                amount: group.transfers.reduce((sum, t) => sum + parseFloat(t.amount), 0)
            });
            
            // Adiciona as transferências do dia
            group.transfers.forEach(transfer => {
                tableData.push({
                    type: 'transfer',
                    ...transfer
                });
            });
        });

        // Inicializa a DataTable
        const table = $('#transfersTable').DataTable({
            data: tableData,
            columns: [
                { 
                    data: 'type',
                    render: function(data, type, row) {
                        if (data === 'group') {
                            return `<i class="bi bi-caret-down-fill me-2"></i> ${row.count} transferência(s)`;
                        }
                        const badgeClass = {
                            'PIX': 'badge-pix',
                            'TED': 'badge-ted',
                            'DOC': 'badge-doc',
                            'INTERNAL': 'badge-internal'
                        }[row.type] || 'badge-secondary';
                        return `<span class="badge ${badgeClass}">${row.type}</span>`;
                    }
                },
                { 
                    data: 'amount',
                    render: function(data, type, row) {
                        if (row.type === 'group') {
                            return `<strong>${formatCurrency(row.amount)}</strong>`;
                        }
                        return formatCurrency(data);
                    }
                },
                { data: 'description' },
                { 
                    data: 'transfer_date',
                    render: function(data, type, row) {
                        if (row.type === 'group') {
                            return row.title;
                        }
                        return new Date(data).toLocaleString('pt-BR');
                    }
                },
                { data: 'asaas_identifier' }
            ],
            order: [], // Desativa ordenação inicial
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            createdRow: function(row, data, dataIndex) {
                if (data.type === 'group') {
                    $(row).addClass('group-day-header');
                } else {
                    $(row).addClass('group-detail');
                }
            }
        });

        // Configura clique nos cabeçalhos de grupo
        $('#transfersTable tbody').on('click', 'tr.group-day-header', function() {
            const tr = $(this);
            const row = table.row(tr);
            const nextTr = tr.next('tr');
            
            while (nextTr.length && nextTr.hasClass('group-detail')) {
                nextTr.toggleClass('shown');
                nextTr = nextTr.next('tr');
            }
            
            // Altera o ícone
            const icon = tr.find('i');
            if (icon.hasClass('bi-caret-down-fill')) {
                icon.removeClass('bi-caret-down-fill').addClass('bi-caret-right-fill');
            } else {
                icon.removeClass('bi-caret-right-fill').addClass('bi-caret-down-fill');
            }
        });
        
        // Expande o primeiro grupo por padrão
        $('#transfersTable tbody tr.group-day-header').first().click();

    }).fail(function() {
        $('#transfersTable tbody').html('<tr><td colspan="5" class="text-center py-4 text-danger">Erro ao carregar transferências. Tente novamente.</td></tr>');
    });
}

// Inicialização da tabela de motoristas (mantida da versão anterior)
$(document).ready(function () {
    const table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('drivers.data') }}",
        columns: [
            { className: 'dt-control', orderable: false, data: null, defaultContent: '' },
            { data: 'name' },
            { data: 'address' },
            { data: 'identity_card', render: maskRG },
            { data: 'phone', render: maskPhone },
            {
                data: 'status',
                render: status => {
                    const [text, color] = getStatusLabel(status);
                    return `<span class="badge bg-${color}">${text}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: (data, type, row) => `
                    <div class="btn-group btn-group-sm">
                        <button onclick="showBalanceModal(${row.id})" class="btn btn-outline-success">💰 Saldo</button>
                        <a href="/drivers/${row.id}/freights" class="btn btn-outline-primary">🚚 Fretes</a>
                        <button onclick="activateDriver(${row.id}, '${row.status}')" class="btn btn-outline-${row.status === 'active' ? 'danger' : 'warning'}">
                            ${row.status === 'active' ? '🚫 Bloquear' : '✅ Ativar'}
                        </button>
                        <button class="btn btn-sm btn-info" onclick="analyzeDriver(${row.id})">🔍 Analisar</button>
                        <button class="btn btn-sm btn-success" onclick="openWhatsApp('${row.phone}')">💬 WhatsApp</button>
                    </div>
                `
            }
        ]
    });

    // Controle de expandir/recolher na tabela de motoristas
    $('#drivers-table tbody').on('click', 'td.dt-control', function () {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });

    // Controles do modal de bloqueio
    $('#blockUserBtn').click(() => updateDriverStatus(selectedDriverId, 'block'));
    $('#blockTransferBtn').click(() => updateDriverStatus(selectedDriverId, 'transfer_block'));
});
</script>

<!-- Ícones Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Motoristas</h2>
        <a href="{{ route('drivers.create') }}" class="btn btn-success">➕ Adicionar Motorista</a>
    </div>

    <table id="drivers-table" class="table table-striped">
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

<!-- Modais -->
@include('drivers.modals.image_modal')
@include('drivers.modals.analyze_modal')
@include('drivers.modals.block_modal')
@include('drivers.modals.balance_modal')

<!-- CSS e JavaScript -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.4/sorting/datetime-moment.js"></script>
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
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
// Variável global
let selectedDriverId = null;

// Funções auxiliares
function maskRG(value) {
    if (!value) return '';
    return value.replace(/^(\d{1,2})(\d{3})(\d{3})([\dxX])?$/, (_, p1, p2, p3, p4) => `${p1}.${p2}.${p3}${p4 ? '-' + p4 : ''}`);
}

function maskPhone(value) {
    if (!value) return '';
    return value.replace(/\D/g, '').replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
}

function maskCPF(cpf) {
    return cpf?.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4") || '';
}

function formatDateBR(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('pt-BR');
}

function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value || 0);
}

function togglePassword(id, password) {
    const span = document.getElementById(`password-${id}`);
    if (span.innerText === '••••••••') {
        span.innerText = password;
    } else {
        span.innerText = '••••••••';
    }
}

function getStatusLabel(status) {
    const labels = {
        'create': ['Aguardando Ativação', 'warning'],
        'active': ['Ativo', 'success'],
        'block': ['Bloqueado', 'danger'],
        'transfer_block': ['Transferências Bloqueadas', 'danger'],
    };
    return labels[status] || ['Desconhecido', 'secondary'];
}

function openWhatsApp(phone) {
    if (!phone) return alert("Número de telefone não disponível.");
    const formatted = phone.replace(/\D/g, '');
    window.open(`https://wa.me/55${formatted}`, '_blank');
}

function format(d) {
    let reason = '';
    if (d.status === 'block' || d.status === 'transfer_block') {
        reason = `<p><strong>Motivo:</strong> ${d.reason || 'Não informado'}</p>`;
    }

    return `
        <div class="p-3 bg-light rounded">
            <p><strong>Data de Nascimento:</strong> ${formatDateBR(d.birth_date)}</p>
            <p><strong>Estado Civil:</strong> ${d.marital_status}</p>
            <p><strong>CPF:</strong> ${maskCPF(d.cpf)}</p>
            <p><strong>CNH:</strong> ${d.driver_license_number}</p>
            <p><strong>Categoria CNH:</strong> ${d.driver_license_category}</p>
            <p><strong>Validade CNH:</strong> ${formatDateBR(d.driver_license_expiration)}</p>
            <p><strong>Status:</strong> ${getStatusLabel(d.status)[0]}</p>
            <p><strong>Senha:</strong> 
                <span id="password-${d.id}" class="password-hidden">••••••••</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="togglePassword('${d.id}', '${d.password}')">👁️</button>
            </p>
            ${reason}
            <div class="row">
                ${renderImageColumn('Frente CNH', d.driver_license_front)}
                ${renderImageColumn('Verso CNH', d.driver_license_back)}
                ${renderImageColumn('Foto do Rosto', d.face_photo)}
                ${renderImageColumn('Comprovante de Endereço', d.address_proof)}
            </div>
        </div>
    `;
}

function openImageModal(src) {
    $('#modalImage').attr('src', src);
    new bootstrap.Modal('#imageModal').show();
}

function renderImageColumn(title, src) {
    return `
        <div class="col-md-3 text-center mb-3">
            <p><strong>${title}</strong></p>
            <img src="${src}" class="img-fluid rounded" style="max-height:150px;" onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';"/>
            <br>
            <a href="${src}" download class="btn btn-sm btn-outline-primary mt-2">⬇ Baixar</a>
            <button class="btn btn-sm btn-outline-secondary mt-2" onclick="openImageModal('${src}')">🔍 Ampliar</button>
        </div>
    `;
}

function updateDriverStatus(id, status) {
    const reason = $('#blockReason').val().trim();

    if ((status === 'block' || status === 'transfer_block') && !reason) {
        toastr.warning('Por favor, informe o motivo do bloqueio.');
        return;
    }

    $.post(`/drivers/${id}/update-status`, {
        status,
        reason,
        _token: '{{ csrf_token() }}'
    }, () => {
        $('#drivers-table').DataTable().ajax.reload(null, false);
        bootstrap.Modal.getInstance(document.getElementById('blockModal'))?.hide();
        toastr.success(`Status atualizado para ${status}`);
        $('#blockReason').val('');
    }).fail(() => toastr.error("Erro ao atualizar status."));
}

function activateDriver(id, status) {
    if (status === 'active') {
        selectedDriverId = id;
        new bootstrap.Modal('#blockModal').show();
    } else {
        updateDriverStatus(id, 'active');
    }
}

function analyzeDriver(driverId) {
    const modal = new bootstrap.Modal('#analyzeModal');
    $('#analysisContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Aguarde enquanto a inteligência artificial realiza a análise...</p>
        </div>
    `);
    modal.show();

    $.get(`/drivers/${driverId}/analyze`, result => {
        $('#analysisContent').html(`
            <div class="alert alert-info">
                <h5>🧠 Resultado da Análise via IA:</h5>
                <p>${result.message.replace(/\n/g, "<br>")}</p>
            </div>
            <div class="row">
                ${renderImageColumn('Frente CNH', result.driver_license_front)}
                ${renderImageColumn('Comprovante de Endereço', result.address_proof)}
                ${renderImageColumn('Foto do Rosto', result.face_photo)}
            </div>
        `);
    }).fail(() => {
        $('#analysisContent').html(`<div class="alert alert-danger">❌ Erro na análise com IA.</div>`);
    });
}

function showBalanceModal(driverId) {
    const modal = new bootstrap.Modal('#balanceModal');
    
    if ($.fn.DataTable.isDataTable('#transfersTable')) {
        $('#transfersTable').DataTable().destroy();
    }
    
    $('#transfersTable tbody').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Carregando transferências...</p></td></tr>');
    modal.show();
    
    $.get(`/drivers/${driverId}/balance-data`, function(data) {
        $('#asaasIdentifier').text(data.account.asaas_identifier || 'Não informado');
        $('#totalBalance').text(formatCurrency(data.account.total_balance));
        $('#blockedBalance').text(formatCurrency(data.account.blocked_balance));
        $('#availableBalance').text(formatCurrency(data.account.available_balance));
        
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

        const sortedDates = Object.keys(groupedTransfers).sort().reverse();
        const tableData = [];
        
        sortedDates.forEach(dateKey => {
            const group = groupedTransfers[dateKey];
            
            tableData.push({
                type: 'group',
                date: group.date,
                title: group.day_name,
                count: group.transfers.length,
                amount: group.transfers.reduce((sum, t) => sum + parseFloat(t.amount), 0)
            });
            
            group.transfers.forEach(transfer => {
                tableData.push({
                    type: 'transfer',
                    ...transfer
                });
            });
        });

        $.fn.dataTable.moment('DD/MM/YYYY HH:mm:ss');
        
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
            order: [],
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

        $('#transfersTable tbody').on('click', 'tr.group-day-header', function() {
            const tr = $(this);
            let nextTr = tr.next('tr');
            
            while (nextTr.length && nextTr.hasClass('group-detail')) {
                nextTr.toggleClass('shown');
                nextTr = nextTr.next('tr');
            }
            
            const icon = tr.find('i');
            if (icon.hasClass('bi-caret-down-fill')) {
                icon.removeClass('bi-caret-down-fill').addClass('bi-caret-right-fill');
            } else {
                icon.removeClass('bi-caret-right-fill').addClass('bi-caret-down-fill');
            }
        });
        
        $('#transfersTable tbody tr.group-day-header').first().click();

    }).fail(function() {
        $('#transfersTable tbody').html('<tr><td colspan="5" class="text-center py-4 text-danger">Erro ao carregar transferências. Tente novamente.</td></tr>');
    });
}

// Inicialização da tabela principal
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

    $('#blockUserBtn').click(() => updateDriverStatus(selectedDriverId, 'block'));
    $('#blockTransferBtn').click(() => updateDriverStatus(selectedDriverId, 'transfer_block'));
});
</script>

@endsection
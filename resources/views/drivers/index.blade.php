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
    .transfer-header {
        background-color: #6c757d !important;
        color: white !important;
        font-weight: bold !important;
        cursor: pointer;
    }
    .year-group {
        background-color: #0d6efd !important;
        color: white !important;
        cursor: pointer;
    }
    .month-group {
        background-color: #0dcaf0 !important;
        color: black !important;
        cursor: pointer;
    }
    .day-group {
        background-color: #f8f9fa !important;
        cursor: pointer;
    }
    .transfer-detail {
        display: none;
    }
    .transfer-detail.shown {
        display: table-row;
    }
    
    /* Estilos por tipo de transferência */
    .available-transfer {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    .blocked-transfer {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    .debited-transfer {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    /* Ícones e badges */
    .type-available .bi-wallet2 {
        color: #0d6efd;
    }
    .type-blocked .bi-wallet2 {
        color: #ffc107;
    }
    .type-debited .bi-wallet2 {
        color: #198754;
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
    .badge-warning {
        background-color: #ffc107;
        color: black;
    }
    .badge-success {
        background-color: #198754;
    }
    
    /* Indentações */
    .indent-0 { padding-left: 5px !important; }
    .indent-1 { padding-left: 25px !important; }
    .indent-2 { padding-left: 45px !important; }
    .indent-3 { padding-left: 65px !important; }
    .indent-4 { padding-left: 85px !important; }
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
        
        // Agrupa transferências por tipo e data
        const groupedTransfers = {
            available: { name: 'Transferências Liberadas', transfers: [], total: 0 },
            blocked: { name: 'Transferências Bloqueadas', transfers: [], total: 0 },
            debited: { name: 'Transferências PIX Cliente', transfers: [], total: 0 }
        };
        
        data.transfers.forEach(transfer => {
            const date = new Date(transfer.transfer_date);
            const year = date.getFullYear();
            const month = date.getMonth() + 1;
            const day = date.getDate();
            
            // Determina o tipo de transferência
            let transferType = 'available'; // padrão
            if (transfer.description.includes('bloqueado') || transfer.type === 'BLOCKED') {
                transferType = 'blocked';
            } else if (transfer.description.includes('PIX cliente') || transfer.type === 'PIX_DEBIT') {
                transferType = 'debited';
            }
            
            // Adiciona ao grupo correspondente
            groupedTransfers[transferType].transfers.push({
                ...transfer,
                year,
                month,
                day,
                dateStr: date.toLocaleDateString('pt-BR')
            });
            groupedTransfers[transferType].total += parseFloat(transfer.amount);
        });

        // Prepara os dados para a DataTable
        const tableData = [];
        
        // Processa cada tipo de transferência
        Object.entries(groupedTransfers).forEach(([type, group]) => {
            if (group.transfers.length === 0) return;
            
            // Adiciona cabeçalho do tipo
            tableData.push({
                type: 'header',
                title: `${group.name} (Total: ${formatCurrency(group.total)})`,
                transferType: type,
                level: 0
            });
            
            // Agrupa por ano, mês e dia
            const byYear = {};
            group.transfers.forEach(t => {
                if (!byYear[t.year]) {
                    byYear[t.year] = {
                        year: t.year,
                        months: {},
                        total: 0,
                        count: 0
                    };
                }
                
                if (!byYear[t.year].months[t.month]) {
                    byYear[t.year].months[t.month] = {
                        month: t.month,
                        monthName: new Date(t.year, t.month-1, 1).toLocaleString('pt-BR', { month: 'long' }),
                        days: {},
                        total: 0,
                        count: 0
                    };
                }
                
                if (!byYear[t.year].months[t.month].days[t.day]) {
                    byYear[t.year].months[t.month].days[t.day] = {
                        day: t.day,
                        dateStr: t.dateStr,
                        transfers: [],
                        total: 0,
                        count: 0
                    };
                }
                
                byYear[t.year].total += parseFloat(t.amount);
                byYear[t.year].count++;
                byYear[t.year].months[t.month].total += parseFloat(t.amount);
                byYear[t.year].months[t.month].count++;
                byYear[t.year].months[t.month].days[t.day].total += parseFloat(t.amount);
                byYear[t.year].months[t.month].days[t.day].count++;
                byYear[t.year].months[t.month].days[t.day].transfers.push(t);
            });
            
            // Ordena anos
            const years = Object.keys(byYear).sort((a, b) => b - a);
            
            years.forEach(yearKey => {
                const yearData = byYear[yearKey];
                
                // Adiciona linha do ano
                tableData.push({
                    type: 'year',
                    title: `Ano ${yearKey}`,
                    total: yearData.total,
                    count: yearData.count,
                    transferType: type,
                    level: 1
                });
                
                // Ordena meses
                const months = Object.keys(yearData.months).sort((a, b) => b - a);
                
                months.forEach(monthKey => {
                    const monthData = yearData.months[monthKey];
                    
                    // Adiciona linha do mês
                    tableData.push({
                        type: 'month',
                        title: `${monthData.monthName.charAt(0).toUpperCase() + monthData.monthName.slice(1)} ${yearKey}`,
                        total: monthData.total,
                        count: monthData.count,
                        transferType: type,
                        level: 2
                    });
                    
                    // Ordena dias
                    const days = Object.keys(monthData.days).sort((a, b) => b - a);
                    
                    days.forEach(dayKey => {
                        const dayData = monthData.days[dayKey];
                        
                        // Adiciona linha do dia
                        tableData.push({
                            type: 'day',
                            title: dayData.dateStr,
                            total: dayData.total,
                            count: dayData.count,
                            transferType: type,
                            level: 3
                        });
                        
                        // Adiciona transferências
                        dayData.transfers.forEach(transfer => {
                            tableData.push({
                                type: 'transfer',
                                ...transfer,
                                transferType: type,
                                level: 4
                            });
                        });
                    });
                });
            });
        });

        // Configura a DataTable
        $.fn.dataTable.moment('DD/MM/YYYY HH:mm:ss');
        
        const table = $('#transfersTable').DataTable({
            data: tableData,
            columns: [
                { 
                    data: 'type',
                    render: function(data, type, row) {
                        const indentClass = `indent-${row.level}`;
                        const typeClass = `type-${row.transferType}`;
                        
                        if (data === 'header') {
                            return `<div class="${indentClass} ${typeClass}"><i class="bi bi-wallet2 me-2"></i> <strong>${row.title}</strong></div>`;
                        } else if (data === 'year') {
                            return `<div class="${indentClass} ${typeClass}"><i class="bi bi-calendar-event me-2"></i> ${row.title}</div>`;
                        } else if (data === 'month') {
                            return `<div class="${indentClass} ${typeClass}"><i class="bi bi-calendar-month me-2"></i> ${row.title}</div>`;
                        } else if (data === 'day') {
                            return `<div class="${indentClass} ${typeClass}"><i class="bi bi-calendar-day me-2"></i> ${row.title}</div>`;
                        } else {
                            const badgeClass = {
                                'PIX': 'badge-pix',
                                'TED': 'badge-ted',
                                'DOC': 'badge-doc',
                                'INTERNAL': 'badge-internal',
                                'BLOCKED': 'badge-warning',
                                'PIX_DEBIT': 'badge-success'
                            }[row.type] || 'badge-secondary';
                            
                            return `<div class="${indentClass} ${typeClass}"><span class="badge ${badgeClass}">${row.type}</span></div>`;
                        }
                    }
                },
                { 
                    data: 'amount',
                    render: function(data, type, row) {
                        if (row.type !== 'transfer') {
                            return `<strong>${formatCurrency(row.total)} (${row.count})</strong>`;
                        }
                        return formatCurrency(data);
                    }
                },
                { 
                    data: 'description',
                    render: function(data, type, row) {
                        if (row.type !== 'transfer') return '';
                        return data;
                    }
                },
                { 
                    data: 'transfer_date',
                    render: function(data, type, row) {
                        if (row.type !== 'transfer') return '';
                        return new Date(data).toLocaleString('pt-BR');
                    }
                },
                { 
                    data: 'asaas_identifier',
                    render: function(data, type, row) {
                        if (row.type !== 'transfer') return '';
                        return data;
                    }
                }
            ],
            order: [],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            createdRow: function(row, data, dataIndex) {
                // Adiciona classes baseadas no tipo
                if (data.transferType === 'available') {
                    $(row).addClass('available-transfer');
                } else if (data.transferType === 'blocked') {
                    $(row).addClass('blocked-transfer');
                } else if (data.transferType === 'debited') {
                    $(row).addClass('debited-transfer');
                }
                
                // Adiciona classes para grupos
                if (data.type === 'header') {
                    $(row).addClass('transfer-header');
                } else if (data.type === 'year') {
                    $(row).addClass('year-group');
                } else if (data.type === 'month') {
                    $(row).addClass('month-group');
                } else if (data.type === 'day') {
                    $(row).addClass('day-group');
                } else {
                    $(row).addClass('transfer-detail');
                }
            }
        });

        // Configura clique para expandir/recolher
        $('#transfersTable tbody').on('click', '.transfer-header, .year-group, .month-group, .day-group', function() {
            const tr = $(this);
            const row = table.row(tr);
            let nextTr = tr.next('tr');
            const currentLevel = tr.hasClass('transfer-header') ? 0 : 
                               tr.hasClass('year-group') ? 1 :
                               tr.hasClass('month-group') ? 2 : 3;
            
            while (nextTr.length) {
                const nextLevel = nextTr.hasClass('transfer-header') ? 0 : 
                                 nextTr.hasClass('year-group') ? 1 :
                                 nextTr.hasClass('month-group') ? 2 :
                                 nextTr.hasClass('day-group') ? 3 : 4;
                
                if (nextLevel <= currentLevel) break;
                
                nextTr.toggleClass('shown');
                nextTr = nextTr.next('tr');
            }
            
            // Altera o ícone
            const icon = tr.find('i');
            if (icon.hasClass('bi-caret-down-fill')) {
                icon.removeClass('bi-caret-down-fill').addClass('bi-caret-right-fill');
            } else if (icon.hasClass('bi-caret-right-fill')) {
                icon.removeClass('bi-caret-right-fill').addClass('bi-caret-down-fill');
            }
        });
        
        // Expande todos os cabeçalhos inicialmente
        $('#transfersTable tbody .transfer-header').each(function() {
            $(this).click();
        });

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
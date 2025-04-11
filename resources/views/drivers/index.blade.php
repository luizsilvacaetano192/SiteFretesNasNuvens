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
    .year-group {
        cursor: pointer;
        font-weight: bold !important;
        background-color: #0d6efd !important;
        color: white !important;
    }
    .month-group {
        cursor: pointer;
        font-weight: 600 !important;
        background-color: #0dcaf0 !important;
        color: black !important;
    }
    .day-group {
        cursor: pointer;
        background-color: #f8f9fa !important;
    }
    .transfer-detail {
        display: none;
    }
    .transfer-detail.shown {
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
    .indent-1 {
        padding-left: 20px !important;
    }
    .indent-2 {
        padding-left: 40px !important;
    }
    .indent-3 {
        padding-left: 60px !important;
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
        
        // Agrupa transferências por ano, mês e dia
        const groupedTransfers = {};
        
        data.transfers.forEach(transfer => {
            const date = new Date(transfer.transfer_date);
            const year = date.getFullYear();
            const month = date.getMonth() + 1; // Janeiro é 0
            const day = date.getDate();
            
            // Cria estrutura hierárquica: ano -> mês -> dia
            if (!groupedTransfers[year]) {
                groupedTransfers[year] = {
                    year: year,
                    months: {},
                    total: 0,
                    count: 0
                };
            }
            
            if (!groupedTransfers[year].months[month]) {
                groupedTransfers[year].months[month] = {
                    month: month,
                    monthName: date.toLocaleString('pt-BR', { month: 'long' }),
                    days: {},
                    total: 0,
                    count: 0
                };
            }
            
            if (!groupedTransfers[year].months[month].days[day]) {
                groupedTransfers[year].months[month].days[day] = {
                    day: day,
                    dateStr: date.toLocaleDateString('pt-BR'),
                    transfers: [],
                    total: 0,
                    count: 0
                };
            }
            
            // Adiciona a transferência
            groupedTransfers[year].months[month].days[day].transfers.push(transfer);
            
            // Atualiza totais
            const amount = parseFloat(transfer.amount);
            groupedTransfers[year].total += amount;
            groupedTransfers[year].count++;
            groupedTransfers[year].months[month].total += amount;
            groupedTransfers[year].months[month].count++;
            groupedTransfers[year].months[month].days[day].total += amount;
            groupedTransfers[year].months[month].days[day].count++;
        });

        // Prepara os dados para a DataTable
        const tableData = [];
        
        // Ordena anos (decrescente)
        const years = Object.keys(groupedTransfers).sort((a, b) => b - a);
        
        years.forEach(yearKey => {
            const yearData = groupedTransfers[yearKey];
            
            // Adiciona linha do ano
            tableData.push({
                type: 'year',
                title: `Ano ${yearKey}`,
                total: yearData.total,
                count: yearData.count,
                level: 0
            });
            
            // Ordena meses (decrescente)
            const months = Object.keys(yearData.months).sort((a, b) => b - a);
            
            months.forEach(monthKey => {
                const monthData = yearData.months[monthKey];
                
                // Adiciona linha do mês
                tableData.push({
                    type: 'month',
                    title: `${monthData.monthName.charAt(0).toUpperCase() + monthData.monthName.slice(1)} ${yearKey}`,
                    total: monthData.total,
                    count: monthData.count,
                    level: 1
                });
                
                // Ordena dias (decrescente)
                const days = Object.keys(monthData.days).sort((a, b) => b - a);
                
                days.forEach(dayKey => {
                    const dayData = monthData.days[dayKey];
                    
                    // Adiciona linha do dia
                    tableData.push({
                        type: 'day',
                        title: dayData.dateStr,
                        total: dayData.total,
                        count: dayData.count,
                        level: 2
                    });
                    
                    // Adiciona as transferências do dia
                    dayData.transfers.forEach(transfer => {
                        tableData.push({
                            type: 'transfer',
                            ...transfer,
                            level: 3
                        });
                    });
                });
            });
        });

        // Configura o DataTables para usar moment.js para ordenação de datas
        $.fn.dataTable.moment('DD/MM/YYYY HH:mm:ss');
        
        // Inicializa a DataTable
        const table = $('#transfersTable').DataTable({
            data: tableData,
            columns: [
                { 
                    data: 'type',
                    render: function(data, type, row) {
                        const indentClass = `indent-${row.level}`;
                        
                        if (data === 'year') {
                            return `<div class="${indentClass}"><i class="bi bi-calendar-event me-2"></i> <strong>${row.title}</strong></div>`;
                        } else if (data === 'month') {
                            return `<div class="${indentClass}"><i class="bi bi-calendar-month me-2"></i> ${row.title}</div>`;
                        } else if (data === 'day') {
                            return `<div class="${indentClass}"><i class="bi bi-calendar-day me-2"></i> ${row.title}</div>`;
                        } else {
                            const badgeClass = {
                                'PIX': 'badge-pix',
                                'TED': 'badge-ted',
                                'DOC': 'badge-doc',
                                'INTERNAL': 'badge-internal'
                            }[row.type] || 'badge-secondary';
                            return `<div class="${indentClass}"><span class="badge ${badgeClass}">${row.type}</span></div>`;
                        }
                    }
                },
                { 
                    data: 'amount',
                    render: function(data, type, row) {
                        if (row.type === 'year' || row.type === 'month' || row.type === 'day') {
                            return `<strong>${formatCurrency(row.total)} (${row.count})</strong>`;
                        }
                        return formatCurrency(data);
                    }
                },
                { 
                    data: 'description',
                    render: function(data, type, row) {
                        if (row.type === 'year' || row.type === 'month' || row.type === 'day') {
                            return '';
                        }
                        return data;
                    }
                },
                { 
                    data: 'transfer_date',
                    render: function(data, type, row) {
                        if (row.type === 'year' || row.type === 'month' || row.type === 'day') {
                            return '';
                        }
                        return new Date(data).toLocaleString('pt-BR');
                    }
                },
                { 
                    data: 'asaas_identifier',
                    render: function(data, type, row) {
                        if (row.type === 'year' || row.type === 'month' || row.type === 'day') {
                            return '';
                        }
                        return data;
                    }
                }
            ],
            order: [],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            createdRow: function(row, data, dataIndex) {
                if (data.type === 'year') {
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

        // Configura clique nos grupos para expandir/recolher
        $('#transfersTable tbody').on('click', 'tr.year-group, tr.month-group, tr.day-group', function() {
            const tr = $(this);
            let nextTr = tr.next('tr');
            const currentLevel = tr.hasClass('year-group') ? 0 : 
                               tr.hasClass('month-group') ? 1 : 2;
            
            while (nextTr.length && 
                  ((nextTr.hasClass('month-group') && currentLevel === 0) || 
                   (nextTr.hasClass('day-group') && currentLevel === 1) ||
                   (nextTr.hasClass('transfer-detail') && currentLevel === 2))) {
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
        
        // Expande todos os anos inicialmente
        $('#transfersTable tbody tr.year-group').each(function() {
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
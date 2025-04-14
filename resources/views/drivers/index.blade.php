@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 py-3 border-bottom">
        <h2 class="mb-0">
            <i class="fas fa-users-cog me-2"></i>Gestão de Motoristas
        </h2>
        <a href="{{ route('drivers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Motorista
        </a>
    </div>

    <div class="card shadow-sm rounded-lg">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Lista de Motoristas
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="driver-search" class="form-control" placeholder="Pesquisar motoristas...">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="drivers-table" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40"></th>
                            <th>Nome</th>
                            <th>Endereço</th>
                            <th>RG</th>
                            <th>Telefone</th>
                            <th width="120">Status</th>
                            <th width="220" class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals (manter exatamente os mesmos modais existentes) -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <!-- Conteúdo do modal de imagem -->
</div>

<div class="modal fade" id="analyzeModal" tabindex="-1">
  <!-- Conteúdo do modal de análise por IA -->
</div>

<div class="modal fade" id="blockModal" tabindex="-1">
  <!-- Conteúdo do modal de bloqueio -->
</div>

<div class="modal fade" id="balanceModal" tabindex="-1">
  <!-- Conteúdo do modal de saldo -->
</div>

<div class="modal fade" id="transferModal" tabindex="-1">
  <!-- Conteúdo do modal de transferência -->
</div>

<div class="modal fade" id="freightsModal" tabindex="-1">
  <!-- Conteúdo do modal de fretes -->
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<style>
:root {
    --primary: #4e73df;
    --secondary: #858796;
    --success: #1cc88a;
    --info: #36b9cc;
    --warning: #f6c23e;
    --danger: #e74a3b;
    --light: #f8f9fc;
    --dark: #5a5c69;
}

body {
    background-color: #f8f9fc;
    color: #333;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    border-radius: 0.5rem;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.25rem 1.5rem;
}

.table thead th {
    vertical-align: middle;
    padding: 1rem 1.25rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--secondary);
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table tbody td {
    vertical-align: middle;
    padding: 0.75rem 1.25rem;
    border-top: 1px solid #f0f0f0;
}

.table-hover tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.03);
}

.btn {
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 0.35rem;
    transition: all 0.2s;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    letter-spacing: 0.5px;
}

/* Estilos para os botões de ação */
.btn-action {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border-radius: 50%;
    margin: 0 2px;
}

.btn-action i {
    font-size: 14px;
}

/* Estilos para os modais */
.modal-content {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.25rem 1.5rem;
}

.modal-title {
    font-weight: 600;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1rem 1.5rem;
}

/* Estilos para as imagens */
.img-thumbnail {
    border: 1px solid #eee;
    border-radius: 0.35rem;
    padding: 0.25rem;
    background-color: #fff;
    max-width: 100%;
    height: auto;
}

/* Responsividade */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
    }
    
    #driver-search {
        margin-top: 1rem;
        width: 100% !important;
    }
    
    .table-responsive {
        border: none;
    }
}

/* Estilos específicos para a tabela */
td.dt-control {
    position: relative;
    cursor: pointer;
}

td.dt-control::before {
    content: "+";
    font-weight: bold;
    font-size: 1.1rem;
    color: var(--success);
    display: inline-block;
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    transition: all 0.2s;
}

tr.shown td.dt-control::before {
    content: "−";
    color: var(--danger);
}

/* Estilos para os detalhes expandidos */
.dtr-details {
    padding: 1rem;
    background-color: #f9f9f9;
    border-radius: 0.5rem;
    margin: 0.5rem 0;
}

.dtr-details p {
    margin-bottom: 0.5rem;
}

/* Estilos para os cards de saldo */
.balance-card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
    height: 100%;
}

.balance-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

.balance-card .card-body {
    padding: 1.25rem;
}

.balance-card .card-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: inherit;
    opacity: 0.8;
}

.balance-card .card-text {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0;
}

/* Estilos para a tabela de transferências */
#transfersTable_wrapper {
    padding: 0;
}

#transfersTable {
    margin-top: 1rem !important;
}

/* Ajustes para os botões de ação */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

@media (max-width: 992px) {
    .action-buttons {
        justify-content: flex-start;
    }
}
</style>
@endpush

@push('scripts')
<!-- Manter todos os scripts existentes -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// Manter todos os scripts existentes, apenas atualizar a renderização das ações
function format(d) {
    return `
        <div class="dtr-details">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Data de Nascimento:</strong> ${formatDateBR(d.birth_date)}</p>
                    <p><strong>Estado Civil:</strong> ${d.marital_status}</p>
                    <p><strong>CPF:</strong> ${maskCPF(d.cpf)}</p>
                    <p><strong>CNH:</strong> ${d.driver_license_number}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Categoria CNH:</strong> ${d.driver_license_category}</p>
                    <p><strong>Validade CNH:</strong> ${formatDateBR(d.driver_license_expiration)}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${getStatusLabel(d.status)[1]}">${getStatusLabel(d.status)[0]}</span></p>
                    <p><strong>Senha:</strong> 
                        <span id="password-${d.id}" class="password-hidden">••••••••</span>
                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="togglePassword('${d.id}', '${d.password}')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </p>
                </div>
            </div>
            
            ${d.status === 'block' || d.status === 'transfer_block' ? `
            <div class="alert alert-warning mt-3">
                <strong>Motivo do Bloqueio:</strong> ${d.reason || 'Não informado'}
            </div>
            ` : ''}
            
            <div class="row mt-3">
                <div class="col-md-3 text-center">
                    ${renderImageColumn('Frente CNH', d.driver_license_front)}
                </div>
                <div class="col-md-3 text-center">
                    ${renderImageColumn('Verso CNH', d.driver_license_back)}
                </div>
                <div class="col-md-3 text-center">
                    ${renderImageColumn('Foto do Rosto', d.face_photo)}
                </div>
                <div class="col-md-3 text-center">
                    ${renderImageColumn('Comprovante', d.address_proof)}
                </div>
            </div>
        </div>
    `;
}

// Atualizar a renderização das colunas da DataTable
$(document).ready(function () {
    const table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('drivers.data') }}",
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        },
        columns: [
            { 
                className: 'dt-control', 
                orderable: false, 
                data: null, 
                defaultContent: '',
                width: '40px'
            },
            { 
                data: 'name',
                render: function(data, type, row) {
                    return `<strong>${data}</strong>`;
                }
            },
            { 
                data: 'address',
                render: function(data) {
                    return data ? `<span class="text-truncate d-inline-block" style="max-width: 200px;" title="${data}">${data}</span>` : 'N/A';
                }
            },
            { 
                data: 'identity_card', 
                render: maskRG 
            },
            { 
                data: 'phone', 
                render: maskPhone 
            },
            {
                data: 'status',
                render: status => {
                    const [text, color] = getStatusLabel(status);
                    return `<span class="badge bg-${color}">${text}</span>`;
                },
                width: '120px'
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: (data, type, row) => `
                    <div class="action-buttons">
                        <button onclick="showBalanceModal(${row.id})" class="btn btn-sm btn-outline-success" title="Saldo">
                            <i class="fas fa-wallet me-1"></i>
                        </button>
                        <button onclick="showFreightsModal(${row.id})" class="btn btn-sm btn-outline-primary" title="Fretes">
                            <i class="fas fa-truck me-1"></i>
                        </button>
                        <button onclick="activateDriver(${row.id}, '${row.status}')" class="btn btn-sm btn-outline-${row.status === 'active' ? 'danger' : 'success'}" title="${row.status === 'active' ? 'Bloquear' : 'Ativar'}">
                            <i class="fas ${row.status === 'active' ? 'fa-lock' : 'fa-check'} me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="analyzeDriver(${row.id})" title="Analisar">
                            <i class="fas fa-search me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-dark" onclick="openWhatsApp('${row.phone}')" title="WhatsApp">
                            <i class="fab fa-whatsapp me-1"></i>
                        </button>
                    </div>
                `,
                width: '220px',
                className: 'text-end'
            }
        ],
        initComplete: function() {
            // Adiciona funcionalidade de pesquisa
            $('#driver-search').keyup(function() {
                table.search($(this).val()).draw();
            });
        }
    });

    // Manter o restante dos scripts existentes
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

    // Manter todos os outros eventos e funções existentes
    $('#blockUserBtn').click(() => updateDriverStatus(selectedDriverId, 'block'));
    $('#blockTransferBtn').click(() => updateDriverStatus(selectedDriverId, 'transfer_block'));
    $('#submitTransfer').click(submitTransfer);
});

// Manter todas as outras funções existentes exatamente como estão
</script>
@endpush
@endsection
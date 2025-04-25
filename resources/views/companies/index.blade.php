@extends('layouts.app')

@section('title', 'Gestão de Empresas')

@push('styles')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
    .table-responsive {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: white;
    }
    
    .table th {
        background-color: #f8f9fa;
        border-top: none;
        white-space: nowrap;
        padding: 12px 15px;
    }
    
    .table td {
        vertical-align: middle;
        padding: 12px 15px;
    }
    
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .action-buttons .btn {
        padding: 5px 8px;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-view {
        background-color: #17a2b8;
        color: white;
    }
    
    .btn-edit {
        background-color: #ffc107;
        color: white;
    }
    
    .btn-delete {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-toggle {
        background-color: #28a745;
        color: white;
    }
    
    .btn-toggle.inactive {
        background-color: #6c757d;
    }
    
    .details-content {
        padding: 20px;
        background: #f8f9fa;
        border-left: 4px solid #3b7ddd;
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .detail-item {
        background: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    
    .detail-value {
        font-size: 0.95rem;
    }
    
    .dt-control {
        cursor: pointer;
        color: #3b7ddd;
        font-size: 1.1rem;
    }
    
    .badge-status {
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .badge-active {
        background-color: #d4edda;
        color: #155724;
    }
    
    .badge-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="fas fa-building me-2"></i>Gestão de Empresas</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Empresas</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('companies.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nova Empresa
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="companies-table" class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%"></th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Status</th>
                            <th width="15%">Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    function formatCompanyDetails(d) {
        return `
        <div class="details-content">
            <div class="details-title mb-3">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detalhes da Empresa</h5>
            </div>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Nome Fantasia</div>
                    <div class="detail-value">${d.trading_name || '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">${d.email || '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Telefone</div>
                    <div class="detail-value">${d.phone || '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Endereço</div>
                    <div class="detail-value">
                        ${d.address || '-'} ${d.number || ''}<br>
                        ${d.complement ? 'Complemento: ' + d.complement + '<br>' : ''}
                        ${d.neighborhood ? 'Bairro: ' + d.neighborhood + '<br>' : ''}
                        ${d.city ? d.city + '/' + d.state : ''}<br>
                        ${d.zip_code ? 'CEP: ' + d.zip_code : ''}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Data Cadastro</div>
                    <div class="detail-value">${new Date(d.created_at).toLocaleDateString('pt-BR')}</div>
                </div>
            </div>
        </div>`;
    }

    const table = $('#companies-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('companies.data') }}",
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        },
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '<i class="fas fa-plus-circle"></i>',
                width: '5%'
            },
            { 
                data: 'name',
                name: 'name',
                render: function(data, type, row) {
                    return `<div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="fas fa-building text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong>${data}</strong>
                                    <div class="text-muted small">${row.email || ''}</div>
                                </div>
                            </div>`;
                }
            },
            { 
                data: 'cnpj',
                name: 'cnpj',
                render: function(data) {
                    if (!data) return '-';
                    const formatted = data.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
                    return `<i class="fas fa-id-card me-1 text-muted"></i>${formatted}`;
                }
            },
            { 
                data: 'active',
                name: 'active',
                render: function(data) {
                    const active = data ? true : false;
                    return `<span class="badge-status ${active ? 'badge-active' : 'badge-inactive'}">
                                <i class="fas ${active ? 'fa-check-circle' : 'fa-times-circle'} me-1"></i>
                                ${active ? 'Ativo' : 'Inativo'}
                            </span>`;
                }
            },
            { 
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                    <div class="action-buttons">
                        <a href="/companies/${row.id}" class="btn btn-view" title="Visualizar">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/companies/${row.id}/edit" class="btn btn-edit" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-toggle ${row.active ? '' : 'inactive'} toggle-status" 
                                data-id="${row.id}" 
                                title="${row.active ? 'Desativar' : 'Ativar'}">
                            <i class="fas ${row.active ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
                        </button>
                        <button class="btn btn-delete delete-company" data-id="${row.id}" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`;
                }
            }
        ],
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-control');
            $('.dataTables_length select').addClass('form-select');
        }
    });

    // Detalhes expansíveis
    $('#companies-table tbody').on('click', 'td.dt-control', function() {
        const tr = $(this).closest('tr');
        const row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            $(this).html('<i class="fas fa-plus-circle"></i>');
        } else {
            row.child(formatCompanyDetails(row.data())).show();
            tr.addClass('shown');
            $(this).html('<i class="fas fa-minus-circle"></i>');
        }
    });

    // Alternar status
    $(document).on('click', '.toggle-status', function() {
        const btn = $(this);
        const companyId = btn.data('id');
        
        $.ajax({
            url: `/companies/${companyId}/toggle-status`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                btn.prop('disabled', true);
            },
            success: function(response) {
                table.ajax.reload(null, false);
                showToast('Status atualizado com sucesso!', 'success');
            },
            error: function() {
                showToast('Erro ao atualizar status!', 'danger');
                btn.prop('disabled', false);
            }
        });
    });

    // Excluir empresa
    $(document).on('click', '.delete-company', function() {
        const companyId = $(this).data('id');
        
        if (confirm('Tem certeza que deseja excluir esta empresa?')) {
            $.ajax({
                url: `/companies/${companyId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload();
                    showToast('Empresa excluída com sucesso!', 'success');
                },
                error: function() {
                    showToast('Erro ao excluir empresa!', 'danger');
                }
            });
        }
    });
});
</script>
@endpushs
@extends('layouts.app')

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
    }
    .table th {
        background-color: #f8f9fa;
        border-top: none;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .badge-status {
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
    .action-buttons .btn {
        padding: 5px 8px;
        margin: 0 2px;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .details-row {
        background-color: #f9fafb;
    }
    .details-content {
        padding: 20px;
        background: linear-gradient(135deg, #f9fafb 0%, #f0f4f8 100%);
        border-left: 4px solid #3b7ddd;
    }
    .details-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px dashed #dee2e6;
        display: flex;
        align-items: center;
    }
    .details-title i {
        margin-right: 8px;
        color: #3b7ddd;
    }
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
    }
    .detail-item {
        margin-bottom: 12px;
        background-color: white;
        padding: 12px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .detail-value {
        font-weight: 400;
        color: #212529;
        line-height: 1.4;
    }
    .detail-value a {
        color: #3b7ddd;
        text-decoration: none;
    }
    .detail-value a:hover {
        text-decoration: underline;
    }
    .dt-control {
        cursor: pointer;
        color: #3b7ddd;
        font-size: 1.1rem;
        transition: transform 0.2s;
    }
    .dt-control:hover {
        color: #2c6ed5;
        transform: scale(1.1);
    }
    .btn-toggle-active {
        background-color: #28a745;
        color: white;
    }
    .btn-toggle-inactive {
        background-color: #dc3545;
        color: white;
    }
    .btn-edit {
        background-color: #17a2b8;
        color: white;
    }
    .btn-delete {
        background-color: #dc3545;
        color: white;
    }
    .btn-view {
        background-color: #6c757d;
        color: white;
    }
    .btn-toggle-active:hover, .btn-toggle-inactive:hover,
    .btn-edit:hover, .btn-delete:hover, .btn-view:hover {
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="bi bi-building me-2"></i>Gestão de Empresas</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="bi bi-building me-1"></i>Empresas</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('companies.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nova Empresa
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="companies-table" class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%"></th>
                            <th width="5%">ID</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th width="18%">Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables & Plugins -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    function format(d) {
        return `
        <div class="details-content">
            <div class="details-title">
                <i class="bi bi-info-circle"></i>Detalhes da Empresa
            </div>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Nome Fantasia</div>
                    <div class="detail-value">${d.trading_name || '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Inscrição Estadual</div>
                    <div class="detail-value">${d.state_registration || '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">WhatsApp</div>
                    <div class="detail-value">${d.whatsapp ? '<i class="bi bi-whatsapp me-1 text-success"></i>' + d.whatsapp : '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">E-mail</div>
                    <div class="detail-value">${d.email ? '<i class="bi bi-envelope me-1"></i>' + d.email : '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Endereço</div>
                    <div class="detail-value">
                        ${d.address ? '<i class="bi bi-geo-alt me-1"></i>' + d.address + ', ' + d.number : '-'}<br>
                        ${d.complement ? '<span class="ms-3">Complemento: ' + d.complement + '</span><br>' : ''}
                        ${d.neighborhood ? '<span class="ms-3">Bairro: ' + d.neighborhood + '</span><br>' : ''}
                        ${d.city ? '<span class="ms-3">' + d.city + '/' + d.state + '</span><br>' : ''}
                        ${d.zip_code ? '<span class="ms-3">CEP: ' + d.zip_code + '</span>' : ''}
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Site</div>
                    <div class="detail-value">${d.website ? '<a href="' + (d.website.startsWith('http') ? d.website : 'http://' + d.website) + '" target="_blank"><i class="bi bi-globe me-1"></i>' + d.website + '</a>' : '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Descrição</div>
                    <div class="detail-value">${d.description || '-'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Data de Cadastro</div>
                    <div class="detail-value"><i class="bi bi-calendar me-1"></i>${new Date(d.created_at).toLocaleDateString('pt-BR')}</div>
                </div>
            </div>
        </div>`;
    }

    var table = $('#companies-table').DataTable({
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
                defaultContent: '<i class="bi bi-plus-circle"></i>',
                width: '5%'
            },
            { data: 'id', name: 'id' },
            { 
                data: 'name', 
                name: 'name',
                render: function(data, type, row) {
                    return `<div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bi bi-building fs-4 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong>${data}</strong><br>
                                    <small class="text-muted">${row.email || ''}</small>
                                </div>
                            </div>`;
                }
            },
            { 
                data: 'cnpj', 
                name: 'cnpj',
                render: function(data) {
                    return data ? `<i class="bi bi-file-text me-1"></i>${data.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5")}` : '-';
                }
            },
            { 
                data: 'phone', 
                name: 'phone',
                render: function(data) {
                    return data ? `<i class="bi bi-telephone me-1"></i>${data.replace(/^(\d{2})(\d{4,5})(\d{4})$/, "($1) $2-$3")}` : '-';
                }
            },
            { 
                data: 'active', 
                name: 'active',
                render: function(data) {
                    const icon = data ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger';
                    return `<span class="badge-status ${data ? 'bg-success' : 'bg-danger'}">
                                <i class="bi ${icon} me-1"></i>${data ? 'Ativo' : 'Inativo'}
                            </span>`;
                }
            },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    const toggleBtnClass = row.active ? 'btn-toggle-inactive' : 'btn-toggle-active';
                    const toggleBtnIcon = row.active ? 'bi-x-lg' : 'bi-check-lg';
                    const toggleBtnTitle = row.active ? 'Desativar' : 'Ativar';
                    
                    return `<div class="action-buttons d-flex">
                                <button class="btn btn-sm ${toggleBtnClass} toggle-btn" data-id="${row.id}" title="${toggleBtnTitle}">
                                    <i class="bi ${toggleBtnIcon}"></i>
                                </button>
                                <a href="/companies/${row.id}/edit" class="btn btn-sm btn-edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-delete delete-btn" data-id="${row.id}" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <a href="/companies/${row.id}" class="btn btn-sm btn-view" title="Visualizar">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>`;
                }
            }
        ],
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-control');
            $('.dataTables_length select').addClass('form-select');
        }
    });

    // Add event listener for opening and closing details
    $('#companies-table tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            $(this).html('<i class="bi bi-plus-circle"></i>');
        } else {
            row.child(format(row.data())).show();
            tr.addClass('shown');
            $(this).html('<i class="bi bi-dash-circle"></i>');
        }
    });

    // Delete button handler
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm('Tem certeza que deseja excluir esta empresa?')) {
            $.ajax({
                url: `/companies/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload();
                    showAlert('success', 'Empresa excluída com sucesso!');
                },
                error: function() {
                    showAlert('danger', 'Erro ao excluir empresa!');
                }
            });
        }
    });

    // Toggle active status button handler
    $(document).on('click', '.toggle-btn', function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        $.ajax({
            url: `/companies/${id}/toggle-status`,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                btn.prop('disabled', true);
            },
            success: function(response) {
                table.ajax.reload();
                showAlert('success', 'Status da empresa atualizado com sucesso!');
            },
            error: function() {
                showAlert('danger', 'Erro ao atualizar status da empresa!');
                btn.prop('disabled', false);
            }
        });
    });

    function showAlert(type, message) {
        const alert = `<div class="alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        
        $('body').append(alert);
        setTimeout(() => $('.alert').alert('close'), 3000);
    }
});
</script>
@endpush
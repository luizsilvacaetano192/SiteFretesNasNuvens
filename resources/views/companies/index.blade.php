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
    }
    .table th {
        background-color: #f8f9fa;
        border-top: none;
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
        padding: 5px 10px;
        margin: 0 2px;
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
                            <th width="5%"><i class="bi bi-hash"></i> ID</th>
                            <th><i class="bi bi-building me-1"></i> Nome</th>
                            <th><i class="bi bi-file-text me-1"></i> CNPJ</th>
                            <th><i class="bi bi-telephone me-1"></i> Telefone</th>
                            <th><i class="bi bi-circle-fill me-1"></i> Status</th>
                            <th width="15%"><i class="bi bi-activity me-1"></i> Ações</th>
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
    $('#companies-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('companies.data') }}",
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        },
        columns: [
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
                    return data ? `<i class="bi bi-telephone me-1"></i>${data}` : '-';
                }
            },
            { 
                data: 'active', 
                name: 'active',
                render: function(data) {
                    const icon = data ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-secondary';
                    return `<span class="badge-status ${data ? 'bg-success' : 'bg-secondary'}">
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
                    return `<div class="action-buttons d-flex">
                                <a href="/companies/${row.id}/edit" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${row.id}" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <a href="/companies/${row.id}" class="btn btn-sm btn-outline-info" title="Visualizar">
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
                    $('#companies-table').DataTable().ajax.reload();
                    alert('Empresa excluída com sucesso!');
                },
                error: function() {
                    alert('Erro ao excluir empresa!');
                }
            });
        }
    });
});
</script>
@endpush
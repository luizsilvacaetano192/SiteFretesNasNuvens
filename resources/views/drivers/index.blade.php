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

<!-- Modais já incluídos acima (Image, IA, Bloqueio) -->

<!-- Scripts e estilos já incluídos acima -->

<script>
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
                render: function (data) {
                    const isActive = data.status === 'active';
                    const analyzeBtn = `<button class="btn btn-sm btn-info me-1" onclick="analyzeDriver(${data.id})">🔎 Analisar</button>`;
                    const toggleStatusBtn = isActive
                        ? `<button class="btn btn-sm btn-danger me-1" onclick="activateDriver(${data.id}, 'active')">🚫 Bloquear</button>`
                        : `<button class="btn btn-sm btn-success me-1" onclick="activateDriver(${data.id}, '${data.status}')">✅ Ativar</button>`;
                    const whatsappBtn = `<button class="btn btn-sm btn-success" onclick="openWhatsApp('${data.phone}')">💬 WhatsApp</button>`;
                    const saldoBtn = `<a href="/drivers/${data.id}/balance" class="btn btn-sm btn-primary ms-1">💰 Saldo</a>`;
                    return analyzeBtn + toggleStatusBtn + whatsappBtn + saldoBtn;
                }
            },
        ],
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
});
</script>
@endsection

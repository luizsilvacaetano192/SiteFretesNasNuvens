@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 Lista de Motoristas</h2>
        <a href="{{ route('drivers.create') }}" class="btn btn-success">➕ Adicionar Motorista</a>
    </div>

    <table id="drivers-table" class="table table-striped table-hover">
        <thead class="table-dark">
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

@include('drivers.modals.image')
@include('drivers.modals.analysis')
@include('drivers.modals.block')
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<style>
    td.dt-control::before {
        content: "+";
        font-weight: bold;
        font-size: 18px;
        color: #198754;
        cursor: pointer;
    }
    tr.shown td.dt-control::before {
        content: "−";
        color: #dc3545;
    }
    .password-hidden {
        font-family: monospace;
        letter-spacing: 2px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    let selectedDriverId = null;

    function maskRG(value) {
        return value?.replace(/^(\d{1,2})(\d{3})(\d{3})([\dxX])?$/, (_, a, b, c, d) => `${a}.${b}.${c}${d ? '-' + d : ''}`) || '';
    }

    function maskPhone(value) {
        return value?.replace(/\D/g, '').replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3') || '';
    }

    function maskCPF(cpf) {
        return cpf?.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4") || '';
    }

    function formatDateBR(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleDateString('pt-BR');
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

    function openImageModal(src) {
        $('#modalImage').attr('src', src);
        new bootstrap.Modal('#imageModal').show();
    }

    function getStatusLabel(status) {
        const labels = {
            'create': ['Aguardando', 'warning'],
            'active': ['Ativo', 'success'],
            'block': ['Bloqueado', 'danger'],
            'transfer_block': ['Transferência Bloqueada', 'danger'],
        };
        return labels[status] || ['Desconhecido', 'secondary'];
    }

    function updateDriverStatus(id, status) {
        const reason = $('#blockReason').val().trim();

        if ((status === 'block' || status === 'transfer_block') && !reason) {
            toastr.warning('Informe o motivo do bloqueio.');
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

    $('#blockUserBtn').click(() => updateDriverStatus(selectedDriverId, 'block'));
    $('#blockTransferBtn').click(() => updateDriverStatus(selectedDriverId, 'transfer_block'));

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
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Analisando com IA...</p>
            </div>
        `);
        modal.show();

        $.get(`/drivers/${driverId}/analyze`, result => {
            $('#analysisContent').html(`
                <div class="alert alert-info">
                    <h5>🧠 Resultado IA:</h5>
                    <p>${result.message.replace(/\n/g, "<br>")}</p>
                </div>
                <div class="row">
                    ${renderImageColumn('Frente CNH', result.driver_license_front)}
                    ${renderImageColumn('Comprovante de Endereço', result.address_proof)}
                    ${renderImageColumn('Foto do Rosto', result.face_photo)}
                </div>
            `);
        }).fail(() => {
            $('#analysisContent').html(`<div class="alert alert-danger">Erro ao analisar via IA.</div>`);
        });
    }

    function togglePassword(id, password) {
        const span = document.getElementById(`password-${id}`);
        span.innerText = span.innerText === '••••••••' ? password : '••••••••';
    }

    function format(row) {
        let reason = '';
        if (row.status === 'block' || row.status === 'transfer_block') {
            reason = `<p><strong>Motivo:</strong> ${row.reason || 'Não informado'}</p>`;
        }

        return `
            <div class="p-3 bg-light rounded">
                <p><strong>Data Nasc.:</strong> ${formatDateBR(row.birth_date)}</p>
                <p><strong>Estado Civil:</strong> ${row.marital_status}</p>
                <p><strong>CPF:</strong> ${maskCPF(row.cpf)}</p>
                <p><strong>CNH:</strong> ${row.driver_license_number}</p>
                <p><strong>Categoria:</strong> ${row.driver_license_category}</p>
                <p><strong>Validade:</strong> ${formatDateBR(row.driver_license_expiration)}</p>
                <p><strong>Status:</strong> ${getStatusLabel(row.status)[0]}</p>
                <p><strong>Senha:</strong> 
                    <span id="password-${row.id}" class="password-hidden">••••••••</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="togglePassword('${row.id}', '${row.password}')">👁️</button>
                </p>
                ${reason}
                <div class="row">
                    ${renderImageColumn('Frente CNH', row.driver_license_front)}
                    ${renderImageColumn('Verso CNH', row.driver_license_back)}
                    ${renderImageColumn('Foto do Rosto', row.face_photo)}
                    ${renderImageColumn('Comprovante de Endereço', row.address_proof)}
                </div>
            </div>
        `;
    }

    $(document).ready(() => {
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
                    render: row => `
                        <div class="btn-group btn-group-sm">
                            <a href="/drivers/${row.id}/balance" class="btn btn-outline-success">💰 Saldo</a>
                            <a href="/drivers/${row.id}/freights" class="btn btn-outline-primary">🚚 Fretes</a>
                            <button class="btn btn-outline-dark" onclick="analyzeDriver(${row.id})">🤖 Analisar</button>
                            <button class="btn btn-outline-warning" onclick="activateDriver(${row.id}, '${row.status}')">
                                ${row.status === 'active' ? '🔒 Bloquear' : '✅ Ativar'}
                            </button>
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
    });
</script>
@endpush

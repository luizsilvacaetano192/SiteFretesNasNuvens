<!-- Modal de Saldo -->
<div class="modal fade" id="balanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">💰 Saldo do Motorista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="balanceInfo" class="mb-4"></div>
                <h5 class="mb-3">📄 Transferências</h5>
                <table id="transfersTable" class="table table-bordered table-hover w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Descrição</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

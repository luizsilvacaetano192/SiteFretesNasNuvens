<!-- Modal de Saldo e Transferências -->
<div class="modal fade" id="balanceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">💰 Saldo e Transferências</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4 g-3">
          <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
              <div class="card-body">
                <h6 class="card-title">ID Conta Asaas</h6>
                <p class="card-text" id="asaasIdentifier">-</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-success text-white h-100">
              <div class="card-body">
                <h6 class="card-title">Saldo Total</h6>
                <p class="card-text" id="totalBalance">R$ 0,00</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-warning text-dark h-100">
              <div class="card-body">
                <h6 class="card-title">Saldo Bloqueado</h6>
                <p class="card-text" id="blockedBalance">R$ 0,00</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-info text-white h-100">
              <div class="card-body">
                <h6 class="card-title">Saldo Disponível</h6>
                <p class="card-text" id="availableBalance">R$ 0,00</p>
              </div>
            </div>
          </div>
        </div>
        
        <h5 class="mb-3">Histórico de Transferências</h5>
        <div class="table-responsive">
          <table id="transfersTable" class="table table-striped table-hover" style="width:100%">
            <thead class="table-dark">
              <tr>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Descrição</th>
                <th>Data/Hora</th>
                <th>ID Asaas</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

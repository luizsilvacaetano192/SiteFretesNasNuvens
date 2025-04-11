<div class="modal fade" id="blockModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🔒 Bloqueio de Motorista</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-center">Escolha o tipo de bloqueio e informe o motivo.</p>
        <div class="mb-3">
          <label for="blockReason" class="form-label">📝 Motivo do Bloqueio</label>
          <textarea class="form-control" id="blockReason" rows="3" placeholder="Descreva o motivo..."></textarea>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-danger" id="blockUserBtn">🚫 Bloquear Usuário</button>
          <button class="btn btn-warning" id="blockTransferBtn">📵 Bloquear Transferências</button>
        </div>
      </div>
    </div>
  </div>
</div>

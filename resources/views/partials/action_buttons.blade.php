// resources/views/partials/action_buttons.blade.php

<a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-warning btn-sm">Editar</a>
<a href="{{ route('shipments.show', $shipment->id) }}" class="btn btn-info btn-sm">Visualizar</a>
<button class="btn btn-danger btn-sm" onclick="deleteShipment({{ $shipment->id }})">Deletar</button>

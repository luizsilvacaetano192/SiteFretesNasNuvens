<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\View\View;

class DriverController extends Controller
{
    /**
     * Exibe a view de saldo e transferências
     */
    public function showBalance(Driver $driver): View
    {
        $driver->load(['userAccount.transfers' => function($query) {
            $query->with(['freight.company'])
                 ->orderBy('transfer_date', 'desc');
        }]);

        return view('drivers.balance', [
            'driver' => $driver,
            'transfers' => $driver->userAccount->transfers ?? collect()
        ]);
    }

    /**
     * Formata o tipo de transferência para exibição
     */
    protected function formatTransferType(string $type): string
    {
        $types = [
            'PIX' => 'PIX',
            'TED' => 'TED',
            'DOC' => 'DOC',
            'INTERNAL' => 'Interna',
            'BLOCKED' => 'Bloqueado',
            'PIX_DEBIT' => 'PIX Débito'
        ];

        return $types[$type] ?? $type;
    }

    /**
     * Retorna a cor do badge conforme o tipo
     */
    protected function transferBadgeColor(string $type): string
    {
        $colors = [
            'PIX' => 'pix',
            'TED' => 'ted',
            'DOC' => 'doc',
            'INTERNAL' => 'internal',
            'BLOCKED' => 'blocked',
            'PIX_DEBIT' => 'success'
        ];

        return $colors[$type] ?? 'secondary';
    }
}
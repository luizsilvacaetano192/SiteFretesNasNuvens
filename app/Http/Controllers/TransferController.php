<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $transfers = Transfer::query();

        // Filtro por período de datas
        if ($request->filled('date_range')) {
            try {
                [$start, $end] = explode(' - ', $request->date_range);

                $startDate = Carbon::createFromFormat('d/m/Y', trim($start))->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', trim($end))->endOfDay();

                $transfers->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // Se erro na conversão de data, ignora o filtro
            }
        }

        // Outros filtros podem ser aplicados aqui, exemplo:
        if ($request->filled('freight_id')) {
            $transfers->where('freight_id', $request->freight_id);
        }

        if ($request->filled('type')) {
            $transfers->where('type', $request->type);
        }

        // Ordenação e paginação
        $transfers = $transfers->orderBy('created_at', 'desc')->paginate(50);

        return view('transfers.index', compact('transfers'));
    }
}

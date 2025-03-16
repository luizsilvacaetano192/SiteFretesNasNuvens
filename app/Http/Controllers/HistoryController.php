<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        return response()->json(History::all());
    }

    public function getHistory($freightId)
    {
        $history = History::where('freight_id', $freightId)
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();

        return response()->json($history);
    }

    public function store(Request $request)
    {
        $history = History::create($request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'freight_id' => 'required|exists:freights,id',
            'status' => 'required|in:pending,in_progress,completed,canceled',
        ]));

        return response()->json($history, 201);
    }

    public function show(History $history)
    {
        return response()->json($history);
    }

    public function update(Request $request, History $history)
    {
        $history->update($request->validate([
            'date' => 'sometimes|date',
            'time' => 'sometimes',
            'address' => 'sometimes|string',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'freight_id' => 'sometimes|exists:freights,id',
            'status' => 'sometimes|in:pending,in_progress,completed,canceled',
        ]));

        return response()->json($history);
    }

    public function destroy(History $history)
    {
        $history->delete();
        return response()->json(null, 204);
    }
}

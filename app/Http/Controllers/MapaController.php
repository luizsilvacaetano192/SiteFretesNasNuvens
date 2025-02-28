<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MapaController extends Controller
{
    public function index()
    {
        return view('mapa');
    }

    public function getRota(Request $request)
    {
        $client = new Client();
        $origin = urlencode($request->input('origem'));
        $destination = urlencode($request->input('destino'));
        $apiKey = 'AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI';

        $response = $client->get("https://maps.googleapis.com/maps/api/directions/json?origin={$origin}&destination={$destination}&key={$apiKey}");
        $data = json_decode($response->getBody()->getContents());

        return response()->json($data);
    }
}

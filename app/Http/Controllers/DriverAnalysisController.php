<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Support\Facades\Storage;


class DriverAnalysisController extends Controller
{
   
    public function analyze(Request $request)
    {
        $driver = $request->all();

        dd($request);
    
        try {
            $rekognition = new RekognitionClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);
    
            dd($driver);
            $sourceImageKey = parse_url($driver['face_photo'], PHP_URL_PATH);
            $targetImageKey = parse_url($driver['driver_license_front'], PHP_URL_PATH);
    
            $result = $rekognition->compareFaces([
                'SimilarityThreshold' => 80,
                'SourceImage' => [
                    'S3Object' => [
                        'Bucket' => env('AWS_BUCKET'),
                        'Name' => ltrim($sourceImageKey, '/'),
                    ],
                ],
                'TargetImage' => [
                    'S3Object' => [
                        'Bucket' => env('AWS_BUCKET'),
                        'Name' => ltrim($targetImageKey, '/'),
                    ],
                ],
            ]);
    
            $matches = $result['FaceMatches'];
    
            if (count($matches) > 0 && $matches[0]['Similarity'] >= 80) {
                return response()->json([
                    'status' => 'analisado',
                    'message' => "✅ A foto do rosto é compatível com a CNH (semelhança: " . round($matches[0]['Similarity'], 2) . "%)"
                ]);
            } else {
                return response()->json([
                    'status' => 'analisado',
                    'message' => "⚠️ As imagens não são compatíveis o suficiente (semelhança abaixo de 80%)"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'erro',
                'message' => 'Erro ao usar o AWS Rekognition: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
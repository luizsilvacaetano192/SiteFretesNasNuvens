<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Support\Facades\Storage;


class DriverAnalysisController extends Controller
{
   
    public function analyze($driver_id)
    {
       
        $driver = Driver::findorfail($driver_id);
       
    
        try {
            $rekognition = new RekognitionClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);
    
            $sourceImageKey =      $driver['face_photo'];
            $targetImageKey =      $driver['driver_license_front_photo'];

           
            // Verifica se o bucket está configurado
            if (empty(env('AWS_BUCKET'))) {
                throw new \Exception("Bucket S3 não configurado no .env");
            }

            // Verifica se as chaves não estão vazias
            if (empty($sourceImageKey) || empty($targetImageKey)) {
                throw new \Exception("Caminhos das imagens inválidos.");
            }
                
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
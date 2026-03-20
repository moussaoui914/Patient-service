<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AiController extends Controller
{
    public function generateResume(Patient $patient)
    {
        try {
            // Create client with SSL verification disabled (DEVELOPMENT ONLY)
            $client = new Client([
                'base_uri' => 'https://api.groq.com/openai/v1/',
                'timeout' => 60,
                'verify' => true,
                'http_errors' => false,
            ]);

            // Prepare patient data
            $patientData = [
                'nom' => $patient->firstname,
                'prenom' => $patient->lastname,
                'date_naissance' => $patient->date_naissance ?? 'Non spécifiée',
                'diagnostic' => 'Non spécifié',
                'traitements' => 'Non spécifiés',
                'observations' => 'Aucune observation',
            ];
            
            // Make API request to Groq
            $response = $client->post('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'llama-3.3-70b-versatile', // Updated model
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un médecin assistant qui doit créer des résumés médicaux clairs et professionnels en français.'
                        ],
                        [
                            'role' => 'user',
                            'content' => "Crée un résumé médical concis à partir de ces données patient : " . json_encode($patientData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                        ]
                    ],
                    'max_tokens' => 500,
                    'temperature' => 0.7,
                ]
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            
            if ($statusCode !== 200) {
                return response()->json([
                    'success' => false,
                    'error' => 'API Error',
                    'status' => $statusCode,
                    'response' => json_decode($body, true)
                ], $statusCode);
            }
            
            $result = json_decode($body, true);
            $resume = $result['choices'][0]['message']['content'] ?? 'No response generated';
            
            return response()->json([
                'success' => true,
                'resume' => $resume,
                'patient_id' => $patient->id
            ]);
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
            $responseBody = $response ? $response->getBody()->getContents() : null;
            
            return response()->json([
                'success' => false,
                'error' => 'Guzzle Error: ' . $e->getMessage(),
                'response' => $responseBody ? json_decode($responseBody, true) : null
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
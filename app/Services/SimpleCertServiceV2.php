<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SimpleCertServiceV2
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.simplecert.api_key', 'q7aipClj6xiUXmE2OlylttEwvcI1wPJ9Hoi6GdRKhVVs93GYDOwJ8NcvUEfQ');
    }

    /**
     * Try direct API call with the provided API key
     */
    public function generateCertificateDirect(array $data)
    {
        try {
            // Try using the API key directly as Bearer token
            $certificateData = [
                'name' => $data['studentName'],
                'course' => $data['trainingTitle'],
                'date' => $data['completionDate'],
                'organization' => $data['schoolName'],
            ];

            $response = $this->client->request('POST', 'https://app.simplecert.net/api/certificates', [
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'authorization' => 'Bearer ' . $this->apiKey,
                ],
                'json' => $certificateData,
            ]);

            $result = json_decode($response->getBody(), true);

            Log::info('SimpleCert V2 certificate generated successfully', [
                'certificate_id' => $result['id'] ?? null,
                'recipient' => $data['studentName']
            ]);

            return [
                'success' => true,
                'certificate_id' => $result['id'] ?? null,
                'download_url' => $result['download_url'] ?? $result['url'] ?? null,
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('SimpleCert V2 API error: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'fallback' => true
            ];
        }
    }

    /**
     * Try with different data structure
     */
    public function generateCertificateAlternative(array $data)
    {
        try {
            // Alternative data structure
            $certificateData = [
                'recipient' => $data['studentName'],
                'title' => $data['trainingTitle'],
                'issued_date' => $data['completionDate'],
                'issuer' => $data['schoolName'],
            ];

            $response = $this->client->request('POST', 'https://app.simplecert.net/api/certificates', [
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'authorization' => 'Bearer ' . $this->apiKey,
                ],
                'json' => $certificateData,
            ]);

            $result = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'certificate_id' => $result['id'] ?? null,
                'download_url' => $result['download_url'] ?? $result['url'] ?? null,
                'data' => $result
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'fallback' => true
            ];
        }
    }

    /**
     * Download certificate from SimpleCert V2
     */
    public function downloadCertificate($certificateId, $filename = null)
    {
        try {
            $response = $this->client->request('GET', "https://app.simplecert.net/api/certificates/{$certificateId}/download", [
                'headers' => [
                    'accept' => 'application/pdf',
                    'authorization' => 'Bearer ' . $this->apiKey,
                ],
            ]);

            $filename = $filename ?? 'certificate.pdf';
            
            // Store the certificate locally
            $path = "certificates/{$filename}";
            Storage::disk('public')->put($path, $response->getBody());

            return [
                'success' => true,
                'path' => $path,
                'filename' => $filename,
                'size' => strlen($response->getBody())
            ];

        } catch (\Exception $e) {
            Log::error('SimpleCert V2 download error: ' . $e->getMessage(), [
                'certificate_id' => $certificateId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

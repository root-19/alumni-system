<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SimpleCertServiceFixed
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Generate certificate using SimpleCert API with proper authentication
     */
    public function generateCertificate(array $data)
    {
        try {
            // Use the API key directly in headers (not as Bearer)
            $certificateData = [
                'name' => $data['studentName'],
                'course' => $data['trainingTitle'], 
                'date' => $data['completionDate'],
                'organization' => $data['schoolName'],
                'certificate_id' => $data['certificateId'] ?? uniqid('CERT-'),
            ];

            $response = $this->client->request('POST', 'https://app.simplecert.net/api/certificates', [
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'X-API-Key' => 'q7aipClj6xiUXmE2OlylttEwvcI1wPJ9Hoi6GdRKhVVs93GYDOwJ8NcvUEfQ',
                ],
                'json' => $certificateData,
            ]);

            $result = json_decode($response->getBody(), true);

            Log::info('SimpleCert Fixed certificate generated successfully', [
                'certificate_id' => $result['id'] ?? null,
                'recipient' => $data['studentName'],
                'response' => $result
            ]);

            return [
                'success' => true,
                'certificate_id' => $result['id'] ?? null,
                'download_url' => $result['download_url'] ?? $result['url'] ?? null,
                'certificate_url' => $result['certificate_url'] ?? $result['url'] ?? null,
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('SimpleCert Fixed API error: ' . $e->getMessage(), [
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
     * Alternative method using API key in query parameter
     */
    public function generateCertificateAlt(array $data)
    {
        try {
            $certificateData = [
                'name' => $data['studentName'],
                'course' => $data['trainingTitle'],
                'date' => $data['completionDate'],
                'organization' => $data['schoolName'],
            ];

            $apiKey = 'q7aipClj6xiUXmE2OlylttEwvcI1wPJ9Hoi6GdRKhVVs93GYDOwJ8NcvUEfQ';
            
            $response = $this->client->request('POST', "https://app.simplecert.net/api/certificates?api_key={$apiKey}", [
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
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
     * Download certificate from SimpleCert Fixed
     */
    public function downloadCertificate($certificateId, $filename = null)
    {
        try {
            $response = $this->client->request('GET', "https://app.simplecert.net/api/certificates/{$certificateId}/download", [
                'headers' => [
                    'accept' => 'application/pdf',
                    'X-API-Key' => 'q7aipClj6xiUXmE2OlylttEwvcI1wPJ9Hoi6GdRKhVVs93GYDOwJ8NcvUEfQ',
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
            Log::error('SimpleCert Fixed download error: ' . $e->getMessage(), [
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

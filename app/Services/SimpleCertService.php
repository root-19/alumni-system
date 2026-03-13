<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SimpleCertService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.simplecert.base_url', 'https://app.simplecert.net/api');
        $this->apiKey = config('services.simplecert.api_key', 'q7aipClj6xiUXmE2OlylttEwvcI1wPJ9Hoi6GdRKhVVs93GYDOwJ8NcvUEfQ');
    }

    /**
     * Get authenticated API key from email/password
     */
    private function getAuthenticatedApiKey()
    {
        try {
            $response = $this->client->request('POST', $this->baseUrl . '/user/api-key', [
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'email' => config('services.simplecert.email'),
                    'password' => config('services.simplecert.password'),
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['api_key'] ?? $this->apiKey;
        } catch (\Exception $e) {
            Log::error('Failed to get authenticated API key: ' . $e->getMessage());
            return $this->apiKey;
        }
    }

    /**
     * Generate certificate using SimpleCert API
     */
    public function generateCertificate(array $data)
    {
        try {
            // Get authenticated API key
            $authApiKey = $this->getAuthenticatedApiKey();
            
            // Prepare certificate data for SimpleCert API
            $certificateData = [
                'recipient_name' => $data['studentName'],
                'training_title' => $data['trainingTitle'],
                'completion_date' => $data['completionDate'],
                'issued_by' => $data['schoolName'],
                'certificate_id' => $data['certificateId'] ?? uniqid('CERT-'),
            ];

            // Try the correct SimpleCert API structure
            $response = $this->client->request('POST', $this->baseUrl . '/certificates', [
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'authorization' => 'Bearer ' . $authApiKey,
                ],
                'json' => $certificateData,
            ]);

            $result = json_decode($response->getBody(), true);

            Log::info('SimpleCert certificate generated successfully', [
                'certificate_id' => $result['id'] ?? null,
                'recipient' => $data['studentName'],
                'response_data' => $result
            ]);

            return [
                'success' => true,
                'certificate_id' => $result['id'] ?? null,
                'download_url' => $result['download_url'] ?? $result['url'] ?? null,
                'certificate_url' => $result['certificate_url'] ?? $result['url'] ?? null,
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('SimpleCert API error: ' . $e->getMessage(), [
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
     * Download certificate from SimpleCert
     */
    public function downloadCertificate($certificateId, $filename = null)
    {
        try {
            $authApiKey = $this->getAuthenticatedApiKey();
            
            // Try different possible download endpoints
            $endpoints = [
                "/certificates/{$certificateId}/download",
                "/certificate/{$certificateId}/download",
                "/certificates/{$certificateId}/pdf",
                "/api/certificates/{$certificateId}/download"
            ];

            $response = null;
            $lastError = null;

            foreach ($endpoints as $endpoint) {
                try {
                    $response = $this->client->request('GET', $this->baseUrl . $endpoint, [
                        'headers' => [
                            'accept' => 'application/pdf',
                            'authorization' => 'Bearer ' . $authApiKey,
                        ],
                    ]);
                    break;
                } catch (\Exception $e) {
                    $lastError = $e;
                    continue;
                }
            }

            if (!$response) {
                throw $lastError;
            }

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
            Log::error('SimpleCert download error: ' . $e->getMessage(), [
                'certificate_id' => $certificateId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get certificate status from SimpleCert
     */
    public function getCertificateStatus($certificateId)
    {
        try {
            $authApiKey = $this->getAuthenticatedApiKey();
            
            // Try different possible status endpoints
            $endpoints = [
                "/certificates/{$certificateId}",
                "/certificate/{$certificateId}",
                "/api/certificates/{$certificateId}"
            ];

            $response = null;
            $lastError = null;

            foreach ($endpoints as $endpoint) {
                try {
                    $response = $this->client->request('GET', $this->baseUrl . $endpoint, [
                        'headers' => [
                            'accept' => 'application/json',
                            'authorization' => 'Bearer ' . $authApiKey,
                        ],
                    ]);
                    break;
                } catch (\Exception $e) {
                    $lastError = $e;
                    continue;
                }
            }

            if (!$response) {
                throw $lastError;
            }

            $result = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'status' => $result['status'] ?? 'unknown',
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('SimpleCert status check error: ' . $e->getMessage(), [
                'certificate_id' => $certificateId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate API credentials
     */
    public function validateApiKey()
    {
        try {
            $response = $this->client->request('POST', $this->baseUrl . '/user/api-key', [
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'email' => config('services.simplecert.email'),
                    'password' => config('services.simplecert.password'),
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'valid' => true,
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('SimpleCert API key validation error: ' . $e->getMessage());

            return [
                'success' => false,
                'valid' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

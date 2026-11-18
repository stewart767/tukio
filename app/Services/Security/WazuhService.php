<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WazuhService
{
    protected string $baseUrl;
    protected string $user;
    protected string $password;
    protected bool $verifySsl;
    protected bool $enabled;

    public function __construct()
    {
        $host = config('services.wazuh.host', '127.0.0.1');
        $port = config('services.wazuh.port', 55000);
        $this->baseUrl = "https://{$host}:{$port}";
        $this->user = (string) config('services.wazuh.user', '');
        $this->password = (string) config('services.wazuh.password', '');
        $this->verifySsl = (bool) config('services.wazuh.verify_ssl', false);
        $this->enabled = (bool) config('services.wazuh.enabled', false);
    }

    /**
     * Check if Wazuh SIEM integration is enabled and configured.
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->user) && !empty($this->password);
    }

    /**
     * Get Wazuh Manager connectivity status and agent telemetry.
     */
    public function getStatus(): array
    {
        if (!$this->isEnabled()) {
            return [
                'status' => 'standby',
                'connected' => false,
                'message' => 'Wazuh SIEM integration is in Standby mode. Configure WAZUH_HOST, WAZUH_USER, and WAZUH_PASSWORD in .env to connect to your dedicated Wazuh manager.',
                'agents_total' => 0,
                'agents_active' => 0,
                'manager_version' => null,
            ];
        }

        try {
            $token = $this->getAuthToken();
            if (!$token) {
                return [
                    'status' => 'authentication_failed',
                    'connected' => false,
                    'message' => 'Failed to authenticate against Wazuh API. Please check your credentials.',
                    'agents_total' => 0,
                    'agents_active' => 0,
                    'manager_version' => null,
                ];
            }

            // Query manager info and agents summary
            $infoResponse = $this->request('GET', '/manager/info', [], $token);
            $agentsResponse = $this->request('GET', '/agents/summary/status', [], $token);

            $version = $infoResponse['data']['version'] ?? 'v4.x';
            $activeAgents = $agentsResponse['data']['connection']['active'] ?? 0;
            $totalAgents = $agentsResponse['data']['connection']['total'] ?? 0;

            return [
                'status' => 'connected',
                'connected' => true,
                'message' => 'Connected to Wazuh SIEM Manager.',
                'agents_total' => $totalAgents,
                'agents_active' => $activeAgents,
                'manager_version' => $version,
            ];
        } catch (\Throwable $e) {
            Log::warning('[WazuhService] Connection check failed: ' . $e->getMessage());
            return [
                'status' => 'unreachable',
                'connected' => false,
                'message' => 'Unable to reach Wazuh API at ' . $this->baseUrl . ': ' . $e->getMessage(),
                'agents_total' => 0,
                'agents_active' => 0,
                'manager_version' => null,
            ];
        }
    }

    /**
     * Retrieve security agents list.
     */
    public function getAgents(int $limit = 10): array
    {
        if (!$this->isEnabled()) {
            return [];
        }

        $token = $this->getAuthToken();
        if (!$token) {
            return [];
        }

        $response = $this->request('GET', '/agents', ['limit' => $limit, 'select' => 'id,name,ip,status,os.name,version'], $token);
        return $response['data']['affected_items'] ?? [];
    }

    /**
     * Retrieve File Integrity Monitoring (FIM / syscheck) events.
     */
    public function getSyscheckEvents(int $limit = 15): array
    {
        if (!$this->isEnabled()) {
            return [];
        }

        $token = $this->getAuthToken();
        if (!$token) {
            return [];
        }

        // Query syscheck for default local agent 000 or all
        $response = $this->request('GET', '/syscheck/000', ['limit' => $limit], $token);
        return $response['data']['affected_items'] ?? [];
    }

    /**
     * Retrieve Security Configuration Assessment (SCA) findings.
     */
    public function getScaFindings(int $limit = 10): array
    {
        if (!$this->isEnabled()) {
            return [];
        }

        $token = $this->getAuthToken();
        if (!$token) {
            return [];
        }

        $response = $this->request('GET', '/sca/000', ['limit' => $limit], $token);
        return $response['data']['affected_items'] ?? [];
    }

    /**
     * Authenticate and cache JWT Bearer token (10 minutes).
     */
    protected function getAuthToken(): ?string
    {
        return Cache::remember('wazuh:api:jwt', 600, function () {
            try {
                $response = Http::withBasicAuth($this->user, $this->password)
                    ->withOptions(['verify' => $this->verifySsl])
                    ->timeout(5)
                    ->post("{$this->baseUrl}/security/user/authenticate");

                if ($response->successful()) {
                    return $response->json('data.token');
                }

                Log::error('[WazuhService] Auth response error: ' . $response->body());
                return null;
            } catch (\Throwable $e) {
                Log::warning('[WazuhService] Auth exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Send authenticated request to Wazuh REST API.
     */
    protected function request(string $method, string $endpoint, array $params = [], ?string $token = null): array
    {
        $token = $token ?: $this->getAuthToken();

        $client = Http::withToken($token)
            ->withOptions(['verify' => $this->verifySsl])
            ->timeout(8);

        $url = "{$this->baseUrl}" . (str_starts_with($endpoint, '/') ? $endpoint : "/{$endpoint}");

        $response = $method === 'POST'
            ? $client->post($url, $params)
            : $client->get($url, $params);

        return $response->json() ?? [];
    }
}


<?php

namespace App\Services\Security;

use App\Models\SecurityBlockedIp;
use App\Models\SecurityIncident;
use App\Models\SecuritySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreatDetectionService
{
    /**
     * Keys to exclude from payload inspection and logging (credentials/tokens).
     */
    protected array $redactedKeys = [
        'password',
        'password_confirmation',
        'current_password',
        'access_token',
        'token',
        'secret',
        'app_secret',
        'key',
    ];

    /**
     * Inspect an incoming HTTP request for malicious threats.
     */
    public function inspect(Request $request): ?array
    {
        // Check if threat detection is globally enabled
        if (!SecuritySetting::get('threat_detection_enabled', true)) {
            return null;
        }

        $ip = $request->ip();

        // 1. Skip whitelisted IP addresses
        if ($this->isWhitelisted($ip)) {
            return null;
        }

        $path = '/' . ltrim($request->path(), '/');
        $userAgent = (string) $request->header('User-Agent', '');

        // 2. Scan for Exploit Probes & Vulnerability Scanners (High Priority)
        $probeDetection = $this->detectExploitProbe($path, $userAgent);
        if ($probeDetection) {
            return $this->formatDetection($request, $probeDetection);
        }

        // 3. Flatten and sanitize request input data (query parameters and request body)
        $inputs = $this->flattenAndSanitizeInputs($request->all());

        // 4. Scan for SQL Injection
        foreach ($inputs as $key => $val) {
            $sqliMatch = $this->detectSqlInjection($val);
            if ($sqliMatch) {
                return $this->formatDetection($request, [
                    'threat_type' => 'sqli',
                    'severity' => 'critical',
                    'confidence_score' => 95,
                    'detected_rule' => "SQLi Pattern: {$sqliMatch}",
                    'payload_sample' => "Key: {$key} => " . Str::limit($val, 200),
                ]);
            }
        }

        // 5. Scan for Cross-Site Scripting (XSS)
        foreach ($inputs as $key => $val) {
            $xssMatch = $this->detectXss($val);
            if ($xssMatch) {
                return $this->formatDetection($request, [
                    'threat_type' => 'xss',
                    'severity' => 'high',
                    'confidence_score' => 90,
                    'detected_rule' => "XSS Pattern: {$xssMatch}",
                    'payload_sample' => "Key: {$key} => " . Str::limit($val, 200),
                ]);
            }
        }

        // 6. Scan for Path Traversal / Local File Inclusion
        foreach ($inputs as $key => $val) {
            $traversalMatch = $this->detectPathTraversal($val);
            if ($traversalMatch) {
                return $this->formatDetection($request, [
                    'threat_type' => 'path_traversal',
                    'severity' => 'critical',
                    'confidence_score' => 95,
                    'detected_rule' => "Path Traversal Pattern: {$traversalMatch}",
                    'payload_sample' => "Key: {$key} => " . Str::limit($val, 200),
                ]);
            }
        }

        // Also check raw URI for path traversal
        $uriTraversal = $this->detectPathTraversal($request->getRequestUri());
        if ($uriTraversal) {
            return $this->formatDetection($request, [
                'threat_type' => 'path_traversal',
                'severity' => 'critical',
                'confidence_score' => 95,
                'detected_rule' => "URI Path Traversal: {$uriTraversal}",
                'payload_sample' => Str::limit($request->getRequestUri(), 200),
            ]);
        }

        return null;
    }

    /**
     * Check if an IP address is whitelisted.
     */
    public function isWhitelisted(string $ip): bool
    {
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)) {
            return true;
        }

        $configured = SecuritySetting::get('whitelisted_ips', []);
        return is_array($configured) && in_array($ip, $configured, true);
    }

    /**
     * Detect SQL Injection signatures.
     */
    protected function detectSqlInjection(string $value): ?string
    {
        if (strlen($value) < 6) {
            return null;
        }

        $patterns = [
            'union\s+(all\s+)?select' => 'UNION SELECT',
            '\bselect\b.+\bfrom\b.+\binformation_schema\b' => 'INFORMATION_SCHEMA Probe',
            '\b(sleep|benchmark)\s*\(\s*\d+\s*\)' => 'Time-based SQLi',
            '\'\s*or\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+' => 'Boolean-based OR 1=1',
            '"\s*or\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+' => 'Boolean-based OR 1=1 (Double Quote)',
            '(\%27|\')\s*or\s+.*--' => 'OR Comment SQLi',
            '\bload_file\s*\(' => 'LOAD_FILE SQLi',
            '\binto\s+(out|dump)file\b' => 'INTO OUTFILE SQLi',
            'char\s*\(\s*\d+\s*(,\s*\d+\s*)+\)' => 'Char Concatenation SQLi',
            '--\s*[\r\n]' => 'SQL Line Comment',
            ';\s*(drop|alter|delete|update|insert)\s+[a-zA-Z0-9_]+' => 'Stacked Query Injection',
        ];

        foreach ($patterns as $regex => $label) {
            if (preg_match('/' . $regex . '/i', $value)) {
                return $label;
            }
        }

        return null;
    }

    /**
     * Detect Cross-Site Scripting (XSS) signatures.
     */
    protected function detectXss(string $value): ?string
    {
        if (strlen($value) < 4) {
            return null;
        }

        $patterns = [
            '<script\b[^>]*>' => '<script> Tag Injection',
            'javascript\s*:\s*[^\s]+' => 'Javascript: URI Handler',
            'vbscript\s*:\s*[^\s]+' => 'VBScript: URI Handler',
            'on(error|load|click|mouseover|submit|focus|blur)\s*=' => 'Inline Event Handler (onerror/onload)',
            '<svg\b[^>]*onload\s*=' => '<svg onload=> Injection',
            '<iframe\b[^>]*>' => '<iframe> Injection',
            'document\.(cookie|location|domain)' => 'DOM Property Access (document.cookie)',
            'base64\s*,\s*[A-Za-z0-9+\/]{20,}={0,2}' => 'Potential Base64 Payload Execution',
        ];

        foreach ($patterns as $regex => $label) {
            if (preg_match('/' . $regex . '/i', $value)) {
                return $label;
            }
        }

        return null;
    }

    /**
     * Detect Path Traversal / Local File Inclusion.
     */
    protected function detectPathTraversal(string $value): ?string
    {
        $patterns = [
            '(\.\.[\/\\\\])+' => 'Directory Traversal (../ or ..\\)',
            '(\%2e\%2e[\%2f\%5c])+' => 'URL Encoded Traversal (%2e%2e/)',
            '(\/etc\/(passwd|shadow|hosts))' => 'Linux Sensitive System File Probe',
            '(c:\\\\boot\.ini|win\.ini)' => 'Windows Sensitive System File Probe',
            'php:\/\/(filter|input|data)' => 'PHP Wrapper Stream Exploitation',
        ];

        foreach ($patterns as $regex => $label) {
            if (preg_match('/' . $regex . '/i', $value)) {
                return $label;
            }
        }

        return null;
    }

    /**
     * Detect Exploit Probes and Automated Security Scanners.
     */
    protected function detectExploitProbe(string $path, string $userAgent): ?array
    {
        // 1. Probe Paths
        $probePaths = [
            '^\/\.env' => 'Environment Secrets Probe (.env)',
            '^\/\.git' => 'Git Repository Extraction (.git)',
            '^\/(wp-login|wp-admin|xmlrpc)\.php' => 'WordPress Scanner Probe',
            '^\/(phpmyadmin|pma|myadmin)' => 'phpMyAdmin Database Probe',
            '^\/eval-stdin\.php' => 'PHP CGI Remote Code Execution Probe',
            '^\/(actuator|console|autodiscover)' => 'Framework Actuator / Management Probe',
            '^\/\.aws\/(credentials|config)' => 'Cloud Credential Probe (.aws)',
            '^\/(setup|install)\.php' => 'Installation Script Probe',
        ];

        foreach ($probePaths as $regex => $label) {
            if (preg_match('/' . $regex . '/i', $path)) {
                return [
                    'threat_type' => 'scanner_probe',
                    'severity' => 'high',
                    'confidence_score' => 95,
                    'detected_rule' => $label,
                    'payload_sample' => "Probed Path: {$path}",
                ];
            }
        }

        // 2. Malicious / Automated Scanner User Agents
        $scannerAgents = [
            'sqlmap' => 'SQLMap Automated Exploitation Tool',
            'nikto' => 'Nikto Web Vulnerability Scanner',
            'acunetix' => 'Acunetix Vulnerability Scanner',
            'masscan' => 'Masscan Port / Vulnerability Scanner',
            'zgrab' => 'ZGrab Banner Grabbing Tool',
            'nmap' => 'Nmap Security Scanner',
            'dirbuster' => 'DirBuster Directory Enumeration Tool',
            'gobuster' => 'GoBuster Directory Enumeration Tool',
            'wpscan' => 'WPScan Automated Tool',
        ];

        foreach ($scannerAgents as $key => $label) {
            if (stripos($userAgent, $key) !== false) {
                return [
                    'threat_type' => 'scanner_probe',
                    'severity' => 'high',
                    'confidence_score' => 95,
                    'detected_rule' => $label,
                    'payload_sample' => "User-Agent: {$userAgent}",
                ];
            }
        }

        return null;
    }

    /**
     * Format a detected incident structure.
     */
    protected function formatDetection(Request $request, array $details): array
    {
        return array_merge([
            'incident_uuid' => 'INC-' . strtoupper(Str::random(10)),
            'source_ip' => $request->ip(),
            'user_agent' => Str::limit($request->header('User-Agent', ''), 255),
            'request_method' => $request->method(),
            'request_url' => Str::limit($request->fullUrl(), 500),
            'target_guard' => auth('host')->check() ? 'host' : (auth('web')->check() ? 'web' : 'public'),
            'user_id' => auth('web')->id(),
            'host_id' => auth('host')->id(),
            'status' => 'active',
            'containment_action' => 'request_blocked',
        ], $details);
    }

    /**
     * Recursively flatten request inputs into key-value strings, redacting sensitive parameters.
     */
    protected function flattenAndSanitizeInputs(array $data, string $prefix = ''): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $fullKey = $prefix !== '' ? "{$prefix}.{$key}" : (string) $key;

            // Skip passwords and sensitive credentials from analysis to prevent false positives and leaks
            if (in_array(strtolower((string) $key), $this->redactedKeys, true)) {
                continue;
            }

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenAndSanitizeInputs($value, $fullKey));
            } elseif (is_scalar($value)) {
                $result[$fullKey] = (string) $value;
            }
        }

        return $result;
    }
}


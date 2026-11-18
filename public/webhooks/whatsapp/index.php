<?php
/**
 * WhatsApp Cloud API Webhook Entrypoint & Fast Verification Bridge.
 * Handles Meta GET verification challenges and routes POST event streams to Laravel.
 */

// 1. Meta Webhook Verification (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $mode = $_GET['hub_mode'] ?? $_GET['hub.mode'] ?? null;
    $token = $_GET['hub_verify_token'] ?? $_GET['hub.verify_token'] ?? null;
    $challenge = $_GET['hub_challenge'] ?? $_GET['hub.challenge'] ?? null;

    // Load expected verify token from .env or fallback to hardcoded master verify token
    $expectedToken = null;
    $envPath = __DIR__ . '/../../../.env';
    if (file_exists($envPath)) {
        $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($envLines as $line) {
            if (str_starts_with(trim($line), 'WHATSAPP_WEBHOOK_VERIFY_TOKEN=')) {
                $parts = explode('=', $line, 2);
                $expectedToken = trim($parts[1] ?? '', " \t\n\r\0\x0B\"'");
                break;
            }
        }
    }

    // Master fallback token
    if (empty($expectedToken)) {
        $expectedToken = '759ee4c79112c7190f93e34257d9d7d697bba36d25009ce4';
    }

    if ($mode === 'subscribe' && !empty($expectedToken) && hash_equals($expectedToken, (string) $token)) {
        header('Content-Type: text/plain; charset=utf-8');
        http_response_code(200);
        echo (string) $challenge;
        exit;
    }

    http_response_code(403);
    echo 'Forbidden: Webhook verification failed';
    exit;
}

// 2. Incoming Webhook POST Events -> Delegate directly to Laravel Front Controller
require __DIR__ . '/../../index.php';


<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class TestWhatsAppIntegration extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'whatsapp:test {--phone=} {--message=Hello, this is a test message from MyCard}';

    /**
     * The console command description.
     */
    protected $description = 'Test WhatsApp Business API integration by sending a test message';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService)
    {
        $this->info('WhatsApp Integration Test');
        $this->newLine();

        // Check configuration
        $this->info('1. Checking WhatsApp configuration...');
        
        if (!$whatsAppService->isConfigured()) {
            $this->error('WhatsApp service is not configured!');
            $this->newLine();
            $this->warn('Please add the following to your .env file:');
            $this->line('WHATSAPP_ACCESS_TOKEN=your_access_token');
            $this->line('WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id');
            $this->line('WHATSAPP_GRAPH_API_VERSION=v18.0');
            $this->line('WHATSAPP_WEBHOOK_VERIFY_TOKEN=your_webhook_token');
            return Command::FAILURE;
        }

        $this->info('WhatsApp configuration found');
        $this->newLine();

        // Test connection
        $this->info('2. Testing connection to WhatsApp API...');
        $connectionResult = $whatsAppService->testConnection();

        if (!$connectionResult['success']) {
            $this->error('Connection test failed!');
            $this->error('Error: ' . $connectionResult['error']);
            return Command::FAILURE;
        }

        $this->info('Connection test successful');
        $this->newLine();

        // Get phone number for test
        $phoneNumber = $this->option('phone');
        
        if (!$phoneNumber) {
            $phoneNumber = $this->ask('Enter phone number to send test message (with country code, e.g., +255655123456)');
            
            if (!$phoneNumber) {
                $this->error('Phone number is required for test message');
                return Command::FAILURE;
            }
        }

        // Validate phone number format
        if (!preg_match('/^(\+?255|0)\d{9}$/', preg_replace('/[^\d+]/', '', $phoneNumber))) {
            $this->warn('Phone number format may be incorrect for Tanzania');
            $this->line('Expected format: +255655123456 or 0655123456');
            
            if (!$this->confirm('Continue anyway?')) {
                return Command::SUCCESS;
            }
        }

        // Send test message
        $this->info('3. Sending test message...');
        $message = $this->option('message');
        
        $result = $whatsAppService->sendTextMessage($phoneNumber, $message);

        if ($result['success']) {
            $this->info('Test message sent successfully!');
            $this->line('Message ID: ' . ($result['message_id'] ?? 'N/A'));
            
            $this->newLine();
            $this->info('Check the recipient\'s WhatsApp for the test message');
            
        } else {
            $this->error('Test message failed!');
            $this->error('Error: ' . ($result['error'] ?? 'Unknown error'));
            $this->error('Status: ' . ($result['status'] ?? 'N/A'));
            
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('WhatsApp integration test completed successfully!');
        
        // Additional info
        $this->newLine();
        $this->info('Next steps:');
        $this->line('1. Set up your webhook URL: ' . url('webhook/whatsapp/verify'));
        $this->line('2. Configure webhook in WhatsApp Business API settings');
        $this->line('3. Start sending invitations through the Host dashboard');

        return Command::SUCCESS;
    }
}
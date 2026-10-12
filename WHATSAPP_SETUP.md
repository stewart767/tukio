# WhatsApp Business API Integration Setup

## Required Environment Variables

Add these variables to your `.env` file:

```env
# WhatsApp Business API Configuration
WHATSAPP_ACCESS_TOKEN=your_whatsapp_access_token_here
WHATSAPP_PHONE_NATURE_ID=your_phone_number_id_here
WHATSAPP_GRAPH_API_VERSION=v18.0
WHATSAPP_WEBHOOK_VERIFY_TOKEN=your_webhook_verify_token_here
```

## Getting WhatsApp Business API Credentials

### 1. Create WhatsApp Business Account
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a Facebook Business Manager account
3. Set up WhatsApp Business API

### 2. Get Access Token
1. In Facebook Developers Console, go to your WhatsApp Business app
2. Navigate to WhatsApp → API Setup
3. Copy your Access Token

### 3. Get Phone Number ID
1. In the same API Setup section
2. Copy the Phone Number ID

### 4. Set Webhook Verify Token
1. Create a random secure token (min 32 characters)
2. Use this for webhook verification

## Webhook Configuration

Set up your webhook URL in WhatsApp Business API:
- **Webhook URL**: `https://yourdomain.com/webhook/whatsapp/webhook`
- **Verify Token**: Use the same token as `WHATSAPP_WEBHOOK_VERIFY_TOKEN`
- **Events**: Subscribe to `messages` and `message_deliveries`

## Installation Steps

1. **Install Dependencies**:
   ```bash
   composer require muhammadnuman/whatsapp-cloud-api
   ```

2. **Configure Environment**:
   ```bash
   # Add WhatsApp configuration to your .env file
   echo "WHATSAPP_ACCESS_TOKEN=your_token_here" >> .env
   echo "WHATSAPP_PHONE_NUMBER_ID=your_phone_id_here" >> .env
   echo "WHATSAPP_GRAPH_API_VERSION=v18.0" >> .env
   echo "WHATSAPP_WEBHOOK_VERIFY_TOKEN=your_webhook_token_here" >> .env
   ```

3. **Test Connection**:
   ```bash
   # Test via command line
   php artisan whatsapp:test --phone=+255655123456
   
   # Or test via web interface
   # Go to Host Dashboard → Invitations → Click "Test WhatsApp" button
   ```

4. **Test Command Line Integration**:
   ```bash
   php artisan whatsapp:test --help
   php artisan whatsapp:test --phone=+255655123456 --message="Custom test message"
   ```

5. **Send Invitations**:
   - Navigate to "Send Invitations" page
   - Select WhatsApp as sending method
   - Choose guests and send invitations

## Features Added

### WhatsAppService Class
- Send text messages
- Send image attachments
- Bulk invitation sending
- Connection testing
- Error handling and logging

### InvitationController Updates
- Automatic WhatsApp sending when invitation is created
- Resend failed invitations
- WhatsApp connection testing
- Status tracking

### UI Enhancements
- Test WhatsApp button on invitations page
- Resend button for failed invitations
- Real-time status updates
- Error messaging

## Testing Phone Number Format

The system automatically formats phone numbers for Tanzania (+255):
- Input: `0655123456` → Output: `255655123456`
- Input: `+255655123456` → Output: `255655123456`

## Rate Limiting

The system includes automatic delays between message sends:
- 0.5 seconds between individual invitations
- 1 second between bulk invitation batches

## Error Handling

- Connection failures are logged
- Retry mechanisms for failed sends
- Status tracking (pending, sent, delivered, failed)
- User-friendly error messages

## Troubleshooting

### Common Issues

1. **Connection Test Fails**:
   - Check ACCESS_TOKEN is valid
   - Verify PHONE_NUMBER_ID is correct
   - Ensure app has WhatsApp permissions

2. **Messages Not Sending**:
   - Verify phone number format
   - Check rate limiting
   - Review API logs

3. **Images Not Uploading**:
   - Check file permissions
   - Verify image format (JPEG/PNG)
   - Ensure temp directory is writable

### Debug Mode

Enable detailed logging by setting `LOG_LEVEL=debug` in your `.env` file and check `storage/logs/laravel.log`.

## Security Notes

- Never commit WhatsApp tokens to version control
- Use environment variables for all sensitive data
- Regularly rotate access tokens
- Monitor API usage for unusual activity

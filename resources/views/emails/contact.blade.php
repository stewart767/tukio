<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #9333ea 0%, #db2777 50%, #c026d3 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">New Contact Form Submission</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="margin-top: 0;">You have received a new message from the contact form on <strong>{{ $brandName }}</strong> website.</p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #9333ea;">
            <p style="margin: 0 0 15px 0;"><strong>Name:</strong> {{ $name }}</p>
            <p style="margin: 0 0 15px 0;"><strong>Email:</strong> <a href="mailto:{{ $email }}" style="color: #9333ea; text-decoration: none;">{{ $email }}</a></p>
            @if($subject)
            <p style="margin: 0 0 15px 0;"><strong>Subject:</strong> {{ $subject }}</p>
            @endif
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #9333ea; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">Message:</h3>
            <p style="white-space: pre-wrap; margin: 0;">{{ $message }}</p>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="color: #6b7280; font-size: 14px; margin: 0;">
                This email was sent from the contact form on {{ $brandName }} website.<br>
                You can reply directly to this email to respond to {{ $name }}.
            </p>
        </div>
    </div>
</body>
</html>

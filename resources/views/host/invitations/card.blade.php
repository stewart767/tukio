<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Card - {{ $invitationData['guest_name'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .print-card { 
                width: 100% !important; 
                height: 100vh !important; 
                margin: 0 !important; 
                padding: 0 !important;
            }
        }
        
        .invitation-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Georgia', serif;
        }
        
        .card-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            margin: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .card-body {
            padding: 40px 30px;
            text-align: center;
        }
        
        .guest-name {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #2d3748;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .event-title {
            font-size: 1.8rem;
            color: #4a5568;
            margin-bottom: 30px;
            font-style: italic;
        }
        
        .event-details {
            background: #f7fafc;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            border-left: 5px solid #667eea;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0;
            font-size: 1.1rem;
        }
        
        .detail-icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            color: #667eea;
        }
        
        .qr-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
        }
        
        .qr-code {
            width: 120px;
            height: 120px;
            margin: 0 auto 15px;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        
        .couple-photo-section {
            position: relative;
        }
        
        .couple-photo-section::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 140px;
            height: 140px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.1;
        }
        
        .couple-names {
            font-family: 'Georgia', serif;
            font-style: italic;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    @if(isset($download) && $download)

    <div class="invitation-card">
        <div class="card-content print-card">
            <!-- Header -->
            <div class="card-header">
                <div style="position: relative; z-index: 1;">
                    <h1 class="text-4xl font-bold mb-4">You're Invited!</h1>
                    <p class="text-xl opacity-90">Join us for a special celebration</p>
                </div>
            </div>
            
            <!-- Body -->
            <div class="card-body">
                <!-- Couple's Photo -->
                @php
                    $couplePhoto = null;
                    $eventData = $invitationData['event_data'] ?? [];
                    $hostEventData = $invitationData['host_event_data'] ?? [];
                    
                    // Try to get photo from multiple possible field names
                    $couplePhoto = $eventData['couple_photo_main'] ?? 
                                  $eventData['couple_photo'] ?? 
                                  $eventData['couple_photo_secondary'] ??
                                  $eventData['photo'] ??
                                  $eventData['event_banner'] ??
                                  $hostEventData['couple_photo_main'] ?? 
                                  $hostEventData['couple_photo'] ?? 
                                  $hostEventData['couple_photo_secondary'] ??
                                  $hostEventData['photo'] ??
                                  $hostEventData['event_banner'] ?? null;
                @endphp
                
                @if($couplePhoto)
                <div class="couple-photo-section mb-6">
                    <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-white shadow-lg">
                        <img src="{{ storage_url('event_uploads/' . $couplePhoto) }}" 
                             alt="Couple's Photo" 
                             class="w-full h-full object-cover"
                             onerror="console.log('Image failed to load:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             onload="checkImageSize(this);">
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center" style="display: none;">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-2">Photo: {{ $couplePhoto }}</p>
                    <p class="text-xs text-blue-500 text-center">URL: {{ storage_url('event_uploads/' . $couplePhoto) }}</p>
                </div>
                @else
                <div class="couple-photo-section mb-6">
                    <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-2">No photo available</p>
                </div>
                @endif
                
                <div class="guest-name">{{ $invitationData['guest_name'] }}</div>
                <div class="event-title">{{ $invitationData['event_title'] }}</div>
                
                @php
                    $groomName = $invitationData['host_event_data']['groom_name'] ?? $invitationData['event_data']['groom_name'] ?? null;
                    $brideName = $invitationData['host_event_data']['bride_name'] ?? $invitationData['event_data']['bride_name'] ?? null;
                @endphp
                
                <!-- @if($groomName && $brideName)
                <div class="couple-names text-xl text-gray-600 font-medium mb-4">
                    {{ $groomName }} & {{ $brideName }}
                </div>
                @endif -->
                
                <div class="event-details">
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span><strong>Date:</strong> {{ $invitationData['event_date'] }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><strong>Time:</strong> {{ $invitationData['event_time'] }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span><strong>Venue:</strong> {{ $invitationData['venue'] }}</span>
                    </div>
                    
                    @if($invitationData['venue_address'])
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        <span><strong>Address:</strong> {{ $invitationData['venue_address'] }}</span>
                    </div>
                    @endif
                </div>
                
                @if($invitationData['description'])
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                    <p class="text-gray-700 italic">{{ $invitationData['description'] }}</p>
                </div>
                @endif
                
                <!-- QR Code Section -->
                <div class="qr-section">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Scan for Event Details</h3>
                    <div class="qr-code">
                        <canvas id="qrcode" style="display: none;"></canvas>
                        <div id="qr-fallback" class="text-center">
                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500">Loading QR Code...</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Present this QR code at the event entrance</p>
                </div>
                
     
            </div>
        </div>
    </div>

    <script>
        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            const qrString = JSON.stringify({
                guest: '{{ $invitationData["guest_name"] }}',
                event: '{{ $invitationData["event_title"] }}',
                date: '{{ $invitationData["event_date"] }}',
                venue: '{{ $invitationData["venue"] }}',
                invitation_id: '{{ $invitationData["qr_code"] }}'
            });
            
            const canvas = document.getElementById('qrcode');
            const fallback = document.getElementById('qr-fallback');
            
            if (canvas && fallback) {
                // Try multiple QR code generation methods
                generateQRCode();
            } else {
                console.error('QR Code elements not found');
            }
            
            function generateQRCode() {
                // Method 1: Try QRCode library
                if (typeof QRCode !== 'undefined') {
                    try {
                        QRCode.toCanvas(canvas, qrString, {
                            width: 100,
                            height: 100,
                            color: {
                                dark: '#000000',
                                light: '#FFFFFF'
                            },
                            margin: 1
                        }, function (error) {
                            if (error) {
                                console.error('QRCode library error:', error);
                                tryMethod2();
                            } else {
                                console.log('QR Code generated successfully with QRCode library');
                                showQRCode();
                            }
                        });
                    } catch (e) {
                        console.error('QRCode library exception:', e);
                        tryMethod2();
                    }
                } else {
                    console.log('QRCode library not available, trying alternative');
                    tryMethod2();
                }
            }
            
            function tryMethod2() {
                // Method 2: Try qrcode-generator library
                if (typeof qrcode !== 'undefined') {
                    try {
                        const qr = qrcode(0, 'M');
                        qr.addData(qrString);
                        qr.make();
                        
                        const size = 100;
                        const cellSize = Math.floor(size / qr.getModuleCount());
                        const offset = Math.floor((size - qr.getModuleCount() * cellSize) / 2);
                        
                        const ctx = canvas.getContext('2d');
                        canvas.width = size;
                        canvas.height = size;
                        
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillRect(0, 0, size, size);
                        
                        ctx.fillStyle = '#000000';
                        for (let row = 0; row < qr.getModuleCount(); row++) {
                            for (let col = 0; col < qr.getModuleCount(); col++) {
                                if (qr.isDark(row, col)) {
                                    ctx.fillRect(
                                        col * cellSize + offset,
                                        row * cellSize + offset,
                                        cellSize,
                                        cellSize
                                    );
                                }
                            }
                        }
                        
                        console.log('QR Code generated successfully with qrcode-generator library');
                        showQRCode();
                    } catch (e) {
                        console.error('qrcode-generator library error:', e);
                        showError();
                    }
                } else {
                    console.log('qrcode-generator library not available');
                    showError();
                }
            }
            
            function showQRCode() {
                fallback.style.display = 'none';
                canvas.style.display = 'block';
            }
            
            function showError() {
                fallback.innerHTML = `
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <p class="text-red-500 text-sm">QR Code unavailable</p>
                        <p class="text-xs text-gray-500 mt-1">Guest: {{ $invitationData["guest_name"] }}</p>
                    </div>
                `;
            }
        });
        
        // Function to check if image is too small (likely corrupted/placeholder)
        function checkImageSize(img) {
            console.log('Image loaded successfully:', img.src);
            
            // Check if image is too small (less than 50x50 pixels)
            if (img.naturalWidth < 50 || img.naturalHeight < 50) {
                console.log('Image too small, showing fallback. Dimensions:', img.naturalWidth + 'x' + img.naturalHeight);
                img.style.display = 'none';
                img.nextElementSibling.style.display = 'flex';
                
                // Update the fallback text
                const fallbackText = img.nextElementSibling.querySelector('svg').parentElement;
                if (fallbackText) {
                    fallbackText.innerHTML = `
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Image too small or corrupted</p>
                        </div>
                    `;
                }
            } else {
                console.log('Image dimensions OK:', img.naturalWidth + 'x' + img.naturalHeight);
                img.style.display = 'block';
                img.nextElementSibling.style.display = 'none';
            }
        }
        
    </script>
</body>
</html>

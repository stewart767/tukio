<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Card - {{ $invitationData['guest_name'] }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .invitation-card {
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
            background: rgba(255, 255, 255, 0.1);
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
            margin: 0 auto;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .couple-photo-section {
            margin-bottom: 30px;
        }
        
        .couple-photo {
            width: 128px;
            height: 128px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .couple-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.875rem;
            text-align: center;
            padding: 10px;
        }
        
        .photo-info {
            margin-top: 10px;
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
        }
        
        .header-text {
            position: relative;
            z-index: 1;
        }
        
        .header-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="invitation-card">
        <!-- Header -->
        <div class="card-header">
            <div class="header-text">
                <div class="header-title">You're Invited!</div>
                <div class="header-subtitle">Join us for a special celebration</div>
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
            
            <div class="couple-photo-section">
                <div class="couple-photo">
                    @if($couplePhoto && file_exists(public_path('storage/event_uploads/' . $couplePhoto)))
                        @php
                            $imagePath = public_path('storage/event_uploads/' . $couplePhoto);
                            $imageSize = getimagesize($imagePath);
                        @endphp
                        @if($imageSize && $imageSize[0] >= 50 && $imageSize[1] >= 50)
                            <img src="{{ public_path('storage/event_uploads/' . $couplePhoto) }}" alt="Couple's Photo">
                        @else
                            <div class="photo-placeholder">
                                <div>
                                    <div style="font-weight: bold; margin-bottom: 5px;">PHOTO</div>
                                    <div>Image too small</div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="photo-placeholder">
                            <div>
                                <div style="font-weight: bold; margin-bottom: 5px;">PHOTO</div>
                                <div>No photo available</div>
                            </div>
                        </div>
                    @endif
                </div>
                @if($couplePhoto)
                    <div class="photo-info">Photo: {{ $couplePhoto }}</div>
                @endif
            </div>
            
            <div class="guest-name">{{ $invitationData['guest_name'] }}</div>
            <div class="event-title">{{ $invitationData['event_title'] }}</div>
            
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
                
                @if(!empty($invitationData['venue']))
                <div class="detail-item">
                    <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span><strong>Venue:</strong> {{ $invitationData['venue'] }}</span>
                </div>
                @endif
                
                @if(!empty($invitationData['venue_address']))
                <div class="detail-item">
                    <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span><strong>Address:</strong> {{ $invitationData['venue_address'] }}</span>
                </div>
                @endif
            </div>
            
            @if(!empty($invitationData['description']))
            <div style="margin: 30px 0; padding: 20px; background: #fef3c7; border-radius: 10px; border-left: 4px solid #f59e0b;">
                <p style="margin: 0; font-style: italic; color: #92400e;">{{ $invitationData['description'] }}</p>
            </div>
            @endif
            
            <!-- QR Code Section -->
            <div class="qr-section">
                <h3 style="margin: 0 0 15px 0; color: #374151; font-size: 1.1rem;">RSVP & More Info</h3>
                <div class="qr-code">
                    @if(!empty($invitationData['qr_code_path']))
                        <img src="{{ storage_url($invitationData['qr_code_path']) }}" alt="Invitation QR Code" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <div style="text-align: center; color: #6b7280; font-size: 0.875rem;">
                            <div style="font-size: 1.1rem; font-weight: bold; margin-bottom: 5px; color: #4f46e5;">QR PASS</div>
                            <div>QR Code</div>
                            <div style="font-size: 0.75rem; margin-top: 5px;">{{ $invitationData['guest_name'] }}</div>
                        </div>
                    @endif
                </div>
                <p style="margin: 15px 0 0 0; font-size: 0.875rem; color: #6b7280;">Scan for event details and RSVP</p>
            </div>
        </div>
    </div>
</body>
</html>

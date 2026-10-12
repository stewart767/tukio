@extends('layouts.dashboard')

@section('title', 'QR Code Scanner')

@push('styles')
<style>
    #reader {
        border-radius: 8px;
        overflow: hidden;
    }
    
    #reader video {
        width: 100%;
        max-width: 400px;
        height: auto;
        border-radius: 8px;
    }
    
    #reader__scan_region {
        border-radius: 8px;
    }
    
    #reader__dashboard_section {
        padding: 10px;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">QR Code Scanner</h1>
        <p class="text-gray-600 mt-1">Scan guest QR codes to check them in</p>
    </div>

    <!-- Assigned Events Info -->
    @if(isset($assignedEvents) && $assignedEvents->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">Your Assigned Events</h3>
                    <p class="text-sm text-blue-700 mb-2">You can mark attendance for the following events:</p>
                    <ul class="list-disc list-inside text-sm text-blue-700 space-y-1">
                        @foreach($assignedEvents as $event)
                            <li>{{ $event->title }} - {{ $event->host->full_name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-amber-900">No Events Assigned</h3>
                    <p class="text-sm text-amber-700 mt-1">You haven't been assigned to any events yet. Please contact an administrator to be assigned to an event.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Scanner Interface -->
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <!-- Scanner Area -->
            <div class="text-center mb-6">
                <div id="reader" class="w-full max-w-md mx-auto mb-4" style="display: none;"></div>
                <div id="scanner_placeholder" class="w-64 h-64 mx-auto bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center mb-4">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"></path>
                        </svg>
                        <p class="text-sm text-gray-500 mt-2">QR Code Scanner</p>
                    </div>
                </div>
                <div id="camera_controls" class="space-y-2">
                    <button id="start_camera_btn" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Start Camera
                    </button>
                    <button id="stop_camera_btn" 
                            class="hidden inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                        </svg>
                        Stop Camera
                    </button>
                </div>
                <p id="scanner_status" class="text-sm text-gray-600 mt-2">Click "Start Camera" to begin scanning</p>
            </div>

            <!-- Manual Input -->
            <div class="space-y-4">
                <div>
                    <label for="qr_input" class="block text-sm font-medium text-gray-700">Or enter invitation URL/slug/code manually:</label>
                    <input type="text" id="qr_input" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                           placeholder="https://.../invitation/{slug} or code">
                </div>
                <button id="scan_btn" 
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5z"></path>
                    </svg>
                    Check In Guest
                </button>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div id="scan_result" class="max-w-md mx-auto hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div id="result_content"></div>
        </div>
    </div>

    <!-- Recent Scans -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Check-ins</h3>
            </div>
            <div class="p-6">
                <div id="recent_scans" class="space-y-3">
                    <!-- Recent scans will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- html5-qrcode library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrInput = document.getElementById('qr_input');
    const scanBtn = document.getElementById('scan_btn');
    const scanResult = document.getElementById('scan_result');
    const resultContent = document.getElementById('result_content');
    const recentScans = document.getElementById('recent_scans');
    const reader = document.getElementById('reader');
    const scannerPlaceholder = document.getElementById('scanner_placeholder');
    const startCameraBtn = document.getElementById('start_camera_btn');
    const stopCameraBtn = document.getElementById('stop_camera_btn');
    const scannerStatus = document.getElementById('scanner_status');
    
    let html5QrcodeScanner = null;
    let isScanning = false;

    // Load recent scans
    loadRecentScans();
    
    // Initialize camera scanner
    function initCameraScanner() {
        if (html5QrcodeScanner) {
            return; // Already initialized
        }
        
        html5QrcodeScanner = new Html5Qrcode("reader");
    }
    
    // Start camera
    startCameraBtn.addEventListener('click', function() {
        startCamera();
    });
    
    // Stop camera
    stopCameraBtn.addEventListener('click', function() {
        stopCamera();
    });
    
    async function startCamera() {
        if (isScanning) {
            return;
        }
        
        try {
            initCameraScanner();
            
            scannerStatus.textContent = 'Starting camera...';
            startCameraBtn.disabled = true;
            
            // Hide placeholder, show reader
            scannerPlaceholder.style.display = 'none';
            reader.style.display = 'block';
            
            // Start scanning
            await html5QrcodeScanner.start(
                { facingMode: "environment" }, // Use back camera
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                },
                onScanSuccess,
                onScanError
            );
            
            isScanning = true;
            startCameraBtn.classList.add('hidden');
            stopCameraBtn.classList.remove('hidden');
            scannerStatus.textContent = 'Camera active - Position QR code in frame';
            scannerStatus.className = 'text-sm text-green-600 mt-2';
            
        } catch (err) {
            console.error('Error starting camera:', err);
            let errorMsg = 'Failed to start camera';
            if (err.message) {
                if (err.message.includes('Permission denied') || err.message.includes('NotAllowedError')) {
                    errorMsg = 'Camera permission denied. Please allow camera access and try again.';
                } else if (err.message.includes('NotFoundError') || err.message.includes('No camera')) {
                    errorMsg = 'No camera found. Please connect a camera device.';
                } else {
                    errorMsg = err.message;
                }
            }
            scannerStatus.textContent = 'Error: ' + errorMsg;
            scannerStatus.className = 'text-sm text-red-600 mt-2';
            scannerPlaceholder.style.display = 'flex';
            reader.style.display = 'none';
            startCameraBtn.disabled = false;
        }
    }
    
    function stopCamera() {
        if (!isScanning || !html5QrcodeScanner) {
            return;
        }
        
        html5QrcodeScanner.stop().then(() => {
            html5QrcodeScanner.clear();
            isScanning = false;
            startCameraBtn.classList.remove('hidden');
            stopCameraBtn.classList.add('hidden');
            scannerPlaceholder.style.display = 'flex';
            reader.style.display = 'none';
            scannerStatus.textContent = 'Camera stopped';
            scannerStatus.className = 'text-sm text-gray-600 mt-2';
            startCameraBtn.disabled = false;
        }).catch((err) => {
            console.error('Error stopping camera:', err);
        });
    }
    
    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning temporarily to prevent multiple scans
        if (isScanning) {
            stopCamera();
        }
        
        // Process the scanned code
        scannerStatus.textContent = 'Code detected: ' + decodedText;
        scannerStatus.className = 'text-sm text-blue-600 mt-2';
        
        // Automatically process the scanned code
        scanQRCode(decodedText);
        
        // Restart camera after a delay
        setTimeout(() => {
            if (!isScanning) {
                startCamera();
            }
        }, 2000);
    }
    
    function onScanError(errorMessage) {
        // Ignore frequent error messages to avoid console spam
        // Only log if it's a significant error
        if (errorMessage && !errorMessage.includes('NotFoundException')) {
            // console.log('Scan error:', errorMessage);
        }
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (isScanning && html5QrcodeScanner) {
            html5QrcodeScanner.stop().catch(() => {});
        }
    });

    // Handle scan button click
    scanBtn.addEventListener('click', function() {
        const qrCode = qrInput.value.trim();
        if (!qrCode) {
            alert('Please enter an invitation code');
            return;
        }
        scanQRCode(qrCode);
    });

    // Handle Enter key in input
    qrInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            scanBtn.click();
        }
    });

    function scanQRCode(qrCode) {
        scanBtn.disabled = true;
        scanBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Scanning...';

        fetch('{{ route("staff.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => response.json())
        .then(data => {
            showResult(data);
            if (data.success) {
                qrInput.value = '';
                loadRecentScans();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showResult({
                success: false,
                message: 'An error occurred while scanning'
            });
        })
        .finally(() => {
            scanBtn.disabled = false;
            scanBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5z"></path></svg>Check In Guest';
        });
    }

    function showResult(data) {
        scanResult.classList.remove('hidden');
        
        if (data.success) {
            resultContent.innerHTML = `
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-800 mb-2">Check-in Successful!</h3>
                    <p class="text-green-600 mb-4">${data.message}</p>
                    <div class="bg-green-50 rounded-lg p-4 text-left">
                        <p class="text-sm"><strong>Guest:</strong> ${data.guest.name}</p>
                        <p class="text-sm"><strong>Event:</strong> ${data.guest.event}</p>
                        <p class="text-sm"><strong>Time:</strong> ${data.guest.attended_at}</p>
                    </div>
                </div>
            `;
        } else {
            resultContent.innerHTML = `
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Check-in Failed</h3>
                    <p class="text-red-600 mb-4">${data.message}</p>
                    ${data.guest ? `
                        <div class="bg-red-50 rounded-lg p-4 text-left">
                            <p class="text-sm"><strong>Guest:</strong> ${data.guest.name}</p>
                            <p class="text-sm"><strong>Event:</strong> ${data.guest.event}</p>
                            <p class="text-sm"><strong>Already checked in:</strong> ${data.guest.attended_at}</p>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // Auto-hide result after 5 seconds
        setTimeout(() => {
            scanResult.classList.add('hidden');
        }, 5000);
    }

    function loadRecentScans() {
        fetch('{{ route("staff.attendance") }}')
        .then(response => response.text())
        .then(html => {
            // Extract recent scans from the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const scans = doc.querySelectorAll('.recent-scan-item');
            
            if (scans.length > 0) {
                recentScans.innerHTML = Array.from(scans).slice(0, 5).map(scan => scan.outerHTML).join('');
            } else {
                recentScans.innerHTML = '<p class="text-gray-500 text-center py-4">No recent check-ins</p>';
            }
        })
        .catch(error => {
            console.error('Error loading recent scans:', error);
        });
    }
});
</script>
@endpush
@endsection


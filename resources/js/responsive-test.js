// Responsive Design Test Script

document.addEventListener('DOMContentLoaded', function() {
    // Test responsive breakpoints
    function updateBreakpointInfo() {
        const width = window.innerWidth;
        const breakpointElement = document.getElementById('current-breakpoint');
        
        if (breakpointElement) {
            let breakpoint = 'Unknown';
            if (width < 640) {
                breakpoint = 'Mobile (sm)';
            } else if (width < 768) {
                breakpoint = 'Tablet (md)';
            } else if (width < 1024) {
                breakpoint = 'Laptop (lg)';
            } else if (width < 1280) {
                breakpoint = 'Desktop (xl)';
            } else {
                breakpoint = 'Large Desktop (2xl)';
            }
            
            breakpointElement.textContent = `${breakpoint} - ${width}px`;
        }
    }
    
    // Update on load and resize
    updateBreakpointInfo();
    window.addEventListener('resize', updateBreakpointInfo);
    
    // Touch device detection
    const touchDeviceElement = document.getElementById('touch-device');
    if (touchDeviceElement) {
        touchDeviceElement.textContent = 'Touch Device: ' + 
            ('ontouchstart' in window ? 'Yes' : 'No');
    }
    
    // Add responsive class to body based on screen size
    function updateResponsiveClass() {
        const body = document.body;
        const width = window.innerWidth;
        
        body.classList.remove('screen-xs', 'screen-sm', 'screen-md', 'screen-lg', 'screen-xl', 'screen-2xl');
        
        if (width < 640) {
            body.classList.add('screen-xs');
        } else if (width < 768) {
            body.classList.add('screen-sm');
        } else if (width < 1024) {
            body.classList.add('screen-md');
        } else if (width < 1280) {
            body.classList.add('screen-lg');
        } else if (width < 1536) {
            body.classList.add('screen-xl');
        } else {
            body.classList.add('screen-2xl');
        }
    }
    
    updateResponsiveClass();
    window.addEventListener('resize', updateResponsiveClass);
    
    // Test touch interactions
    const testElements = document.querySelectorAll('.touch-test');
    testElements.forEach(element => {
        element.addEventListener('touchstart', function(e) {
            this.classList.add('touch-active');
        });
        
        element.addEventListener('touchend', function(e) {
            this.classList.remove('touch-active');
        });
    });
});
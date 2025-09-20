/**
 * Security Layer untuk Form Protection
 * Mencegah form fallback ke GET method yang mengekspos data sensitif
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔒 Security layer initialized');
    
    // 1. Protect Livewire forms dari fallback ke GET
    const livewireForms = document.querySelectorAll('form[wire\\:submit]');
    
    livewireForms.forEach(form => {
        // Ensure form has POST method
        if (!form.hasAttribute('method') || form.getAttribute('method').toLowerCase() !== 'post') {
            console.warn('⚠️ Form missing POST method, adding security enforcement');
            form.setAttribute('method', 'POST');
        }
        
        // Add security validation before submit
        form.addEventListener('submit', function(e) {
            // Check if Livewire is available - dengan delay untuk memastikan Livewire sudah loaded
            setTimeout(() => {
                if (typeof Livewire === 'undefined' || typeof window.Livewire === 'undefined') {
                    console.warn('⚠️ Livewire not fully loaded, but allowing form submission');
                    // Tidak prevent submit, hanya warning
                }
            }, 100);
            
            // Check for sensitive data in URL (should never happen)
            const url = new URL(window.location.href);
            const sensitiveParams = ['password', 'password_confirmation', 'email'];
            const hasSensitiveData = sensitiveParams.some(param => url.searchParams.has(param));
            
            if (hasSensitiveData) {
                console.error('🚨 SECURITY BREACH: Sensitive data detected in URL');
                // Clear URL parameters
                window.history.replaceState({}, document.title, window.location.pathname);
                alert('Security issue detected. Page has been cleaned. Please try again.');
                return false;
            }
            
            console.log('✅ Form security check passed');
        });
    });
    
    // 2. Monitor for URL parameter exposure
    function checkURLSecurity() {
        const url = new URL(window.location.href);
        const sensitiveParams = ['password', 'password_confirmation'];
        
        sensitiveParams.forEach(param => {
            if (url.searchParams.has(param)) {
                console.error(`🚨 SECURITY ALERT: ${param} found in URL`);
                // Immediately clean the URL
                url.searchParams.delete(param);
                window.history.replaceState({}, document.title, url.toString());
            }
        });
    }
    
    // Run security check on page load
    checkURLSecurity();
    
    // 3. Add global error handler untuk JavaScript errors
    window.addEventListener('error', function(e) {
        console.error('JavaScript Error detected:', e.error);
        
        // If error affects Livewire, show warning
        if (e.error && e.error.message && e.error.message.includes('Livewire')) {
            console.warn('⚠️ Livewire error detected - forms may fallback to unsafe methods');
        }
    });
    
    // 4. Livewire availability check dengan delay yang lebih pendek
    setTimeout(() => {
        if (typeof Livewire === 'undefined') {
            console.warn('⚠️ Livewire not loaded after 1 second - checking again...');
            
            // Check lagi setelah 2 detik
            setTimeout(() => {
            if (typeof Livewire === 'undefined') {
                console.error('🚨 CRITICAL: Livewire not loaded after 3 seconds total');
                // Warning logic removed - Livewire styles now properly loaded
            } else {
                console.log('✅ Livewire loaded successfully (delayed)');
            }
        }, 2000);
        } else {
            console.log('✅ Livewire loaded successfully');
        }
    }, 1000); // Reduced from 2000ms to 1000ms
    
    // 5. CSRF Token validation
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('🚨 SECURITY: CSRF token not found');
    } else {
        console.log('✅ CSRF token present');
    }
});

// 6. Export security functions for debugging
window.TimeKeeperSecurity = {
    checkLivewire: function() {
        return typeof Livewire !== 'undefined';
    },
    
    checkCSRF: function() {
        return document.querySelector('meta[name="csrf-token"]') !== null;
    },
    
    cleanURL: function() {
        const url = new URL(window.location.href);
        const sensitiveParams = ['password', 'password_confirmation', 'email'];
        let cleaned = false;
        
        sensitiveParams.forEach(param => {
            if (url.searchParams.has(param)) {
                url.searchParams.delete(param);
                cleaned = true;
            }
        });
        
        if (cleaned) {
            window.history.replaceState({}, document.title, url.toString());
            console.log('✅ URL cleaned from sensitive parameters');
        }
        
        return cleaned;
    },
    
    securityReport: function() {
        return {
            livewire: this.checkLivewire(),
            csrf: this.checkCSRF(),
            url_clean: !new URL(window.location.href).searchParams.has('password'),
            timestamp: new Date().toISOString()
        };
    }
};
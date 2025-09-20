// Performance optimization utilities
class PerformanceOptimizer {
    constructor() {
        this.observers = new Map();
        this.lazyImages = [];
        this.init();
    }

    init() {
        // Initialize performance optimizations
        this.setupLazyLoading();
        this.setupIntersectionObserver();
        this.setupPreloadCriticalResources();
        this.setupServiceWorker();
    }

    // Lazy loading untuk images
    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            // Observe all lazy images
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    // Intersection Observer untuk animasi dan loading
    setupIntersectionObserver() {
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, {
                threshold: 0.1
            });

            // Observe elements with animation classes
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                animationObserver.observe(el);
            });
        }
    }

    // Preload critical resources (SAFE, menghormati Vite @vite)
    setupPreloadCriticalResources() {
        try {
            // Jika Vite sudah menyuntikkan assets (production hashed files), jangan lakukan apa-apa
            const viteCss = document.querySelector('link[rel="stylesheet"][href*="/assets/"][href$=".css"]');
            const viteJs = document.querySelector('script[type="module"][src*="/assets/"][src$=".js"]');
            if (viteCss || viteJs) {
                // Hindari duplikasi preload/stylesheet/script dan potensi warning
                return;
            }

            // Fallback hanya untuk DEV: gunakan path dari tag yang memang sudah ada
            const devCssTag = document.querySelector('link[rel="stylesheet"][href*="/resources/css/app.css"]');
            const devJsTag = document.querySelector('script[type="module"][src*="/resources/js/app.js"]');

            const criticalResources = [];
            if (devCssTag) criticalResources.push({ href: devCssTag.href, as: 'style' });
            if (devJsTag) criticalResources.push({ href: devJsTag.src, as: 'script' });

            // Jika tidak ada tag referensi yang valid, jangan melakukan preload hardcoded
            if (criticalResources.length === 0) return;

            criticalResources.forEach(resource => {
                // Skip jika sudah ada preload untuk resource yang sama
                if (document.querySelector(`link[rel="preload"][href="${resource.href}"]`)) return;

                const link = document.createElement('link');
                link.rel = 'preload';
                link.href = resource.href;
                link.as = resource.as;
                // Tidak mengubah menjadi stylesheet/script untuk mencegah duplikasi
                document.head.appendChild(link);
            });
        } catch (e) {
            console.debug('setupPreloadCriticalResources skipped:', e);
        }
    }

    // Service Worker untuk caching
    setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    }

    // Debounce function untuk event handling
    debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    }

    // Throttle function untuk scroll events
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Memory management
    cleanup() {
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();
        this.lazyImages = [];
    }

    // Performance monitoring
    measurePerformance() {
        if ('performance' in window) {
            const navigation = performance.getEntriesByType('navigation')[0];
            const paint = performance.getEntriesByType('paint');
            
            const metrics = {
                domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
                loadComplete: navigation.loadEventEnd - navigation.loadEventStart,
                firstPaint: paint.find(entry => entry.name === 'first-paint')?.startTime || 0,
                firstContentfulPaint: paint.find(entry => entry.name === 'first-contentful-paint')?.startTime || 0,
            };

            // Send metrics to analytics if needed
            console.log('Performance Metrics:', metrics);
            return metrics;
        }
    }
}

// Initialize performance optimizer
document.addEventListener('DOMContentLoaded', () => {
    window.performanceOptimizer = new PerformanceOptimizer();
});

// Export for module usage
export default PerformanceOptimizer;
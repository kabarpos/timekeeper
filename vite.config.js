import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        // Optimasi untuk production
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Hapus console.log di production
                drop_debugger: true,
            },
        },
        rollupOptions: {
            output: {
                // Chunk splitting untuk better caching
                manualChunks: {
                    vendor: ['axios'],
                    livewire: ['laravel-echo', 'pusher-js'],
                },
                // Asset naming untuk cache busting
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(ext)) {
                        return `assets/images/[name]-[hash][extname]`;
                    }
                    if (/woff2?|eot|ttf|otf/i.test(ext)) {
                        return `assets/fonts/[name]-[hash][extname]`;
                    }
                    return `assets/[name]-[hash][extname]`;
                },
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
            },
        },
        // Optimasi chunk size
        chunkSizeWarningLimit: 1000,
        // Source maps untuk debugging (disable di production)
        sourcemap: process.env.NODE_ENV !== 'production',
    },
    server: {
        // HMR optimization
        hmr: {
            overlay: false,
        },
        // Preload modules
        warmup: {
            clientFiles: ['./resources/js/app.js', './resources/css/app.css'],
        },
    },
    // CSS optimization
    css: {
        devSourcemap: true,
        preprocessorOptions: {
            css: {
                charset: false,
            },
        },
    },
    // Dependency optimization
    optimizeDeps: {
        include: ['axios', 'laravel-echo', 'pusher-js'],
        exclude: ['@vite/client', '@vite/env'],
    },
});

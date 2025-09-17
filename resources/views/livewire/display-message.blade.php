<div 
    class="min-h-screen flex items-center justify-center p-8 font-manrope"
    style="background-color: {{ $background_color }}; color: {{ $font_color }};"
    wire:poll.5s="refreshDisplay">
    
    @if($has_active_message && $message)
        <div class="text-center w-full max-w-6xl">
            <!-- Message Title -->
            @if($message->title)
                <div class="text-4xl md:text-6xl lg:text-7xl font-manrope font-bold mb-8 leading-tight">
                    {{ $message->title }}
                </div>
            @endif
            
            <!-- Message Content -->
            <div class="@if($message->type === 'short') text-3xl md:text-5xl lg:text-6xl @else text-2xl md:text-3xl lg:text-4xl @endif font-manrope font-semibold leading-relaxed">
                {!! nl2br(e($message->content)) !!}
            </div>
            
            <!-- Message Type Indicator -->
            <div class="mt-8 opacity-60">
                <div class="text-lg md:text-xl font-manrope">
                    {{ $message->type === 'short' ? 'Pesan Singkat' : 'Pesan Panjang' }}
                </div>
            </div>
        </div>
    @else
        <!-- No Active Message -->
        <div class="text-center w-full max-w-4xl">
            <div class="text-4xl md:text-6xl font-manrope font-bold mb-8 opacity-60">
                Tidak Ada Pesan Aktif
            </div>
            
            <div class="text-xl md:text-2xl font-manrope opacity-40">
                Silakan aktifkan pesan dari panel admin
            </div>
            
            <!-- Animated Dots -->
            <div class="mt-8">
                <div class="flex justify-center space-x-2">
                    <div class="w-3 h-3 bg-current rounded-full animate-pulse opacity-40"></div>
                    <div class="w-3 h-3 bg-current rounded-full animate-pulse opacity-40" style="animation-delay: 0.2s"></div>
                    <div class="w-3 h-3 bg-current rounded-full animate-pulse opacity-40" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Keyboard Shortcuts Info -->
    <div class="fixed bottom-4 right-4 text-sm opacity-40 hover:opacity-80 transition-opacity">
        <div class="bg-black bg-opacity-50 rounded-lg p-3">
            <div class="text-xs">
                Tekan F11 untuk fullscreen
            </div>
        </div>
    </div>
    
    <!-- Message Info (Bottom Left) -->
    @if($has_active_message && $message)
        <div class="fixed bottom-4 left-4 text-sm opacity-40 hover:opacity-80 transition-opacity">
            <div class="bg-black bg-opacity-50 rounded-lg p-3">
                <div class="text-xs">
                    ID: {{ $message->id }} | 
                    Dibuat: {{ $message->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    @endif
    
    <!-- Auto-refresh and keyboard shortcuts script -->
    <script>
        // Auto refresh untuk sinkronisasi pesan
        document.addEventListener('livewire:init', () => {
            setInterval(() => {
                @this.call('refreshDisplay');
            }, 5000); // Refresh setiap 5 detik
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // F11 untuk fullscreen
            if (e.key === 'F11') {
                e.preventDefault();
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            }
        });
        
        // Auto-adjust font size based on content length
        document.addEventListener('DOMContentLoaded', function() {
            const messageContent = document.querySelector('.message-content');
            if (messageContent) {
                const contentLength = messageContent.textContent.length;
                if (contentLength > 200) {
                    messageContent.classList.add('text-xl', 'md:text-2xl', 'lg:text-3xl');
                    messageContent.classList.remove('text-2xl', 'md:text-3xl', 'lg:text-4xl');
                }
            }
        });
    </script>

    <style>
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .message-content {
            animation: fadeIn 0.8s ease-out;
        }
        
        /* Responsive text scaling */
        @media (max-width: 640px) {
            .text-responsive {
                font-size: clamp(1.5rem, 8vw, 4rem);
            }
        }
    </style>
</div>

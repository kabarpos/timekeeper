<div 
    class="min-h-screen flex items-center justify-center font-manrope"
    style="background-color: {{ $background_color }}; color: {{ $font_color }};"
    @if($message && $message->is_active) 
        wire:poll.30s="refreshDisplay"
    @endif
>
    @if($message && $message->is_active)
        <div class="text-center max-w-6xl mx-auto px-8">
            {{-- Judul dengan ukuran responsif yang lebih besar untuk desktop XL+ --}}
            <h1 class="font-bold mb-8 animate-pulse leading-tight
                text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl">
                {{ $message->title }}
            </h1>
            
            {{-- Konten pesan dengan ukuran optimal untuk semua layar --}}
            <div class="text-lg sm:text-xl md:text-3xl lg:text-5xl xl:text-6xl
                leading-relaxed mb-8 max-w-5xl mx-auto">
                {!! nl2br(e($message->content)) !!}
            </div>
        </div>
    @else
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-bold mb-4">
                Tidak Ada Pesan Aktif
            </h1>
            <p class="text-xl sm:text-2xl md:text-3xl lg:text-4xl opacity-75">
                Menunggu pesan dari admin...
            </p>
        </div>
    @endif
</div>

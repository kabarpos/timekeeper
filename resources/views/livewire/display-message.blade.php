<div 
    class="min-h-screen flex items-center justify-center font-manrope"
    style="background-color: {{ $background_color }}; color: {{ $font_color }};"
    @if($message && $message->is_active) 
        wire:poll.30s="refreshDisplay"
    @endif
>
    @if($message && $message->is_active)
        <div class="text-center max-w-4xl mx-auto px-8">
            <h1 class="text-6xl font-bold mb-8 animate-pulse">
                {{ $message->title }}
            </h1>
            
            <div class="text-2xl leading-relaxed mb-8">
                {!! nl2br(e($message->content)) !!}
            </div>
            
            @if($message->type)
                <div class="inline-flex items-center px-6 py-3 rounded-full text-lg font-semibold
                    @if($message->type === 'info') bg-blue-600 text-blue-100
                    @elseif($message->type === 'warning') bg-yellow-600 text-yellow-100
                    @elseif($message->type === 'success') bg-green-600 text-green-100
                    @elseif($message->type === 'error') bg-red-600 text-red-100
                    @else bg-gray-600 text-gray-100
                    @endif">
                    {{ ucfirst($message->type) }}
                </div>
            @endif
        </div>
    @else
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Tidak Ada Pesan Aktif</h1>
            <p class="text-xl opacity-75">Menunggu pesan dari admin...</p>
        </div>
    @endif
</div>

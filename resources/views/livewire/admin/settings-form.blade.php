<div class="space-y-8">
    <!-- Neumorphic Message Settings with Status Display -->
    <div class="bg-white p-8 rounded-lg border border-gray-200 gap-12">
        
        <!-- Mode Tampilan -->

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Mode Tampilan</h3>
                        <p class="text-sm text-gray-600">Pilih mode tampilan yang sedang aktif</p>
                    </div>
                    <div class="px-3 py-1 bg-green-100 text-green-800 rounded-md text-sm font-medium">
                        Mode: {{ ucfirst($display_mode) }}
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button 
                        wire:click="switchToTimer" 
                        class="px-4 py-2 rounded-md {{ $display_mode === 'timer' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors duration-200 text-sm font-medium">
                        Timer
                    </button>
                    <button 
                        wire:click="switchToMessage" 
                        class="px-4 py-2 rounded-md {{ $display_mode === 'message' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors duration-200 text-sm font-medium">
                        Message
                    </button>
                </div>

        

        
        <!-- Recent Messages List -->

                <div class="flex items-center justify-between mt-8 mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Pesan Terbaru</h4>
                    <a href="/admin/messages" class="text-sm font-medium text-blue-600 hover:text-blue-700">Kelola Semua Pesan →</a>
                </div>
                
                @if($recent_messages && count($recent_messages) > 0)
                    <div class="space-y-3">
                        @foreach($recent_messages as $message)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="px-2 py-1 text-xs font-medium rounded {{ $message->type === 'short' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ ucfirst($message->type) }}</span>
                                            @if($message->is_active)
                                                <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-700">Aktif</span>
                                            @endif
                                        </div>
                                        <div class="text-sm font-medium text-gray-800 mb-1">{{ $message->title ?: 'Tanpa Judul' }}</div>
                                        <div class="text-xs text-gray-600">{{ Str::limit($message->content, 100) }}</div>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        <div class="w-4 h-4 rounded-full border border-gray-300" style="background-color: {{ $message->bg_color }}"></div>
                                        <div class="w-4 h-4 rounded-full border border-gray-300" style="background-color: {{ $message->font_color }}"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 bg-gray-50 rounded-lg text-center">
                        <div class="text-gray-400 mb-2">
                            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="text-sm font-medium text-gray-600">Belum ada pesan tersimpan</div>
                        <div class="text-xs text-gray-500 mt-1">Buat pesan pertama Anda di halaman Message Management</div>
                    </div>
                @endif
 
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-green-500 text-white rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-green-800 font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-red-500 text-white rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>

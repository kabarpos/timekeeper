<div class="space-y-8">
    <!-- Neumorphic Message Settings with Status Display -->
    <div class="p-8 rounded-3xl bg-gradient-to-br from-slate-50/80 to-white/80 backdrop-blur-sm shadow-[inset_20px_20px_40px_#d1d5db,inset_-20px_-20px_40px_#ffffff] border border-white/50">
        
        <!-- Current Display Status -->
        <div class="mb-8 p-6 rounded-2xl bg-gradient-to-br from-white/60 to-slate-50/60 backdrop-blur-sm shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] border border-white/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                   
                    <div>
                        <div class="text-lg font-bold text-slate-700">Status Display Saat Ini</div>
                        <div class="text-sm text-slate-500">Mode: <span class="font-semibold text-{{ $display_mode === 'timer' ? 'blue' : 'purple' }}-600">{{ ucfirst($display_mode) }}</span></div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button 
                        wire:click="switchToTimer" 
                        class="px-4 py-2 rounded-xl {{ $display_mode === 'timer' ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-[4px_4px_8px_#3b82f6/30]' : 'bg-gradient-to-br from-white/80 to-slate-50/80 text-slate-600 shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff]' }} border border-white/30 transition-all duration-300 transform hover:scale-105 active:scale-95 text-sm font-semibold">
                        Timer
                    </button>
                    <button 
                        wire:click="switchToMessage" 
                        class="px-4 py-2 rounded-xl {{ $display_mode === 'message' ? 'bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-[4px_4px_8px_#a855f7/30]' : 'bg-gradient-to-br from-white/80 to-slate-50/80 text-slate-600 shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff]' }} border border-white/30 transition-all duration-300 transform hover:scale-105 active:scale-95 text-sm font-semibold">
                        Message
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Quick Settings Access -->
        <div class="mb-6">
            <h4 class="text-lg font-bold text-slate-700 mb-4">Pengaturan Lanjutan</h4>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <a href="{{ route('admin.color-settings') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-[12px_12px_24px_#a855f7/30] hover:shadow-[16px_16px_32px_#a855f7/40] transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center space-x-3">
                        <div class="text-2xl">🎨</div>
                        <div>
                            <div class="font-bold text-sm">Pengaturan Warna</div>
                            <div class="text-xs opacity-90">Atur warna background & font</div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('admin.timer-settings') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-[12px_12px_24px_#3b82f6/30] hover:shadow-[16px_16px_32px_#3b82f6/40] transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center space-x-3">
                        <div class="text-2xl">⏱️</div>
                        <div>
                            <div class="font-bold text-sm">Pengaturan Timer</div>
                            <div class="text-xs opacity-90">Atur durasi & tampilan timer</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Recent Messages List -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-bold text-slate-700">Pesan Terbaru</h4>
                <a href="/admin/messages" class="text-sm font-semibold text-purple-600 hover:text-purple-700 transition-colors duration-200">Kelola Semua Pesan →</a>
            </div>
            
            @if($recent_messages && count($recent_messages) > 0)
                <div class="space-y-3">
                    @foreach($recent_messages as $message)
                        <div class="p-4 rounded-xl bg-gradient-to-br from-white/60 to-slate-50/60 backdrop-blur-sm shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] border border-white/40 transition-all duration-300 hover:shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff]">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-lg {{ $message->type === 'short' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ ucfirst($message->type) }}</span>
                                        @if($message->is_active)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700">Aktif</span>
                                        @endif
                                    </div>
                                    <div class="text-sm font-semibold text-slate-700 mb-1">{{ $message->title ?: 'Tanpa Judul' }}</div>
                                    <div class="text-xs text-slate-500 line-clamp-2">{{ Str::limit($message->content, 100) }}</div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <div class="w-4 h-4 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $message->bg_color }}"></div>
                                    <div class="w-4 h-4 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $message->font_color }}"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 rounded-xl bg-gradient-to-br from-slate-100/60 to-slate-50/60 backdrop-blur-sm shadow-[inset_6px_6px_12px_#bebebe,inset_-6px_-6px_12px_#ffffff] border border-white/40 text-center">
                    <div class="text-slate-500 mb-2">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div class="text-sm font-medium text-slate-600">Belum ada pesan tersimpan</div>
                    <div class="text-xs text-slate-500 mt-1">Buat pesan pertama Anda di halaman Message Management</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Neumorphic Success/Error Messages -->
    @if (session()->has('message'))
        <div class="p-6 rounded-2xl bg-gradient-to-br from-green-50/80 to-emerald-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#10b981/20,-12px_-12px_24px_#ffffff] border border-green-200/50 transition-all duration-500 transform animate-pulse">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-[6px_6px_12px_#10b981/30]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-green-800 font-bold text-lg">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-6 rounded-2xl bg-gradient-to-br from-red-50/80 to-rose-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#ef4444/20,-12px_-12px_24px_#ffffff] border border-red-200/50 transition-all duration-500 transform animate-pulse">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-[6px_6px_12px_#ef4444/30]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-red-800 font-bold text-lg">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>

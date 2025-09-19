<div class="space-y-8">
    <!-- Display Mode Selection -->
    <div>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-cog text-white"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Display Mode</h3>
                <p class="text-sm text-gray-600">Konfigurasi pengaturan tampilan</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <label class="block text-sm font-semibold text-gray-800 mb-3">Mode Tampilan</label>
            <p class="text-xs text-gray-500 mb-4">Pilih mode tampilan yang sedang aktif</p>
            
            <div class="grid grid-cols-2 gap-3 mb-4">
                <button 
                    wire:click="switchToTimer"
                    class="group relative overflow-hidden rounded-xl border-2 transition-all duration-300 {{ $display_mode === 'timer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-blue-300 hover:bg-blue-50/50' }}"
                >
                    <div class="p-4 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-xl flex items-center justify-center {{ $display_mode === 'timer' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-blue-100 group-hover:text-blue-600' }} transition-all duration-300">
                            <i class="fas fa-clock text-lg"></i>
                        </div>
                        <span class="block font-semibold {{ $display_mode === 'timer' ? 'text-blue-700' : 'text-gray-700 group-hover:text-blue-700' }} transition-colors">Timer</span>
                        <span class="text-xs {{ $display_mode === 'timer' ? 'text-blue-600' : 'text-gray-500' }}">Mode Timer Aktif</span>
                    </div>
                    @if($display_mode === 'timer')
                        <div class="absolute top-2 right-2 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    @endif
                </button>
                
                <button 
                    wire:click="switchToMessage"
                    class="group relative overflow-hidden rounded-xl border-2 transition-all duration-300 {{ $display_mode === 'message' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 bg-white hover:border-purple-300 hover:bg-purple-50/50' }}"
                >
                    <div class="p-4 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-xl flex items-center justify-center {{ $display_mode === 'message' ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-purple-100 group-hover:text-purple-600' }} transition-all duration-300">
                            <i class="fas fa-comment text-lg"></i>
                        </div>
                        <span class="block font-semibold {{ $display_mode === 'message' ? 'text-purple-700' : 'text-gray-700 group-hover:text-purple-700' }} transition-colors">Message</span>
                        <span class="text-xs {{ $display_mode === 'message' ? 'text-purple-600' : 'text-gray-500' }}">Mode Pesan Aktif</span>
                    </div>
                    @if($display_mode === 'message')
                        <div class="absolute top-2 right-2 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    @endif
                </button>
            </div>
            
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full {{ $display_mode === 'timer' ? 'bg-blue-500' : 'bg-purple-500' }} animate-pulse"></div>
                    <span class="text-sm text-gray-600">Status: </span>
                    <span class="font-semibold text-gray-800 capitalize">Mode {{ ucfirst($display_mode) }} Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Pesan Terbaru</h3>
                    <p class="text-sm text-gray-600">Daftar pesan yang baru dibuat</p>
                </div>
            </div>
            <a href="/admin/messages" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-medium">
                <span>Kelola Semua</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        
        @if($recent_messages && count($recent_messages) > 0)
            <div class="space-y-4">
                @foreach($recent_messages as $message)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg hover:border-gray-300 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $message->bg_color }}"></div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $message->type === 'short' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                        <i class="fas {{ $message->type === 'short' ? 'fa-bolt' : 'fa-clock' }} mr-1"></i>
                                        {{ ucfirst($message->type) }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $message->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <div class="w-2 h-2 rounded-full {{ $message->is_active ? 'bg-green-500' : 'bg-gray-500' }} mr-2"></div>
                                        {{ $message->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $message->title ?: 'Tanpa Judul' }}</h4>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($message->content, 120) }}</p>
                            </div>
                            <button 
                                wire:click="toggleMessageStatus({{ $message->id }})"
                                class="ml-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 {{ $message->is_active ? 'bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 hover:border-red-300' : 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 hover:border-green-300' }}"
                            >
                                <i class="fas {{ $message->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                {{ $message->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-dashed border-gray-300">
                <div class="w-16 h-16 bg-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-comment-slash text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Pesan</h4>
                <p class="text-gray-500 text-sm mb-4">Mulai dengan membuat pesan pertama Anda</p>
                <a href="/admin/messages" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-plus"></i>
                    Buat Pesan Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <span class="text-green-800 font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>

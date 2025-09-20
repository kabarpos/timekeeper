<div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-comment-alt text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Pesan Aktif</h3>
                    <p class="text-sm text-gray-600">Status pesan yang sedang ditampilkan</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.messages') }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-cog text-xs"></i>
                    Kelola
                </a>
                <a href="{{ route('display.message') }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-external-link-alt text-xs"></i>
                    Lihat
                </a>
            </div>
        </div>

        @if($activeMessage)
            <div class="space-y-4">
                <!-- Status Badge -->
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        Aktif
                    </span>
                    <span class="text-sm text-gray-500">
                        Dibuat {{ $activeMessage->created_at->diffForHumans() }}
                    </span>
                </div>

                <!-- Message Preview -->
                <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100">
                    <h4 class="font-semibold text-gray-900 mb-2 text-lg">
                        {{ $activeMessage->title }}
                    </h4>
                    <div class="text-gray-700 text-sm leading-relaxed">
                        {{ Str::limit($activeMessage->content, 150) }}
                    </div>
                </div>

                <!-- Display Settings Preview -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50/50 rounded-lg p-3 border border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-4 h-4 rounded" style="background-color: {{ $backgroundColor }}"></div>
                            <span class="text-xs font-medium text-gray-600">Background</span>
                        </div>
                        <span class="text-xs text-gray-500 font-mono">{{ $backgroundColor }}</span>
                    </div>
                    <div class="bg-gray-50/50 rounded-lg p-3 border border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-4 h-4 rounded border" style="background-color: {{ $fontColor }}"></div>
                            <span class="text-xs font-medium text-gray-600">Text Color</span>
                        </div>
                        <span class="text-xs text-gray-500 font-mono">{{ $fontColor }}</span>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex gap-2 pt-2">
                    <button 
                        wire:click="deactivateMessage"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-stop text-xs"></i>
                        Nonaktifkan
                    </button>
                    <button 
                        wire:click="refreshMessage"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-sync-alt text-xs"></i>
                    </button>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-comment-slash text-gray-400 text-xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Pesan Aktif</h4>
                <p class="text-gray-600 mb-4">Belum ada pesan yang sedang ditampilkan</p>
                <a href="{{ route('admin.messages') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-plus text-xs"></i>
                    Buat Pesan Baru
                </a>
            </div>
        @endif
    </div>
</div>
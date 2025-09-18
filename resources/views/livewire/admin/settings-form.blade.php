<div class="space-y-6">
    <!-- Mode Tampilan Card -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Mode Tampilan</h3>
                    <p class="mt-1 text-sm text-gray-500">Pilih mode tampilan yang sedang aktif</p>
                </div>
                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="size-1.5 inline-block rounded-full bg-green-800"></span>
                    Mode: {{ ucfirst($display_mode) }}
                </span>
            </div>
            
            <div class="flex gap-3">
                <button 
                    wire:click="switchToTimer" 
                    type="button"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border {{ $display_mode === 'timer' ? 'border-blue-600 bg-blue-600 text-white shadow-sm' : 'border-gray-200 bg-white text-gray-800 hover:bg-gray-50' }} focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    <i class="fas fa-clock flex-shrink-0 size-4"></i>
                    Timer
                </button>
                <button 
                    wire:click="switchToMessage" 
                    type="button"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border {{ $display_mode === 'message' ? 'border-purple-600 bg-purple-600 text-white shadow-sm' : 'border-gray-200 bg-white text-gray-800 hover:bg-gray-50' }} focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    <i class="fas fa-comments flex-shrink-0 size-4"></i>
                    Message
                </button>
            </div>
        </div>
    </div>

        

    <!-- Recent Messages Card -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Pesan Terbaru</h3>
                    <p class="mt-1 text-sm text-gray-500">Daftar pesan yang baru dibuat</p>
                </div>
                <a href="/admin/messages" class="inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                    Kelola Semua
                    <i class="fas fa-chevron-right flex-shrink-0 size-4"></i>
                </a>
            </div>
            
            @if($recent_messages && count($recent_messages) > 0)
                <div class="space-y-3">
                    @foreach($recent_messages as $message)
                        <div class="flex items-center gap-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="flex items-center gap-x-2">
                                    <div class="size-3 rounded-full border border-gray-300" style="background-color: {{ $message->bg_color }}"></div>
                                    <div class="size-3 rounded-full border border-gray-300" style="background-color: {{ $message->font_color }}"></div>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-x-2 mb-1">
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $message->type === 'short' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        <span class="size-1.5 inline-block rounded-full {{ $message->type === 'short' ? 'bg-blue-800' : 'bg-green-800' }}"></span>
                                        {{ ucfirst($message->type) }}
                                    </span>
                                    @if($message->is_active)
                                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="size-1.5 inline-block rounded-full bg-green-800"></span>
                                            Aktif
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm font-semibold text-gray-900">{{ $message->title ?: 'Tanpa Judul' }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ Str::limit($message->content, 80) }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <button 
                                    wire:click="toggleMessageStatus({{ $message->id }})"
                                    class="inline-flex items-center gap-x-1 py-1.5 px-3 text-xs font-medium rounded-lg border {{ $message->is_active ? 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100' : 'border-green-200 bg-green-50 text-green-700 hover:bg-green-100' }} focus:outline-none transition-colors">
                                    @if($message->is_active)
                                        <i class="fas fa-eye-slash flex-shrink-0 size-3"></i>
                                        Nonaktifkan
                                    @else
                                        <i class="fas fa-eye flex-shrink-0 size-3"></i>
                                        Aktifkan
                                    @endif
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <i class="fas fa-comments mx-auto size-12 text-gray-400 mb-4"></i>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum ada pesan</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat pesan pertama Anda.</p>
                    <div class="mt-6">
                        <a href="/admin/messages" class="inline-flex items-center gap-x-2 py-2 px-3 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            <i class="fas fa-plus flex-shrink-0 size-4"></i>
                            Buat Pesan Baru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-teal-50 border border-teal-200 rounded-xl p-4" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-success-label">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800">
                        <i class="fas fa-check flex-shrink-0 size-4"></i>
                    </span>
                </div>
                <div class="ms-3">
                    <h3 id="hs-soft-color-success-label" class="text-gray-800 font-semibold">
                        Berhasil!
                    </h3>
                    <p class="text-sm text-gray-700">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-danger-label">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-red-100 bg-red-200 text-red-800">
                        <i class="fas fa-exclamation-triangle flex-shrink-0 size-4"></i>
                    </span>
                </div>
                <div class="ms-3">
                    <h3 id="hs-soft-color-danger-label" class="text-gray-800 font-semibold">
                        Error!
                    </h3>
                    <p class="text-sm text-gray-700">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="space-y-6">
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Warna Background Message -->
            <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-700 mb-3">Warna Background Message</label>
                <div class="flex gap-3 items-center">
                    <input 
                        type="color" 
                        wire:model.live="bg_color" 
                        class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer">
                    <input 
                        type="text" 
                        wire:model.live="bg_color" 
                        placeholder="#000000" 
                        class="flex-1 px-3 py-2 rounded-lg bg-white border border-gray-300 focus:border-blue-500 focus:outline-none transition-colors font-mono text-sm text-slate-700">
                </div>
                @error('bg_color') 
                    <div class="text-red-500 text-sm font-medium mt-2 p-2 rounded-lg bg-red-50/50">{{ $message }}</div> 
                @enderror
            </div>
            
            <!-- Warna Font Message -->
            <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-700 mb-3">Warna Font Message</label>
                <div class="flex gap-3 items-center">
                    <input 
                        type="color" 
                        wire:model.live="font_color" 
                        class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer">
                    <input 
                        type="text" 
                        wire:model.live="font_color" 
                        placeholder="#ffffff" 
                        class="flex-1 px-3 py-2 rounded-lg bg-white border border-gray-300 focus:border-blue-500 focus:outline-none transition-colors font-mono text-sm text-slate-700">
                </div>
                @error('font_color') 
                    <div class="text-red-500 text-sm font-medium mt-2 p-2 rounded-lg bg-red-50/50">{{ $message }}</div> 
                @enderror
            </div>
        </div>
        
        <!-- Preview Warna Message -->
        <div class="mb-8">
            <label class="block text-sm font-semibold text-gray-900 mb-4">Preview Warna Message</label>
            <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="text-center p-8 rounded-lg transition-all duration-500" style="{{ $this->preview_style }}">
                    <div class="text-4xl font-black mb-4 tracking-wider drop-shadow-lg">Contoh Judul Message</div>
                    <div class="text-lg font-semibold opacity-80">Ini adalah contoh tampilan pesan yang akan ditampilkan pada layar display</div>
                </div>
            </div>
        </div>
        
        <!-- Tombol Aksi Warna Message -->
        <div class="flex gap-4">
            <button 
                type="submit" 
                class="flex-1 py-3 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                <div class="flex items-center justify-center space-x-3">
                    <div class="flex-shrink-0 size-4">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Simpan Pengaturan Warna</span>
                </div>
            </button>
            
            <button 
                type="button" 
                wire:click="resetToDefault" 
                class="flex-1 py-3 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                <div class="flex items-center justify-center space-x-3">
                    <div class="flex-shrink-0 size-4">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Reset ke Default</span>
                </div>
            </button>
        </div>
    </form>
    
    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="fixed top-4 end-4 z-50 max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg" role="alert">
            <div class="flex p-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                </div>
                <div class="ms-3">
                    <p class="text-sm text-gray-700">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 end-4 z-50 max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg" role="alert">
            <div class="flex p-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                </div>
                <div class="ms-3">
                    <p class="text-sm text-gray-700">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
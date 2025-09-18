<div class="space-y-4">
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <!-- Warna Background Message -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Warna Background</label>
                <div class="flex gap-2 items-center">
                    <input 
                        type="color" 
                        wire:model.live="bg_color" 
                        class="w-8 h-8 rounded border border-gray-300 cursor-pointer">
                    <input 
                        type="text" 
                        wire:model.live="bg_color" 
                        placeholder="#000000" 
                        class="flex-1 px-2 py-1.5 text-sm rounded border border-gray-300 focus:border-blue-500 focus:outline-none font-mono">
                </div>
                @error('bg_color') 
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                @enderror
            </div>
            
            <!-- Warna Font Message -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Warna Font</label>
                <div class="flex gap-2 items-center">
                    <input 
                        type="color" 
                        wire:model.live="font_color" 
                        class="w-8 h-8 rounded border border-gray-300 cursor-pointer">
                    <input 
                        type="text" 
                        wire:model.live="font_color" 
                        placeholder="#ffffff" 
                        class="flex-1 px-2 py-1.5 text-sm rounded border border-gray-300 focus:border-blue-500 focus:outline-none font-mono">
                </div>
                @error('font_color') 
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                @enderror
            </div>
        </div>
        
        <!-- Preview Warna Message -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                <div class="text-center p-4 rounded transition-all duration-300" style="{{ $this->preview_style }}">
                    <div class="text-lg font-bold mb-1">Contoh Judul</div>
                    <div class="text-sm opacity-90">Contoh pesan display</div>
                </div>
            </div>
        </div>
        
        <!-- Tombol Aksi -->
        <div class="flex gap-2">
            <button 
                type="submit" 
                class="flex-1 py-2 px-3 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                <i class="fas fa-save text-xs mr-1"></i>
                Simpan
            </button>
            
            <button 
                type="button" 
                wire:click="resetToDefault" 
                class="flex-1 py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 focus:outline-none focus:bg-gray-50">
                <i class="fas fa-undo text-xs mr-1"></i>
                Reset
            </button>
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
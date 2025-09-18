<div>
    <form wire:submit.prevent="saveSettings" class="space-y-4">
        <!-- Duration Presets -->
        <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Preset Durasi</label>
            <div class="grid grid-cols-2 gap-2">
                <button wire:click="setDuration(5)" type="button" 
                        class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 transition-colors">
                    5 Menit
                </button>
                <button wire:click="setDuration(10)" type="button" 
                        class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 transition-colors">
                    10 Menit
                </button>
                <button wire:click="setDuration(15)" type="button" 
                        class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 transition-colors">
                    15 Menit
                </button>
                <button wire:click="setDuration(30)" type="button" 
                        class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 transition-colors">
                    30 Menit
                </button>
            </div>
        </div>

        <!-- Custom Duration -->
        <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Durasi Custom</label>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <input wire:model="duration_minutes" type="number" min="0" max="59" placeholder="Menit"
                           class="py-2 px-3 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <input wire:model="duration_seconds" type="number" min="0" max="59" placeholder="Detik"
                           class="py-2 px-3 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            @error('duration_minutes') 
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
            @enderror
        </div>
        
        <!-- Color Settings -->
        <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Pengaturan Warna</label>
            <div class="space-y-2">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Background</label>
                    <div class="flex">
                        <input wire:model="background_color" type="color"
                               class="p-1 h-8 w-12 bg-white border border-gray-200 cursor-pointer rounded-s-lg">
                        <input wire:model="background_color" type="text" 
                               class="py-2 px-3 block w-full border-gray-200 rounded-e-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Font</label>
                    <div class="flex">
                        <input wire:model="font_color" type="color"
                               class="p-1 h-8 w-12 bg-white border border-gray-200 cursor-pointer rounded-s-lg">
                        <input wire:model="font_color" type="text" 
                               class="py-2 px-3 block w-full border-gray-200 rounded-e-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timer Preview -->
        <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Preview</label>
            <div class="bg-gray-50 border border-gray-300 rounded-lg p-4 text-center" 
                 style="background-color: {{ $background_color ?? '#f9fafb' }}; color: {{ $font_color ?? '#111827' }}">
                <div class="text-3xl font-mono font-bold mb-1">
                    {{ sprintf('%02d:%02d', $duration_minutes ?? 0, $duration_seconds ?? 0) }}
                </div>
                <div class="text-xs opacity-75">Preview Timer</div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button wire:click="saveSettings" type="button" 
                    class="flex-1 py-2 px-3 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                <i class="fas fa-save text-xs"></i>
                Simpan
            </button>
            
            <button wire:click="resetSettings" type="button" 
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 transition-colors">
                <i class="fas fa-undo text-xs"></i>
                Reset
            </button>
        </div>
    </form>
    
    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex items-center">
                <i class="fas fa-check text-green-600 text-sm mr-2"></i>
                <p class="text-sm text-green-800">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-sm mr-2"></i>
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif
</div>
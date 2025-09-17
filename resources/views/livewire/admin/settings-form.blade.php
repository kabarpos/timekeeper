<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Pengaturan Tampilan</h2>
        
        <!-- Settings Form -->
        <form wire:submit.prevent="save">
            <div class="mb-6">
                <!-- Color Settings -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Pengaturan Warna</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Warna Background</label>
                            <div class="flex gap-3 items-center">
                                <input 
                                    type="color" 
                                    wire:model="bg_color" 
                                    class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer">
                                <input 
                                    type="text" 
                                    wire:model="bg_color" 
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="#000000">
                            </div>
                            @error('bg_color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Warna Font</label>
                            <div class="flex gap-3 items-center">
                                <input 
                                    type="color" 
                                    wire:model="font_color" 
                                    class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer">
                                <input 
                                    type="text" 
                                    wire:model="font_color" 
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="#ffffff">
                            </div>
                            @error('font_color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Preview Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Preview Warna</h3>
                <div 
                    class="rounded-lg p-6 text-center border-2 border-dashed border-gray-300"
                    style="{{ $this->preview_style }}">
                    <div class="text-2xl font-bold mb-2">05:30</div>
                    <div class="text-sm opacity-75">Preview tampilan dengan warna yang dipilih</div>
                </div>
            </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                </button>
                
                <button 
                    type="button" 
                    wire:click="resetToDefault" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-undo mr-2"></i>Reset ke Default
                </button>
            </div>
        </form>
        
        <!-- Current Settings Info -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold text-gray-800 mb-2">Pengaturan Saat Ini:</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Background:</span>
                    <span class="font-mono ml-2">{{ $bg_color }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Font:</span>
                    <span class="font-mono ml-2">{{ $font_color }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Mode:</span>
                    <span class="font-semibold ml-2 capitalize">{{ $display_mode }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif
</div>

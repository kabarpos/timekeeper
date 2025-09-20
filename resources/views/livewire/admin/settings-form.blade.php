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

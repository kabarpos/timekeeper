<!-- Timer Settings Card -->
<div class="p-6 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <div class="p-3 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-[8px_8px_16px_#6366f1/30] mr-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold bg-gradient-to-r from-slate-700 to-indigo-600 bg-clip-text text-transparent">
            Pengaturan Timer
        </h3>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Pengaturan Durasi Timer -->
        <div class="mb-8">
            <label class="block text-sm font-bold text-slate-700 mb-4">Durasi Timer</label>
            
            <!-- Preset Durasi -->
            <div class="mb-6">
                <div class="text-xs text-slate-600 mb-3 font-semibold">Pilih Durasi Cepat:</div>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" wire:click="$set('duration_minutes', 1)" class="p-2 text-xs rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 shadow-[4px_4px_8px_#bebebe,-4px_-4px_8px_#ffffff] hover:shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] transition-all duration-200 {{ $duration_minutes == 1 ? 'ring-2 ring-blue-500' : '' }}">1 min</button>
                    <button type="button" wire:click="$set('duration_minutes', 5)" class="p-2 text-xs rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 shadow-[4px_4px_8px_#bebebe,-4px_-4px_8px_#ffffff] hover:shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] transition-all duration-200 {{ $duration_minutes == 5 ? 'ring-2 ring-blue-500' : '' }}">5 min</button>
                    <button type="button" wire:click="$set('duration_minutes', 10)" class="p-2 text-xs rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 shadow-[4px_4px_8px_#bebebe,-4px_-4px_8px_#ffffff] hover:shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] transition-all duration-200 {{ $duration_minutes == 10 ? 'ring-2 ring-blue-500' : '' }}">10 min</button>
                    <button type="button" wire:click="$set('duration_minutes', 15)" class="p-2 text-xs rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 shadow-[4px_4px_8px_#bebebe,-4px_-4px_8px_#ffffff] hover:shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] transition-all duration-200 {{ $duration_minutes == 15 ? 'ring-2 ring-blue-500' : '' }}">15 min</button>
                    <button type="button" wire:click="$set('duration_minutes', 30)" class="p-2 text-xs rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 shadow-[4px_4px_8px_#bebebe,-4px_-4px_8px_#ffffff] hover:shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] transition-all duration-200 {{ $duration_minutes == 30 ? 'ring-2 ring-blue-500' : '' }}">30 min</button>
                    <button type="button" wire:click="$set('duration_minutes', 60)" class="p-2 text-xs rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 shadow-[4px_4px_8px_#bebebe,-4px_-4px_8px_#ffffff] hover:shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] transition-all duration-200 {{ $duration_minutes == 60 ? 'ring-2 ring-blue-500' : '' }}">1 jam</button>
                </div>
            </div>
            
            <!-- Input Manual -->
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="text-xs text-slate-600 mb-2 font-semibold">Durasi Manual:</div>
                    <input 
                        type="number" 
                        wire:model.live="duration_minutes" 
                        min="1" 
                        max="180" 
                        class="w-24 px-3 py-2 rounded-xl bg-white/50 border-0 shadow-[inset_6px_6px_12px_#bebebe,inset_-6px_-6px_12px_#ffffff] focus:shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] focus:outline-none transition-all duration-300 font-mono text-slate-700 text-center text-sm font-bold">
                </div>
                <div class="flex-1">
                    <div class="text-sm text-slate-600 mb-2">Durasi: <span class="font-bold text-blue-600">{{ $duration_minutes }} menit</span></div>
                    <div class="w-full bg-gradient-to-r from-slate-200 to-slate-300 rounded-full h-2 shadow-[inset_3px_3px_6px_#bebebe,inset_-3px_-3px_6px_#ffffff]">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full shadow-[1px_1px_2px_#3b82f6/30] transition-all duration-300" style="width: {{ min(($duration_minutes / 60) * 100, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 mt-1">
                        <span>1 min</span>
                        <span>60 min</span>
                        <span>180 min</span>
                    </div>
                </div>
            </div>
            @error('duration_minutes') 
                <div class="text-red-500 text-sm font-medium mt-2 p-2 rounded-lg bg-red-50/50">{{ $message }}</div> 
            @enderror
        </div>
        
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Warna Background Timer -->
            <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-700 mb-3">Warna Background Timer</label>
                <div class="flex gap-3 items-center">
                    <input 
                        type="color" 
                        wire:model.live="timer_bg_color" 
                        class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer">
                    <input 
                        type="text" 
                        wire:model.live="timer_bg_color" 
                        placeholder="#000000" 
                        class="flex-1 px-3 py-2 rounded-lg bg-white border border-gray-300 focus:border-blue-500 focus:outline-none transition-colors font-mono text-sm text-slate-700">
                </div>
                @error('timer_bg_color') 
                    <div class="text-red-500 text-sm font-medium mt-2 p-2 rounded-lg bg-red-50/50">{{ $message }}</div> 
                @enderror
            </div>
            
            <!-- Warna Font Timer -->
            <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-700 mb-3">Warna Font Timer</label>
                <div class="flex gap-3 items-center">
                    <input 
                        type="color" 
                        wire:model.live="timer_font_color" 
                        class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer">
                    <input 
                        type="text" 
                        wire:model.live="timer_font_color" 
                        placeholder="#ffffff" 
                        class="flex-1 px-3 py-2 rounded-lg bg-white border border-gray-300 focus:border-blue-500 focus:outline-none transition-colors font-mono text-sm text-slate-700">
                </div>
                @error('timer_font_color') 
                    <div class="text-red-500 text-sm font-medium mt-2 p-2 rounded-lg bg-red-50/50">{{ $message }}</div> 
                @enderror
            </div>
        </div>
        
        <!-- Preview Timer -->
        <div class="mb-8">
            <label class="block text-sm font-bold text-slate-700 mb-4">Preview Timer</label>
            <div class="p-6 rounded-2xl shadow-[inset_12px_12px_24px_rgba(0,0,0,0.1),inset_-12px_-12px_24px_rgba(255,255,255,0.8)] border border-white/30">
                <div class="text-center p-8 rounded-2xl transition-all duration-500" style="{{ $this->preview_style }}">
                    <div class="text-6xl font-mono font-black mb-4 tracking-wider drop-shadow-lg">{{ str_pad(floor($duration_minutes / 60), 2, '0', STR_PAD_LEFT) }}:{{ str_pad($duration_minutes % 60, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-lg font-semibold opacity-80">Preview Tampilan Timer</div>
                </div>
            </div>
        </div>
        
        <!-- Tombol Aksi Timer -->
        <div class="flex gap-4">
            <button 
                type="submit" 
                class="group flex-1 p-4 rounded-2xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] hover:shadow-[16px_16px_32px_#bebebe,-16px_-16px_32px_#ffffff] border border-white/30 transition-all duration-300 transform hover:scale-105 active:scale-95">
                <div class="flex items-center justify-center space-x-3">
                    <div class="p-2 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-[4px_4px_8px_#3b82f6/30] group-hover:shadow-[6px_6px_12px_#3b82f6/40] transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-700">Simpan Pengaturan</span>
                </div>
            </button>
            
            <button 
                type="button" 
                wire:click="resetToDefault" 
                class="group flex-1 p-4 rounded-2xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] hover:shadow-[16px_16px_32px_#bebebe,-16px_-16px_32px_#ffffff] border border-white/30 transition-all duration-300 transform hover:scale-105 active:scale-95">
                <div class="flex items-center justify-center space-x-3">
                    <div class="p-2 rounded-xl bg-gradient-to-r from-gray-500 to-slate-600 text-white shadow-[4px_4px_8px_#6b7280/30] group-hover:shadow-[6px_6px_12px_#6b7280/40] transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-700">Reset ke Default</span>
                </div>
            </button>
        </div>
    </form>
    
    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mt-4 p-4 rounded-lg bg-green-50 border border-green-200">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-lg bg-green-500 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-green-800 font-medium text-sm">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 p-4 rounded-lg bg-red-50 border border-red-200">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-lg bg-red-500 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-red-800 font-medium text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
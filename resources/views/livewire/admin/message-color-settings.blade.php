<div class="space-y-6">
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Warna Background Message -->
            <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-700 mb-3">Warna Background Message</label>
                <div class="flex gap-4">
                    <div class="relative">
                        <input 
                            type="color" 
                            wire:model.live="bg_color" 
                            class="w-16 h-16 rounded-2xl border-0 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] cursor-pointer transition-all duration-300 hover:shadow-[inset_12px_12px_24px_#bebebe,inset_-12px_-12px_24px_#ffffff]">
                        <div class="absolute inset-0 rounded-2xl border border-white/50 pointer-events-none"></div>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live="bg_color" 
                        placeholder="#000000" 
                        class="flex-1 px-4 py-3 rounded-2xl bg-white/50 border-0 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] focus:shadow-[inset_12px_12px_24px_#bebebe,inset_-12px_-12px_24px_#ffffff] focus:outline-none transition-all duration-300 font-mono text-slate-700">
                </div>
                @error('bg_color') 
                    <div class="text-red-500 text-sm font-medium mt-2 p-2 rounded-lg bg-red-50/50">{{ $message }}</div> 
                @enderror
            </div>
            
            <!-- Warna Font Message -->
            <div class="space-y-4">
                <label class="block text-sm font-bold text-slate-700 mb-3">Warna Font Message</label>
                <div class="flex gap-4">
                    <div class="relative">
                        <input 
                            type="color" 
                            wire:model.live="font_color" 
                            class="w-16 h-16 rounded-2xl border-0 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] cursor-pointer transition-all duration-300 hover:shadow-[inset_12px_12px_24px_#bebebe,inset_-12px_-12px_24px_#ffffff]">
                        <div class="absolute inset-0 rounded-2xl border border-white/50 pointer-events-none"></div>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live="font_color" 
                        placeholder="#ffffff" 
                        class="flex-1 px-4 py-3 rounded-2xl bg-white/50 border-0 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] focus:shadow-[inset_12px_12px_24px_#bebebe,inset_-12px_-12px_24px_#ffffff] focus:outline-none transition-all duration-300 font-mono text-slate-700">
                </div>
                @error('font_color') 
                    <div class="text-red-500 text-sm font-medium mt-2 p-2 rounded-lg bg-red-50/50">{{ $message }}</div> 
                @enderror
            </div>
        </div>
        
        <!-- Preview Warna Message -->
        <div class="mb-8">
            <label class="block text-sm font-bold text-slate-700 mb-4">Preview Warna Message</label>
            <div class="p-6 rounded-2xl shadow-[inset_12px_12px_24px_rgba(0,0,0,0.1),inset_-12px_-12px_24px_rgba(255,255,255,0.8)] border border-white/30">
                <div class="text-center p-8 rounded-2xl transition-all duration-500" style="{{ $this->preview_style }}">
                    <div class="text-4xl font-black mb-4 tracking-wider drop-shadow-lg">Contoh Judul Message</div>
                    <div class="text-lg font-semibold opacity-80">Ini adalah contoh tampilan pesan yang akan ditampilkan pada layar display</div>
                </div>
            </div>
        </div>
        
        <!-- Tombol Aksi Warna Message -->
        <div class="flex gap-4">
            <button 
                type="submit" 
                class="group flex-1 p-4 rounded-2xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] hover:shadow-[16px_16px_32px_#bebebe,-16px_-16px_32px_#ffffff] border border-white/30 transition-all duration-300 transform hover:scale-105 active:scale-95">
                <div class="flex items-center justify-center space-x-3">
                    <div class="p-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-[4px_4px_8px_#10b981/30] group-hover:shadow-[6px_6px_12px_#10b981/40] transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-slate-700">Simpan Pengaturan Warna</span>
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
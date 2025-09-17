<div class="space-y-8">
    <!-- Neumorphic Timer Display -->
    <div class="p-8 rounded-3xl bg-gradient-to-br from-slate-50/80 to-white/80 backdrop-blur-sm shadow-[inset_20px_20px_40px_#d1d5db,inset_-20px_-20px_40px_#ffffff] border border-white/50">
        <div class="text-center">
            <div class="text-6xl font-mono font-black bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4 tracking-wider">
                {{ $timer ? $timer->formatted_time : '00:00' }}
            </div>
            <div class="flex items-center justify-center space-x-3">
                <div class="w-3 h-3 rounded-full animate-pulse
                    @if($timer && $timer->isRunning()) bg-gradient-to-r from-green-400 to-emerald-500 shadow-[0_0_20px_#10b981]
                    @elseif($timer && $timer->isPaused()) bg-gradient-to-r from-yellow-400 to-orange-500 shadow-[0_0_20px_#f59e0b]
                    @else bg-gradient-to-r from-red-400 to-rose-500 shadow-[0_0_20px_#ef4444]
                    @endif"></div>
                <span class="text-lg font-bold
                    @if($timer && $timer->isRunning()) text-green-600
                    @elseif($timer && $timer->isPaused()) text-yellow-600
                    @else text-red-600
                    @endif">
                    {{ $timer ? ucfirst($timer->status) : 'Stopped' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Neumorphic Timer Controls -->
    <div class="grid grid-cols-3 gap-6">
        <button 
            wire:click="startTimer" 
            @if($timer && $timer->isRunning()) disabled @endif
            class="group p-6 rounded-2xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] hover:shadow-[16px_16px_32px_#bebebe,-16px_-16px_32px_#ffffff] disabled:opacity-50 disabled:cursor-not-allowed border border-white/30 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <div class="flex flex-col items-center space-y-3">
                <div class="p-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-[6px_6px_12px_#10b981/30] group-hover:shadow-[8px_8px_16px_#10b981/40] transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="font-bold text-slate-700">Mulai</span>
            </div>
        </button>
        
        <button 
            wire:click="pauseTimer" 
            @if(!$timer || !$timer->isRunning()) disabled @endif
            class="group p-6 rounded-2xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] hover:shadow-[16px_16px_32px_#bebebe,-16px_-16px_32px_#ffffff] disabled:opacity-50 disabled:cursor-not-allowed border border-white/30 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <div class="flex flex-col items-center space-y-3">
                <div class="p-3 rounded-xl bg-gradient-to-r from-yellow-500 to-orange-600 text-white shadow-[6px_6px_12px_#f59e0b/30] group-hover:shadow-[8px_8px_16px_#f59e0b/40] transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="font-bold text-slate-700">Jeda</span>
            </div>
        </button>
        
        <button 
            wire:click="resetTimer" 
            class="group p-6 rounded-2xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] hover:shadow-[16px_16px_32px_#bebebe,-16px_-16px_32px_#ffffff] border border-white/30 transition-all duration-300 transform hover:scale-105 active:scale-95">
            <div class="flex flex-col items-center space-y-3">
                <div class="p-3 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-[6px_6px_12px_#ef4444/30] group-hover:shadow-[8px_8px_16px_#ef4444/40] transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <span class="font-bold text-slate-700">Reset</span>
            </div>
        </button>
    </div>
        
    </div>
    
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

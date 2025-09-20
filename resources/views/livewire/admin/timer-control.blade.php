<div class="space-y-8">
   

    <!-- Timer Display Card -->
    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
        <!-- Timer Display -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl mb-6 shadow-inner">
                <div class="text-center">
                    <div class="text-4xl font-bold text-gray-900 font-mono tracking-wider">
                        {{ $timer->formatted_time }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1 uppercase tracking-wide">
                        Timer
                    </div>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="flex justify-center mb-6">
                @if($timer->isRunning())
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        Timer Berjalan
                    </span>
                @elseif($timer->isPaused())
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        Timer Dijeda
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                        <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                        Timer Berhenti
                    </span>
                @endif
            </div>
        </div>

        <!-- Progress Bar -->
        @if($timer->duration_seconds > 0)
            <div class="mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress</span>
                    @php
                        $progress = $timer->duration_seconds > 0 
                            ? round((($timer->duration_seconds - $timer->remaining_seconds) / $timer->duration_seconds) * 100) 
                            : 0;
                    @endphp
                    <span class="text-sm text-gray-500">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-1000 ease-out shadow-sm" 
                         style="width: {{ $progress }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>0:00</span>
                    <span>{{ sprintf('%02d:%02d', floor($timer->duration_seconds / 60), $timer->duration_seconds % 60) }}</span>
                </div>
            </div>
        @endif

        <!-- Control Buttons -->
        <div class="flex justify-center gap-4">
            @if(!$timer->isRunning())
                <!-- Start/Resume Button -->
                <button 
                    wire:click="startTimer" 
                    class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r {{ $timer->isPaused() ? 'from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700' : 'from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700' }} text-white rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold"
                >
                    <i class="fas fa-play text-lg group-hover:scale-110 transition-transform"></i>
                    {{ $timer->isPaused() ? 'Lanjutkan Timer' : 'Mulai Timer' }}
                </button>
            @else
                <!-- Pause Button -->
                <button 
                    wire:click="pauseTimer" 
                    class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-2xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold"
                >
                    <i class="fas fa-pause text-lg group-hover:scale-110 transition-transform"></i>
                    Jeda Timer
                </button>
            @endif

            <!-- Reset Button -->
            <button 
                wire:click="resetTimer" 
                class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-2xl hover:from-red-600 hover:to-rose-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold"
            >
                <i class="fas fa-redo text-lg group-hover:scale-110 transition-transform"></i>
                Reset Timer
            </button>
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

<!-- Timer Control Card -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">


    <!-- Timer Display -->
    <div class="text-center mb-6">
        <div class="text-6xl font-mono font-bold text-gray-900 mb-2">
            {{ $timer->formatted_time }}
        </div>
        <div class="text-sm font-medium">
            @if($timer->isRunning())
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                    Berjalan
                </span>
            @elseif($timer->isPaused())
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                    <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1.5"></span>
                    Jeda
                </span>
            @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                    Berhenti
                </span>
            @endif
        </div>
    </div>

    <!-- Control Buttons -->
    <div class="flex justify-center gap-3">
        @if(!$timer->isRunning())
            <button wire:click="startTimer" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-play text-xs mr-2"></i>
                {{ $timer->isPaused() ? 'Lanjutkan' : 'Mulai' }}
            </button>
        @else
            <button wire:click="pauseTimer" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-pause text-xs mr-2"></i>
                Jeda
            </button>
        @endif

        <button wire:click="resetTimer" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-stop text-xs mr-2"></i>
            Reset
        </button>
    </div>

    <!-- Progress Bar -->
    @if($timer->duration_seconds > 0)
        <div class="mt-6">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>Progress</span>
                @php
                    $progress = $timer->duration_seconds > 0 
                        ? round((($timer->duration_seconds - $timer->remaining_seconds) / $timer->duration_seconds) * 100) 
                        : 0;
                @endphp
                <span>{{ $progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000" 
                     style="width: {{ $progress }}%"></div>
            </div>
        </div>
    @endif
    
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

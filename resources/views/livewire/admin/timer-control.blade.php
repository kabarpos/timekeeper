<!-- Timer Control Card -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <span class="inline-flex justify-center items-center size-10 rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-clock text-lg"></i>
                </span>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Timer Control</h2>
                <p class="text-sm text-gray-600">Kelola timer untuk tampilan publik</p>
            </div>
        </div>
    </div>

    <!-- Timer Display -->
    <div class="text-center mb-8">
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-8">
            <div class="text-6xl font-mono font-bold text-gray-900 mb-4" id="timer-display">
                {{ str_pad(floor($timer->remaining_seconds / 60), 2, '0', STR_PAD_LEFT) }}:{{ str_pad($timer->remaining_seconds % 60, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div class="flex justify-center">
                @if($timer && $timer->isRunning())
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                        <span class="size-1.5 inline-block rounded-full bg-teal-800 animate-pulse"></span>
                        Berjalan
                    </span>
                @elseif($timer && $timer->isPaused())
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <span class="size-1.5 inline-block rounded-full bg-yellow-800"></span>
                        Jeda
                    </span>
                @else
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <span class="size-1.5 inline-block rounded-full bg-gray-800"></span>
                        Berhenti
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Control Buttons -->
    <div class="flex justify-center gap-3 mb-6">
        @if($timer && !$timer->isRunning())
            <button wire:click="startTimer" type="button" 
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-teal-600 text-white hover:bg-teal-700 focus:outline-none focus:bg-teal-700 disabled:opacity-50 disabled:pointer-events-none">
                <i class="fas fa-play flex-shrink-0 size-4"></i>
                Mulai
            </button>
        @else
            <button wire:click="pauseTimer" type="button" 
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-yellow-600 text-white hover:bg-yellow-700 focus:outline-none focus:bg-yellow-700 disabled:opacity-50 disabled:pointer-events-none">
                <i class="fas fa-pause flex-shrink-0 size-4"></i>
                Jeda
            </button>
        @endif
        
        <button wire:click="resetTimer" type="button" 
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
            <i class="fas fa-redo flex-shrink-0 size-4"></i>
            Reset
        </button>
    </div>
    
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

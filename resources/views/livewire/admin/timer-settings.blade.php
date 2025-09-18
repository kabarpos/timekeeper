<!-- Timer Settings Card -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <span class="inline-flex justify-center items-center size-10 rounded-lg bg-purple-100 text-purple-600">
                    <i class="fas fa-cog text-lg"></i>
                </span>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Timer Settings</h2>
                <p class="text-sm text-gray-600">Atur durasi dan tampilan timer</p>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Pengaturan Durasi Timer -->
        <div class="mb-8">
            <label class="block text-sm font-bold text-slate-700 mb-4">Durasi Timer</label>
            
            <!-- Preset Durasi -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Preset Durasi</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach([5, 10, 15, 30] as $minutes)
                        <button wire:click="setDuration({{ $minutes }})" type="button"
                                class="py-3 px-4 inline-flex flex-col items-center justify-center text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                            <span class="text-lg font-bold text-purple-600">{{ $minutes }}</span>
                            <span class="text-xs text-gray-500">menit</span>
                        </button>
                    @endforeach
                </div>
            </div>
            
            <!-- Input Manual -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Input Manual</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="minutes" class="block text-sm font-medium mb-2">Menit</label>
                        <input wire:model="duration_minutes" type="number" min="0" max="59" id="minutes"
                               class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                    </div>
                    <div>
                        <label for="seconds" class="block text-sm font-medium mb-2">Detik</label>
                        <input wire:model="duration_seconds" type="number" min="0" max="59" id="seconds"
                               class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                    </div>
                </div>
            </div>
            @error('duration_minutes') 
                <div class="text-red-500 text-sm font-medium mt-2 p-2 rounded-lg bg-red-50/50">{{ $message }}</div> 
            @enderror
        </div>
        
        <!-- Color Settings -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Pengaturan Warna</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="bg-color" class="block text-sm font-medium mb-2">Warna Background</label>
                    <div class="flex">
                        <input wire:model="background_color" type="color" id="bg-color"
                               class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-s-lg disabled:opacity-50 disabled:pointer-events-none">
                        <input wire:model="background_color" type="text" 
                               class="py-3 px-4 block w-full border-gray-200 shadow-sm rounded-e-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                    </div>
                </div>
                <div>
                    <label for="font-color" class="block text-sm font-medium mb-2">Warna Font</label>
                    <div class="flex">
                        <input wire:model="font_color" type="color" id="font-color"
                               class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-s-lg disabled:opacity-50 disabled:pointer-events-none">
                        <input wire:model="font_color" type="text" 
                               class="py-3 px-4 block w-full border-gray-200 shadow-sm rounded-e-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timer Preview -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Preview Timer</h4>
            <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-8" 
                 style="background-color: {{ $background_color ?? '#f9fafb' }}; color: {{ $font_color ?? '#111827' }}">
                <div class="text-center">
                    <div class="text-6xl font-mono font-bold mb-2">
                        {{ sprintf('%02d:%02d', $duration_minutes ?? 0, $duration_seconds ?? 0) }}
                    </div>
                    <div class="text-lg opacity-75">Preview</div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-center gap-3">
            <button wire:click="saveSettings" type="button" 
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                <i class="fas fa-save flex-shrink-0 size-4"></i>
                Simpan
            </button>
            
            <button wire:click="resetSettings" type="button" 
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                <i class="fas fa-undo flex-shrink-0 size-4"></i>
                Reset
            </button>
        </div>
    </form>
    
    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mt-6 bg-teal-50 border border-teal-200 rounded-xl p-4" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-success-label">
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
        <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-4" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-danger-label">
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
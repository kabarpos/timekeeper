<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Kontrol Timer</h2>
        
        <!-- Current Timer Display -->
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <div class="text-center">
                <div class="text-4xl font-mono font-bold text-gray-800 mb-2">
                    {{ $timer ? $timer->formatted_time : '00:00' }}
                </div>
                <div class="text-sm text-gray-600">
                    Status: <span class="font-semibold
                        @if($timer && $timer->isRunning()) text-green-600
                        @elseif($timer && $timer->isPaused()) text-yellow-600
                        @else text-red-600
                        @endif">
                        {{ $timer ? ucfirst($timer->status) : 'Stopped' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Timer Controls -->
        <div class="grid grid-cols-3 gap-3 mb-6">
            <button 
                wire:click="startTimer" 
                @if($timer && $timer->isRunning()) disabled @endif
                class="bg-green-500 hover:bg-green-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-play mr-2"></i>Mulai
            </button>
            
            <button 
                wire:click="pauseTimer" 
                @if(!$timer || !$timer->isRunning()) disabled @endif
                class="bg-yellow-500 hover:bg-yellow-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-pause mr-2"></i>Jeda
            </button>
            
            <button 
                wire:click="resetTimer" 
                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-stop mr-2"></i>Reset
            </button>
        </div>
        
        <!-- Duration Setting -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Timer (Menit)</label>
            <div class="flex gap-3">
                <input 
                    type="number" 
                    wire:model="duration_minutes" 
                    min="1" 
                    max="60" 
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button 
                    wire:click="setDuration" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Set Durasi
                </button>
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

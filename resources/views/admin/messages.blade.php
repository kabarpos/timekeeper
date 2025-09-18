<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 relative overflow-hidden py-12">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Navigation -->
            <div class="mb-8">
                <div class="p-6 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-[8px_8px_16px_#3b82f6/30] mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-700 to-blue-600 bg-clip-text text-transparent">
                                Messages
                            </h2>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.index') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-slate-500 to-gray-600 text-white shadow-[8px_8px_16px_#475569/30] text-sm font-medium">
                                ← Dashboard
                            </a>
                            <a href="{{ route('display.index') }}" target="_blank" class="px-4 py-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-[8px_8px_16px_#10b981/30] text-sm font-medium">
                                🖥️ Open Display
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Color Settings -->
            <div class="mb-12">
                <div class="p-8 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 shadow-[8px_8px_16px_#8b5cf6/30] mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-slate-700 to-violet-600 bg-clip-text text-transparent">Pengaturan Warna Message</h3>
                    </div>
                    @livewire('admin.message-color-settings')
                </div>
            </div>

            <!-- Message CRUD -->
            <div class="p-8 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30">
                <div class="flex items-center mb-6">
                    <div class="p-3 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 shadow-[8px_8px_16px_#10b981/30] mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold bg-gradient-to-r from-slate-700 to-green-600 bg-clip-text text-transparent">
                        Message Management
                    </h3>
                </div>
                @livewire('admin.message-crud')
            </div>
            

        </div>
    </div>
    
    <script>
        // Keyboard shortcuts untuk message management
        document.addEventListener('keydown', function(e) {
            // Hanya aktif jika tidak sedang mengetik di input
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }
            
            switch(e.key) {
                case 'n': // N - New message
                case 'N':
                    e.preventDefault();
                    Livewire.dispatch('create-new-message');
                    break;
                case 'm': // M - Switch to message display
                case 'M':
                    e.preventDefault();
                    Livewire.dispatch('switch-to-message');
                    break;
                case 't': // T - Switch to timer display
                case 'T':
                    e.preventDefault();
                    Livewire.dispatch('switch-to-timer');
                    break;
            }
        });
    </script>
</x-app-layout>
<x-app-layout>
    <div class="min-h-screen bg-gray-100 relative">
        <!-- Modern Background Pattern -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-400/10 to-pink-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-cyan-400/5 to-blue-400/5 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Quick Actions -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Quick Actions</h2>
                    <div class="h-1 flex-1 bg-gradient-to-r from-blue-200 to-purple-200 rounded-full ml-6"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{ route('admin.timer') }}" class="group relative bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-stopwatch text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                Timer Control
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Kontrol waktu presentasi dengan presisi tinggi
                            </p>
                            <div class="flex items-center text-blue-600 text-sm font-medium">
                                <span>Kelola Timer</span>
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.messages') }}" class="group relative bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-violet-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-comment-alt text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                                Messages
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Kelola pesan display dengan mudah
                            </p>
                            <div class="flex items-center text-purple-600 text-sm font-medium">
                                <span>Kelola Pesan</span>
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('display.timer') }}" target="_blank" class="group relative bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-red-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">
                                Timer Display
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Tampilan timer untuk presentasi
                            </p>
                            <div class="flex items-center text-orange-600 text-sm font-medium">
                                <span>Buka Display</span>
                                <i class="fas fa-external-link-alt ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('display.message') }}" target="_blank" class="group relative bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-comment text-white text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">
                                Message Display
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Tampilan pesan untuk audience
                            </p>
                            <div class="flex items-center text-emerald-600 text-sm font-medium">
                                <span>Buka Display</span>
                                <i class="fas fa-external-link-alt ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="space-y-8">
                <!-- Primary Operations - Message & Timer Control -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Active Message Widget -->
                    <div class="w-full">
                        @livewire('admin.active-message-widget')
                    </div>

                    <!-- Timer Control -->
                    <div class="w-full">
                        <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-stopwatch text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Timer Control</h3>
                                        <p class="text-sm text-gray-600">Kontrol waktu presentasi</p>
                                    </div>
                                </div>
                                <div class="bg-gray-50/50 rounded-xl p-6">
                                    @livewire('admin.timer-control')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Configuration - Display Settings -->
                <div class="w-full">
                    <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="p-6">
                            
                            <div class="bg-gray-50/50 rounded-xl p-6">
                                @livewire('admin.settings-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
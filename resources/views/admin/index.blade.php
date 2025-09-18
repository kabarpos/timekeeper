<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 relative overflow-hidden py-12">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
 
            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('admin.timer') }}" class="group flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md focus:outline-none focus:shadow-md transition">
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="inline-flex justify-center items-center size-8 bg-blue-600 text-white rounded-lg">
                                        <i class="fas fa-stopwatch flex-shrink-0 size-4"></i>
                                    </div>
                                    <div class="grow">
                                        <h3 class="group-hover:text-blue-600 font-semibold text-gray-800">
                                            Timer Control
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Kontrol waktu presentasi
                                        </p>
                                    </div>
                                </div>
                                <div class="ps-3">
                                    <i class="fas fa-chevron-right flex-shrink-0 size-4 text-gray-600 group-hover:text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.messages') }}" class="group flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md focus:outline-none focus:shadow-md transition">
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="inline-flex justify-center items-center size-8 bg-purple-600 text-white rounded-lg">
                                        <i class="fas fa-comment-alt flex-shrink-0 size-4"></i>
                                    </div>
                                    <div class="grow">
                                        <h3 class="group-hover:text-purple-600 font-semibold text-gray-800">
                                            Messages
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Kelola pesan display
                                        </p>
                                    </div>
                                </div>
                                <div class="ps-3">
                                    <i class="fas fa-chevron-right flex-shrink-0 size-4 text-gray-600 group-hover:text-purple-600"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                                        <a href="{{ route('display.timer') }}" target="_blank" class="group flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md focus:outline-none focus:shadow-md transition">
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="inline-flex justify-center items-center size-8 bg-orange-600 text-white rounded-lg">
                                        <i class="fas fa-clock flex-shrink-0 size-4"></i>
                                    </div>
                                    <div class="grow">
                                        <h3 class="group-hover:text-orange-600 font-semibold text-gray-800">
                                            Timer Only
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Hanya tampilan timer
                                        </p>
                                    </div>
                                </div>
                                <div class="ps-3">
                                    <i class="fas fa-external-link-alt flex-shrink-0 size-4 text-gray-600 group-hover:text-orange-600"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('display.message') }}" target="_blank" class="group flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md focus:outline-none focus:shadow-md transition">
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-x-3">
                                    <div class="inline-flex justify-center items-center size-8 bg-violet-600 text-white rounded-lg">
                                        <i class="fas fa-comment flex-shrink-0 size-4"></i>
                                    </div>
                                    <div class="grow">
                                        <h3 class="group-hover:text-violet-600 font-semibold text-gray-800">
                                            Message Only
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Hanya tampilan pesan
                                        </p>
                                    </div>
                                </div>
                                <div class="ps-3">
                                    <i class="fas fa-external-link-alt flex-shrink-0 size-4 text-gray-600 group-hover:text-violet-600"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Display Mode Settings -->
                <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center gap-x-3 mb-4">
                            <div class="flex-shrink-0">
                                <span class="inline-flex justify-center items-center size-10 rounded-lg bg-purple-100 text-purple-600">
                                    <i class="fas fa-cog text-lg"></i>
                                </span>
                            </div>
                            <div class="grow">
                                <h3 class="text-lg font-semibold text-gray-800">Display Mode</h3>
                                <p class="text-sm text-gray-600">Configure display settings</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            @livewire('admin.settings-form')
                        </div>
                    </div>
                </div>

                <!-- Timer Control -->
                <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="p-4 md:p-5">
                        <div class="flex items-center gap-x-3 mb-4">
                            <div class="flex-shrink-0">
                                <span class="inline-flex justify-center items-center size-10 rounded-lg bg-blue-100 text-blue-600">
                                    <i class="fas fa-clock text-lg"></i>
                                </span>
                            </div>
                            <div class="grow">
                                <h3 class="text-lg font-semibold text-gray-800">Timer Control</h3>
                                <p class="text-sm text-gray-600">Manage presentation timer</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            @livewire('admin.timer-control')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
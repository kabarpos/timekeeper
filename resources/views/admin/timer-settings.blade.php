<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Timer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigation Back -->
            <div class="mb-6">
                <a href="{{ route('admin.settings') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    ← Kembali ke Settings
                </a>
            </div>

            <!-- Timer Settings Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Pengaturan Timer</h3>
                        <p class="text-sm text-gray-600">Atur durasi, warna background dan font untuk tampilan timer</p>
                    </div>
                    
                    @livewire('admin.timer-settings')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
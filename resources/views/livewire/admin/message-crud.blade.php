<div class="space-y-6">
    <!-- Message Form -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6">
        <div class="flex items-center gap-x-3 mb-6">
            <div class="flex-shrink-0">
                <span class="flex size-8 items-center justify-center rounded-lg bg-blue-600 text-white">
                    <i class="fas fa-comment-alt flex-shrink-0 size-4"></i>
                </span>
            </div>
            <div class="grow">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $editing_id ? 'Edit Pesan' : 'Tambah Pesan Baru' }}
                </h2>
                <p class="text-sm text-gray-500">Kelola pesan yang akan ditampilkan</p>
            </div>
        </div>
        
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Pesan</label>
                    <input 
                        type="text" 
                        id="title"
                        wire:model="title" 
                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                        placeholder="Masukkan judul pesan yang menarik...">
                    @error('title') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Pesan</label>
                    <select 
                        id="type"
                        wire:model="type" 
                        class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                        <option value="short">Pesan Pendek</option>
                        <option value="long">Pesan Panjang</option>
                    </select>
                    @error('type') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Konten Pesan</label>
                <textarea 
                    id="content"
                    wire:model="content" 
                    rows="6" 
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                    placeholder="Tulis konten pesan yang akan ditampilkan..."></textarea>
                @error('content') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>
            
            <div class="flex gap-3 pt-4">
                <button 
                    type="submit" 
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    <i class="fas fa-save flex-shrink-0 size-4"></i>
                    {{ $editing_id ? 'Update Pesan' : 'Simpan Pesan' }}
                </button>
                
                @if($editing_id)
                    <button 
                        type="button" 
                        wire:click="resetForm" 
                        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        <i class="fas fa-times flex-shrink-0 size-4"></i>
                        Batal
                    </button>
                @endif
            </div>
        </form>
    </div>
        
    <!-- Messages List -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6">
        <div class="flex items-center gap-x-3 mb-6">
            <div class="flex-shrink-0">
                <span class="flex size-8 items-center justify-center rounded-lg bg-purple-600 text-white">
                    <i class="fas fa-list flex-shrink-0 size-4"></i>
                </span>
            </div>
            <div class="grow">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Pesan</h3>
                <p class="text-sm text-gray-500">Kelola semua pesan yang tersedia</p>
            </div>
        </div>
        
        <div class="space-y-3">
            @forelse($messages as $msg)
                <div class="group border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-x-3 mb-2">
                                <h4 class="text-sm font-semibold text-gray-800">{{ $msg->title ?: 'Tanpa Judul' }}</h4>
                                <div class="flex gap-x-2">
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $msg->type === 'short' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $msg->type === 'short' ? 'Pendek' : 'Panjang' }}
                                    </span>
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $msg->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $msg->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">{{ Str::limit($msg->content, 120) }}</p>
                        </div>
                        
                        <div class="flex items-center gap-x-2 ml-4">
                            <div class="hs-tooltip inline-block">
                                <button 
                                    wire:click="activate({{ $msg->id }})" 
                                    class="hs-tooltip-toggle size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-green-600 hover:bg-green-100 focus:outline-none focus:bg-green-100 disabled:opacity-50 disabled:pointer-events-none">
                                    <i class="fas fa-check flex-shrink-0 size-4"></i>
                                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm" role="tooltip">
                                        Aktifkan Pesan
                                    </span>
                                </button>
                            </div>
                            
                            <div class="hs-tooltip inline-block">
                                <button 
                                    wire:click="edit({{ $msg->id }})" 
                                    class="hs-tooltip-toggle size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 disabled:opacity-50 disabled:pointer-events-none">
                                    <i class="fas fa-edit flex-shrink-0 size-4"></i>
                                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm" role="tooltip">
                                        Edit Pesan
                                    </span>
                                </button>
                            </div>
                            
                            <div class="hs-tooltip inline-block">
                                <button 
                                    wire:click="delete({{ $msg->id }})" 
                                    onclick="return confirm('Yakin ingin menghapus pesan ini?')" 
                                    class="hs-tooltip-toggle size-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:bg-red-100 focus:outline-none focus:bg-red-100 disabled:opacity-50 disabled:pointer-events-none">
                                    <i class="fas fa-trash flex-shrink-0 size-4"></i>
                                    <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm" role="tooltip">
                                        Hapus Pesan
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="max-w-sm mx-auto">
                        <div class="flex justify-center items-center size-16 bg-gray-100 rounded-full mx-auto mb-4">
                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Pesan</h3>
                        <p class="text-sm text-gray-500">Mulai buat pesan pertama Anda menggunakan form di atas!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
    
    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="fixed top-4 end-4 z-50 max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg" role="alert">
            <div class="flex p-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                </div>
                <div class="ms-3">
                    <p class="text-sm text-gray-700">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 end-4 z-50 max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg" role="alert">
            <div class="flex p-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                </div>
                <div class="ms-3">
                    <p class="text-sm text-gray-700">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Preline UI Interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips if needed
        if (typeof HSTooltip !== 'undefined') {
            HSTooltip.autoInit();
        }
        
        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    });
    
    // Livewire alert system
    window.addEventListener('livewire:init', () => {
        Livewire.on('show-alert', (event) => {
            const alertData = event[0] || event;
            showAlert(alertData.message, alertData.type || 'success');
        });
    });
    
    function showAlert(message, type = 'success') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        const alert = document.createElement('div');
        alert.className = 'custom-alert fixed top-4 end-4 z-50 max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg';
        alert.setAttribute('role', 'alert');
        
        const iconClass = type === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500';
        
        alert.innerHTML = `
            <div class="flex p-4">
                <div class="flex-shrink-0">
                    <i class="${iconClass} mt-0.5"></i>
                </div>
                <div class="ms-3">
                    <p class="text-sm text-gray-700">${message}</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%)';
            alert.style.transition = 'all 0.3s ease';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 3000);
    }
</script>

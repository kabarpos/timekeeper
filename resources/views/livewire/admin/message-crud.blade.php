<div class="space-y-6">
    <!-- Message Form -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-plus text-white text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $editing_id ? 'Edit Pesan' : 'Buat Pesan Baru' }}</h3>
                <p class="text-sm text-gray-600">{{ $editing_id ? 'Perbarui informasi pesan' : 'Tambahkan pesan baru ke sistem' }}</p>
            </div>
        </div>

        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Pesan</label>
                <input 
                    type="text" 
                    id="title"
                    wire:model="title" 
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                    placeholder="Masukkan judul pesan...">
                @error('title') <p class="text-xs text-red-600 mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Konten Pesan</label>
                <textarea 
                    id="content"
                    wire:model="content" 
                    rows="4" 
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 resize-none"
                    placeholder="Masukkan konten pesan..."></textarea>
                @error('content') <p class="text-xs text-red-600 mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p> @enderror
            </div>
            
            <div class="flex gap-3 pt-2">
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save text-sm"></i>
                    {{ $editing_id ? 'Update Pesan' : 'Simpan Pesan' }}
                </button>
                
                @if($editing_id)
                    <button 
                        type="button" 
                        wire:click="resetForm" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                        <i class="fas fa-times text-sm"></i>
                        Batal Edit
                    </button>
                @endif
            </div>
        </form>
    </div>
        
    <!-- Messages List -->
    <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 shadow-xl rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-list text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Daftar Pesan</h3>
                    <p class="text-sm text-gray-600">Total: {{ count($messages) }} pesan</p>
                </div>
            </div>
        </div>
        
        <div class="space-y-3">
            @forelse($messages as $msg)
                <div class="group bg-white border border-gray-200 rounded-xl p-4 hover:shadow-lg hover:border-gray-300 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-base font-semibold text-gray-900 truncate">{{ $msg->title ?: 'Tanpa Judul' }}</h4>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $msg->is_active ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                        <i class="fas {{ $msg->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} text-xs mr-1"></i>
                                        {{ $msg->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($msg->content, 150) }}</p>
                            <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $msg->created_at->format('d M Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-clock"></i>
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 ml-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="relative group/tooltip">
                                <button 
                                    wire:click="activate({{ $msg->id }})" 
                                    class="w-9 h-9 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent text-green-600 hover:bg-green-100 focus:outline-none focus:bg-green-100 transition-all duration-200">
                                    <i class="fas fa-check text-sm"></i>
                                </button>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                    Aktifkan Pesan
                                </div>
                            </div>
                            
                            <div class="relative group/tooltip">
                                <button 
                                    wire:click="edit({{ $msg->id }})" 
                                    class="w-9 h-9 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:bg-blue-100 focus:outline-none focus:bg-blue-100 transition-all duration-200">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                    Edit Pesan
                                </div>
                            </div>
                            
                            <div class="relative group/tooltip">
                                <button 
                                    wire:click="delete({{ $msg->id }})" 
                                    class="w-9 h-9 inline-flex justify-center items-center text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:bg-red-100 focus:outline-none focus:bg-red-100 transition-all duration-200">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover/tooltip:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                    Hapus Pesan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="max-w-sm mx-auto">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Belum Ada Pesan</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Mulai buat pesan pertama Anda menggunakan form di atas untuk menampilkan konten ke layar display!</p>
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

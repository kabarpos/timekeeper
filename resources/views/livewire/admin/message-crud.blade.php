<div class="space-y-8">
    <!-- Neumorphic Message Form -->
    <div class="p-8 rounded-3xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] border border-white/30">
        <div class="flex items-center space-x-4 mb-8">
            <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-[8px_8px_16px_#3b82f6/30]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black bg-gradient-to-r from-slate-700 to-slate-900 bg-clip-text text-transparent">
                {{ $editing_id ? 'Edit Pesan' : 'Tambah Pesan Baru' }}
            </h2>
        </div>
        
        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-700">Judul Pesan</label>
                    <input 
                        type="text" 
                        wire:model="title" 
                        class="w-full px-4 py-4 rounded-2xl bg-white/50 border-0 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] focus:shadow-[inset_12px_12px_24px_#bebebe,inset_-12px_-12px_24px_#ffffff] focus:outline-none text-slate-700 font-medium placeholder-slate-400"
                        placeholder="Masukkan judul pesan yang menarik...">
                    @error('title') <span class="text-red-500 text-sm font-semibold">{{ $message }}</span> @enderror
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-700">Tipe Pesan</label>
                    <select 
                        wire:model="type" 
                        class="w-full px-4 py-4 rounded-2xl bg-white/50 border-0 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] focus:shadow-[inset_12px_12px_24px_#bebebe,inset_-12px_-12px_24px_#ffffff] focus:outline-none text-slate-700 font-medium cursor-pointer">
                        <option value="short">📝 Pesan Pendek</option>
                        <option value="long">📄 Pesan Panjang</option>
                    </select>
                    @error('type') <span class="text-red-500 text-sm font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="space-y-3">
                <label class="block text-sm font-bold text-slate-700">Konten Pesan</label>
                <textarea 
                    wire:model="content" 
                    rows="6" 
                    class="w-full px-4 py-4 rounded-2xl bg-white/50 border-0 shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] focus:shadow-[inset_12px_12px_24px_#bebebe,inset_-12px_-12px_24px_#ffffff] focus:outline-none text-slate-700 font-medium placeholder-slate-400 resize-none"
                    placeholder="Tulis konten pesan yang akan ditampilkan..."></textarea>
                @error('content') <span class="text-red-500 text-sm font-semibold">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex gap-4 pt-4">
                <button 
                    type="submit" 
                    class="group flex-1 p-4 rounded-2xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] border border-white/30">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="p-2 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-[4px_4px_8px_#3b82f6/30]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-slate-700">{{ $editing_id ? '✏️ Update Pesan' : '💾 Simpan Pesan' }}</span>
                    </div>
                </button>
                
                @if($editing_id)
                    <button 
                        type="button" 
                        wire:click="resetForm" 
                        class="group p-4 rounded-2xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] border border-white/30">
                        <div class="flex items-center justify-center space-x-3">
                            <div class="p-2 rounded-xl bg-gradient-to-r from-gray-500 to-slate-600 text-white shadow-[4px_4px_8px_#6b7280/30]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-slate-700">❌ Batal</span>
                        </div>
                    </button>
                @endif
            </div>
        </form>
    </div>
        
    <!-- Neumorphic Messages List -->
    <div class="p-8 rounded-3xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] border border-white/30">
        <div class="flex items-center space-x-4 mb-8">
            <div class="p-4 rounded-2xl bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-[8px_8px_16px_#a855f7/30]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-black bg-gradient-to-r from-slate-700 to-slate-900 bg-clip-text text-transparent">Daftar Pesan</h3>
        </div>
        
        <div class="space-y-4">
            @forelse($messages as $msg)
                <div class="message-card p-6 rounded-2xl bg-gradient-to-br from-white/60 to-slate-50/60 backdrop-blur-sm shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] border border-white/40">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 space-y-3">
                            <div class="flex items-center space-x-4">
                                <h4 class="text-xl font-bold text-slate-800">{{ $msg->title ?: '📝 Tanpa Judul' }}</h4>
                                <div class="flex space-x-2">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full shadow-[4px_4px_8px_rgba(0,0,0,0.1),-4px_-4px_8px_rgba(255,255,255,0.8)] border border-white/50
                                        {{ $msg->type === 'short' ? 'bg-gradient-to-r from-blue-400 to-cyan-500 text-white' : 'bg-gradient-to-r from-purple-400 to-pink-500 text-white' }}">
                                        {{ $msg->type === 'short' ? '📝 Pendek' : '📄 Panjang' }}
                                    </span>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full shadow-[4px_4px_8px_rgba(0,0,0,0.1),-4px_-4px_8px_rgba(255,255,255,0.8)] border border-white/50
                                        {{ $msg->is_active ? 'bg-gradient-to-r from-green-400 to-emerald-500 text-white' : 'bg-gradient-to-r from-gray-400 to-slate-500 text-white' }}">
                                        {{ $msg->is_active ? '✅ Aktif' : '⏸️ Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-slate-600 font-medium leading-relaxed">{{ Str::limit($msg->content, 120) }}</p>
                        </div>
                        
                        <div class="flex space-x-2 ml-6">
                            <button 
                                wire:click="activate({{ $msg->id }})" 
                                class="group p-3 rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] border border-white/30"
                                title="Aktifkan Pesan">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            
                            <button 
                                wire:click="edit({{ $msg->id }})" 
                                class="group p-3 rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] border border-white/30"
                                title="Edit Pesan">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            
                            <button 
                                wire:click="delete({{ $msg->id }})" 
                                onclick="return confirm('🗑️ Yakin ingin menghapus pesan ini?')" 
                                class="group p-3 rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[6px_6px_12px_#bebebe,-6px_-6px_12px_#ffffff] border border-white/30"
                                title="Hapus Pesan">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 rounded-2xl bg-gradient-to-br from-slate-50/60 to-white/60 backdrop-blur-sm shadow-[inset_8px_8px_16px_#bebebe,inset_-8px_-8px_16px_#ffffff] border border-white/40 text-center">
                    <div class="space-y-4">
                        <div class="p-6 rounded-2xl bg-gradient-to-r from-slate-400 to-slate-500 text-white shadow-[8px_8px_16px_#64748b/30] mx-auto w-fit">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-bold text-slate-700">📭 Belum Ada Pesan</h4>
                        <p class="text-slate-600 font-medium">Silakan tambah pesan baru menggunakan form di atas</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
    
    <!-- Neumorphic Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mt-8 p-6 rounded-2xl bg-gradient-to-br from-emerald-50/80 to-green-50/80 backdrop-blur-sm shadow-[8px_8px_16px_rgba(34,197,94,0.2),-8px_-8px_16px_rgba(255,255,255,0.9)] border border-emerald-200/50">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-[6px_6px_12px_rgba(34,197,94,0.3)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-emerald-800 font-bold text-lg">✅ {{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-8 p-6 rounded-2xl bg-gradient-to-br from-red-50/80 to-rose-50/80 backdrop-blur-sm shadow-[8px_8px_16px_rgba(239,68,68,0.2),-8px_-8px_16px_rgba(255,255,255,0.9)] border border-red-200/50">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-[6px_6px_12px_rgba(239,68,68,0.3)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-red-800 font-bold text-lg">❌ {{ session('error') }}</p>
            </div>
        </div>
    @endif
</div>

<script>
    // Simple interactions without animations
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success/error messages
        const alerts = document.querySelectorAll('.animate-pulse');
        alerts.forEach(alert => {
            // Auto-hide after 6 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 6000);
        });
        
        // Basic CSS for hover effects only
        const style = document.createElement('style');
        style.textContent = `
            /* Basic hover effects */
            .message-card:hover {
                box-shadow: 20px 20px 40px #bebebe, -20px -20px 40px #ffffff;
            }
            
            /* Form focus effects */
            input:focus, textarea:focus, select:focus {
                box-shadow: inset 8px 8px 16px #bebebe, inset -8px -8px 16px #ffffff, 0 0 0 3px rgba(59, 130, 246, 0.3);
            }
        `;
        document.head.appendChild(style);
    });
    
    // Simple alert system without animations
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
        alert.className = `custom-alert fixed top-4 right-4 z-50 p-4 rounded-2xl shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] border border-white/30 backdrop-blur-sm max-w-md ${
            type === 'success' 
                ? 'bg-gradient-to-br from-emerald-50/90 to-green-50/90 border-emerald-200/50' 
                : 'bg-gradient-to-br from-red-50/90 to-rose-50/90 border-red-200/50'
        }`;
        
        alert.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-xl ${
                    type === 'success' 
                        ? 'bg-gradient-to-r from-emerald-500 to-green-600' 
                        : 'bg-gradient-to-r from-red-500 to-rose-600'
                } text-white shadow-[4px_4px_8px_rgba(0,0,0,0.2)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${
                            type === 'success' 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                        }
                    </svg>
                </div>
                <p class="font-bold ${
                    type === 'success' ? 'text-emerald-800' : 'text-red-800'
                }">${message}</p>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        // Auto-hide after 6 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 6000);
    }
</script>

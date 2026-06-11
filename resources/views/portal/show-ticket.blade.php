@extends('portal.layouts.app')
@section('page_title', 'Detail Tiket ' . $ticket->ticket_number)

@section('content')
<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- KOLOM KIRI: TICKET INFO & ACTION (1/3) -->
    <div class="space-y-6">
        <!-- Info Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <div class="mb-4">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Nomor Tiket</p>
                <h2 class="text-2xl font-black text-blue-600">{{ $ticket->ticket_number }}</h2>
            </div>
            
            <h3 class="text-lg font-bold text-slate-800 mb-4">{{ $ticket->name }}</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                    <span class="text-xs font-bold text-slate-500">Status</span>
                    @if(in_array($ticket->status, [5, 6]))
                        <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100">Selesai</span>
                    @elseif($ticket->status == 1)
                        <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase bg-blue-50 text-blue-600 border border-blue-100">Menunggu IT</span>
                    @else
                        <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-100">Diproses</span>
                    @endif
                </div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                    <span class="text-xs font-bold text-slate-500">Prioritas</span>
                    <span class="text-xs font-black {{ $ticket->priority == 3 ? 'text-red-500' : ($ticket->priority == 2 ? 'text-amber-500' : 'text-slate-600') }}">
                        {{ $ticket->priority == 3 ? 'High' : ($ticket->priority == 2 ? 'Medium' : 'Low') }}
                    </span>
                </div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                    <span class="text-xs font-bold text-slate-500">Kategori</span>
                    <span class="text-xs font-bold text-slate-800">{{ $ticket->type }}</span>
                </div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                    <span class="text-xs font-bold text-slate-500">Dibuat Pada</span>
                    <span class="text-xs font-bold text-slate-800">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, H:i') }}</span>
                </div>
                <div class="pt-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block mb-2">Ditangani Oleh:</span>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs">
                            {{ $ticket->technician ? substr($ticket->technician->full_name, 0, 1) : '?' }}
                        </div>
                        <span class="text-sm font-bold text-slate-700">{{ $ticket->technician->full_name ?? 'Belum Di-assign' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Box (MUNCUL JIKA STATUS = 5 / RESOLVED) -->
        @if($ticket->status == 5)
        <div class="bg-emerald-50 rounded-3xl border border-emerald-200 shadow-sm p-6 text-center">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xl mx-auto mb-3">
                <i class="fas fa-check-double"></i>
            </div>
            <h3 class="font-black text-emerald-800 mb-2">Tiket Selesai?</h3>
            <p class="text-xs font-medium text-emerald-600 mb-4">Teknisi menyatakan kendala sudah diperbaiki. Silakan konfirmasi untuk menutup tiket.</p>
            <form action="{{ route('portal.approve-ticket', $ticket->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition-all">
                    Tutup & Approve Tiket
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- KOLOM KANAN: LIVE CHAT THREAD (2/3) -->
    <div class="lg:col-span-2 flex flex-col h-[800px] bg-slate-50 rounded-3xl border border-slate-200 shadow-inner overflow-hidden relative">
        
        <!-- Chat Header -->
        <div class="bg-white px-6 py-4 border-b border-slate-200 flex items-center justify-between z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center"><i class="fas fa-comments"></i></div>
                <div>
                    <h3 class="font-bold text-slate-800">Ruang Diskusi IT</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Respon Real-time</p>
                </div>
            </div>
        </div>

        <!-- Chat Messages (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6" id="chatContainer">
            
            <!-- Pesan Pertama (Deskripsi Awal User) -->
            <div class="flex flex-col items-end">
                <div class="max-w-[80%] bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none shadow-sm">
                    <p class="text-sm font-medium whitespace-pre-wrap">{{ $ticket->description }}</p>
                </div>
                <span class="text-[10px] font-bold text-slate-400 mt-1">Anda &middot; {{ \Carbon\Carbon::parse($ticket->created_at)->format('H:i') }}</span>
            </div>

            <!-- Loop Followups/Balasan -->
            @foreach($ticket->followups as $reply)
                @if($reply->user_id == Auth::user()->employee_id)
                    <!-- Bubble Kanan (User) -->
                    <div class="flex flex-col items-end">
                        <div class="max-w-[80%] bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none shadow-sm">
                            <p class="text-sm font-medium whitespace-pre-wrap">{{ $reply->message }}</p>
                            @if($reply->attachment)
                                <a href="{{ asset('storage/'.$reply->attachment) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 text-xs bg-black/20 px-3 py-1.5 rounded-lg hover:bg-black/30 transition">
                                    <i class="fas fa-paperclip"></i> Lihat Lampiran
                                </a>
                            @endif
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 mt-1">Anda &middot; {{ \Carbon\Carbon::parse($reply->created_at)->format('d M, H:i') }}</span>
                    </div>
                @else
                    <!-- Bubble Kiri (Admin/Teknisi) -->
                    <div class="flex flex-col items-start">
                        <div class="flex items-end gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold shadow-sm">IT</div>
                            <div class="max-w-[80%] bg-white border border-slate-200 text-slate-700 p-4 rounded-2xl rounded-tl-none shadow-sm">
                                <p class="text-sm font-medium whitespace-pre-wrap">{{ $reply->message }}</p>
                                @if($reply->attachment)
                                    <a href="{{ asset('storage/'.$reply->attachment) }}" target="_blank" class="mt-3 inline-flex items-center gap-2 text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg hover:bg-slate-200 transition">
                                        <i class="fas fa-paperclip"></i> Lihat Lampiran
                                    </a>
                                @endif
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 mt-1 ml-10">{{ $reply->user->full_name ?? 'IT Support' }} &middot; {{ \Carbon\Carbon::parse($reply->created_at)->format('d M, H:i') }}</span>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Chat Input Area (Hanya Muncul jika belum Closed) -->
        @if($ticket->status != 6)
        <div class="bg-white p-4 border-t border-slate-200">
            <form action="{{ route('portal.reply-ticket', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-end gap-3 bg-slate-50 border border-slate-200 rounded-2xl p-2 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 transition-all">
                    
                    <!-- Tombol Attachment -->
                    <label class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 hover:border-blue-300 cursor-pointer transition">
                        <i class="fas fa-paperclip"></i>
                        <input type="file" name="attachment" class="hidden">
                    </label>

                    <!-- Textarea Chat -->
                    <textarea name="message" rows="1" required placeholder="Ketik balasan Anda di sini..." class="w-full bg-transparent border-none focus:ring-0 resize-none text-sm text-slate-700 py-2.5 max-h-32" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>

                    <!-- Tombol Kirim -->
                    <button type="submit" class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-blue-600 rounded-xl text-white hover:bg-blue-700 transition shadow-md shadow-blue-500/30">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
        @else
        <!-- Box Tiket Ditutup -->
        <div class="bg-slate-100 p-4 text-center border-t border-slate-200">
            <p class="text-sm font-bold text-slate-500"><i class="fas fa-lock mr-1"></i> Tiket ini telah ditutup dan tidak dapat dibalas kembali.</p>
        </div>
        @endif

    </div>
</div>

<script>
    // Auto-scroll chat ke paling bawah saat halaman diload
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endsection
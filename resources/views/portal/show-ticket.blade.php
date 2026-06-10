@extends('portal.layouts.app')
@section('page_title', 'Monitoring Tiket: ' . ($ticket->ticket_number ?? ''))

@section('content')
<div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-[600px]">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800"><i class="fas fa-comments text-blue-500 mr-2"></i> Log & Tindakan IT</h3>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50">
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-300 flex-shrink-0 flex items-center justify-center font-bold text-white">
                        {{ substr($ticket->requester->full_name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="bg-white p-4 rounded-2xl rounded-tl-none border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-bold text-sm text-slate-800">{{ $ticket->requester->full_name ?? 'Anda' }} (Requester)</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                            <p class="text-sm text-slate-600">{{ $ticket->description }}</p>
                        </div>
                    </div>
                </div>

                @if(isset($ticket->threads))
                    @foreach($ticket->threads as $thread)
                        @php $isIT = $thread->user->is_it_team ?? false; @endphp
                        <div class="flex gap-4 {{ $isIT ? 'flex-row-reverse' : '' }}">
                            <div class="w-10 h-10 rounded-full {{ $isIT ? 'bg-blue-600' : 'bg-slate-300' }} flex-shrink-0 flex items-center justify-center font-bold text-white shadow-md">
                                {{ substr($thread->user->full_name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1 {{ $isIT ? 'text-right' : '' }}">
                                <div class="inline-block text-left {{ $isIT ? 'bg-blue-50 border-blue-100 rounded-tr-none' : 'bg-white border-slate-200 rounded-tl-none' }} p-4 rounded-2xl border shadow-sm max-w-[90%]">
                                    <div class="flex justify-between items-center mb-2 gap-4">
                                        <span class="font-bold text-sm {{ $isIT ? 'text-blue-800' : 'text-slate-800' }}">
                                            {{ $thread->user->full_name ?? 'User' }} 
                                            @if($isIT) <i class="fas fa-check-circle text-blue-500 ml-1" title="Teknisi IT"></i> @endif
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($thread->created_at)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <p class="text-sm {{ $isIT ? 'text-blue-900' : 'text-slate-600' }}">{{ $thread->message }}</p>
                                    
                                    @if($thread->attachment)
                                        <a href="/storage/{{ $thread->attachment }}" target="_blank" class="mt-3 inline-flex items-center gap-2 text-xs font-bold text-blue-600 bg-white px-3 py-1.5 rounded-lg border border-blue-200 hover:bg-blue-50">
                                            <i class="fas fa-paperclip"></i> Lihat Lampiran Evidence
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="p-4 border-t border-slate-200 bg-white">
                <form action="{{ route('portal.reply-ticket', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-3">
                    @csrf
                    <input type="file" name="attachment" id="file-upload" class="hidden">
                    <label for="file-upload" class="cursor-pointer w-12 h-12 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-blue-600 transition">
                        <i class="fas fa-paperclip text-lg"></i>
                    </label>
                    <input type="text" name="message" required placeholder="Ketik balasan atau berikan informasi tambahan..." class="flex-1 rounded-xl border border-slate-300 px-4 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                    <button type="submit" class="px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition">
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
            <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-4">Status Saat Ini</h4>
            <div class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-black uppercase bg-blue-100 text-blue-600 border border-blue-200 mb-4">
                {{ $ticket->status_name ?? 'IN PROGRESS' }}
            </div>
            <p class="text-xs text-slate-500 font-medium">Teknisi: <span class="font-bold text-slate-800">{{ $ticket->technician->full_name ?? 'Menunggu Assign' }}</span></p>
        </div>

        @if(in_array($ticket->status, [4, 5])) <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 opacity-10 text-7xl"><i class="fas fa-check-double"></i></div>
            <h4 class="font-bold text-lg mb-2 relative z-10">Konfirmasi Penyelesaian</h4>
            <p class="text-xs text-emerald-100 mb-6 relative z-10">Tim IT menyatakan masalah ini telah diselesaikan. Mohon periksa dan berikan persetujuan Anda untuk menutup tiket ini.</p>
            
            <form action="{{ route('portal.approve-ticket', $ticket->id) }}" method="POST" class="relative z-10">
                @csrf
                <button type="submit" class="w-full bg-white text-emerald-700 font-black py-3 rounded-xl shadow-md hover:shadow-lg hover:bg-emerald-50 transition transform hover:-translate-y-0.5">
                    APPROVE & TUTUP TIKET
                </button>
            </form>
        </div>
        @endif
    </div>

</div>
@endsection
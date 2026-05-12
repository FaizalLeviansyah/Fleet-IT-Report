@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto pb-20">

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 animate-fade-in-up gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-slate-700">
                <i class="fa-solid fa-ticket text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $ticket->ticket_number }}</h1>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-1">Home / Assistance / Tickets / Detail</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if(!in_array($ticket->status, ['Solved', 'Withdrawn']))
                <form action="{{ route('tickets.status.update', $ticket->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menarik / membatalkan tiket ini?')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="Withdrawn">
                    <button type="submit" class="px-4 py-2 bg-white text-slate-600 border-2 border-slate-300 hover:bg-slate-100 rounded-lg text-[11px] font-black uppercase transition-all shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-ban"></i> Withdraw Ticket
                    </button>
                </form>
            @endif
            <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-slate-800 text-white border-2 border-slate-900 rounded-lg text-[11px] font-black uppercase hover:bg-slate-700 transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-t-2xl shadow-sm p-4 animate-fade-in-up flex items-center gap-4" style="animation-delay: 0.1s;">
        <div class="flex items-center gap-2">
            @if($ticket->status === 'New')
                <div class="w-4 h-4 rounded-full bg-emerald-500"></div> <span class="font-black text-emerald-600 text-sm uppercase tracking-widest">NEW</span>
            @elseif($ticket->status === 'Processing')
                <div class="w-4 h-4 rounded-full border-4 border-emerald-500"></div> <span class="font-black text-emerald-600 text-sm uppercase tracking-widest">PROCESSING</span>
            @elseif($ticket->status === 'Solved')
                <div class="w-4 h-4 rounded-full bg-slate-400"></div> <span class="font-black text-slate-500 text-sm uppercase tracking-widest">SOLVED (CLOSED)</span>
            @else
                <div class="w-4 h-4 rounded-full bg-red-400"></div> <span class="font-black text-red-500 text-sm uppercase tracking-widest">WITHDRAWN</span>
            @endif
        </div>
        <div class="h-6 w-px bg-slate-300"></div>
        <h2 class="text-lg font-bold text-slate-800 truncate">{{ $ticket->title }}</h2>
    </div>

    <div class="flex flex-col lg:flex-row gap-0 bg-white border-x-2 border-b-2 border-slate-300 rounded-b-2xl shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">

        <div class="w-full lg:w-8/12 p-6 lg:border-r-2 border-slate-200 bg-slate-50 relative">

            <div class="space-y-6 pb-24">
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-lg border border-emerald-200 shrink-0">
                        {{ substr($ticket->requester->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 bg-emerald-50/50 border border-emerald-200 p-4 rounded-xl shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div class="text-[10px] font-bold text-slate-500"><i class="fa-solid fa-clock mr-1"></i> Created: {{ $ticket->created_at->diffForHumans() }} by <span class="text-slate-800">{{ $ticket->requester->name ?? 'System' }}</span></div>
                            <span class="px-2 py-0.5 bg-white border border-emerald-200 text-emerald-700 text-[9px] font-black uppercase rounded">Original Request</span>
                        </div>
                        <h3 class="font-bold text-emerald-900 mb-2">{{ $ticket->title }}</h3>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $ticket->description }}</p>
                    </div>
                </div>

                @foreach($ticket->threads as $thread)
                <div class="flex gap-4">
                    @php
                        // Styling based on Answer Type (GLPI Logic)
                        $bgColor = 'bg-white'; $borderColor = 'border-slate-200'; $iconColor = 'text-slate-500'; $icon = 'fa-comment';
                        if($thread->type === 'Task') { $bgColor = 'bg-amber-50/50'; $borderColor = 'border-amber-200'; $iconColor = 'text-amber-600'; $icon = 'fa-list-check'; }
                        if($thread->type === 'Solution') { $bgColor = 'bg-blue-50'; $borderColor = 'border-blue-300'; $iconColor = 'text-blue-600'; $icon = 'fa-check-double'; }
                    @endphp

                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center font-black text-lg border {{ $borderColor }} {{ $iconColor }} shrink-0 shadow-sm z-10">
                        <i class="fa-solid {{ $icon }} text-sm"></i>
                    </div>

                    <div class="flex-1 {{ $bgColor }} border {{ $borderColor }} p-4 rounded-xl shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div class="text-[10px] font-bold text-slate-500"><i class="fa-solid fa-clock mr-1"></i> {{ $thread->created_at->diffForHumans() }} by <span class="text-slate-800">{{ $thread->user->name ?? 'System' }}</span></div>
                            <span class="px-2 py-0.5 bg-white border {{ $borderColor }} {{ $iconColor }} text-[9px] font-black uppercase rounded">{{ $thread->type }}</span>
                        </div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $thread->content }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            @if(!in_array($ticket->status, ['Solved', 'Withdrawn']))
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-slate-100 border-t-2 border-slate-200 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)]">
                <form action="{{ route('tickets.thread.store', $ticket->id) }}" method="POST" id="answerForm" class="flex flex-col gap-2 relative">
                    @csrf
                    <input type="hidden" name="type" id="threadType" value="Reply">

                    <textarea name="content" rows="2" required class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Ketik balasan Anda di sini..."></textarea>

                    <div class="flex justify-between items-center">
                        <div class="relative inline-block text-left" id="dropdownMenu">
                            <button type="button" onclick="toggleDropdown()" id="btnActionType" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-black text-[11px] uppercase tracking-widest rounded-lg transition-colors border border-slate-300 flex items-center gap-2">
                                <i class="fa-solid fa-comment"></i> Add a Reply <i class="fa-solid fa-chevron-up ml-1"></i>
                            </button>
                            <div id="actionDropdown" class="hidden origin-bottom-left absolute left-0 bottom-full mb-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-slate-100 z-50">
                                <a href="#" onclick="selectType('Reply', 'bg-slate-200 text-slate-700', 'fa-comment')" class="group flex items-center px-4 py-3 text-xs font-bold text-slate-700 hover:bg-slate-100"><i class="fa-solid fa-comment w-5 text-slate-400 group-hover:text-slate-600"></i> Add a Reply</a>
                                <a href="#" onclick="selectType('Task', 'bg-amber-100 text-amber-800 border-amber-300', 'fa-list-check')" class="group flex items-center px-4 py-3 text-xs font-bold text-slate-700 hover:bg-amber-50"><i class="fa-solid fa-list-check w-5 text-amber-500"></i> Create a Task</a>
                                <a href="#" onclick="selectType('Document', 'bg-emerald-100 text-emerald-800 border-emerald-300', 'fa-file-lines')" class="group flex items-center px-4 py-3 text-xs font-bold text-slate-700 hover:bg-emerald-50"><i class="fa-solid fa-file-lines w-5 text-emerald-500"></i> Add a Document</a>
                                <a href="#" onclick="selectType('Solution', 'bg-blue-600 text-white border-blue-800 shadow-md', 'fa-check-double')" class="group flex items-center px-4 py-3 text-xs font-bold text-blue-700 hover:bg-blue-50 bg-blue-50/50"><i class="fa-solid fa-check-double w-5 text-blue-600"></i> Add a Solution (Close)</a>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit" class="px-6 py-2 bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Send
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-slate-200 border-t-2 border-slate-300 text-center text-slate-500 font-bold text-xs uppercase tracking-widest">
                <i class="fa-solid fa-lock mr-1"></i> Tiket ini telah ditutup / ditarik.
            </div>
            @endif

        </div>

        <div class="w-full lg:w-4/12 bg-white p-0">
            <div class="border-b border-slate-200">
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-users"></i> Actors
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Requester</div>
                        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 p-2 rounded-lg">
                            <i class="fa-solid fa-user text-slate-400"></i> <span class="text-xs font-bold text-slate-700">{{ $ticket->requester->name ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Assigned To (Technician)</div>
                        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 p-2 rounded-lg">
                            <i class="fa-solid fa-user-gear {{ $ticket->assigned_to ? 'text-blue-500' : 'text-slate-300' }}"></i>
                            <span class="text-xs font-bold {{ $ticket->assigned_to ? 'text-blue-700' : 'text-slate-400 italic' }}">{{ $ticket->assignee->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-slate-200">
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-circle-info"></i> Properties
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Category</div>
                        <div class="text-xs font-bold text-slate-700">{{ $ticket->category }}</div>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Priority</div>
                        <span class="text-xs font-black uppercase tracking-widest {{ $ticket->priority === 'High' ? 'text-red-500' : 'text-amber-500' }}">{{ $ticket->priority }}</span>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Linked Asset</div>
                        @if($ticket->asset)
                            <a href="#" class="text-xs font-bold text-blue-600 hover:underline"><i class="fa-solid fa-desktop text-slate-400 mr-1"></i> {{ $ticket->asset->asset_name }}</a>
                        @else
                            <span class="text-xs text-slate-400 italic">No asset linked</span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-stopwatch"></i> Timing & SLA
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                        <span class="text-[10px] font-bold text-slate-500">Opening Date</span>
                        <span class="text-[10px] font-black text-slate-700">{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                        <span class="text-[10px] font-bold text-slate-500">Last Update</span>
                        <span class="text-[10px] font-black text-slate-700">{{ $ticket->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-500">Resolution Date</span>
                        <span class="text-[10px] font-black {{ $ticket->resolved_at ? 'text-emerald-600' : 'text-slate-400 italic' }}">{{ $ticket->resolved_at ? \Carbon\Carbon::parse($ticket->resolved_at)->format('Y-m-d H:i') : 'Pending...' }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // JS Logic untuk dropdown Answer ala GLPI
    function toggleDropdown() {
        document.getElementById('actionDropdown').classList.toggle('hidden');
    }

    // Menutup dropdown jika klik di luar
    window.onclick = function(event) {
        if (!event.target.closest('#dropdownMenu')) {
            document.getElementById('actionDropdown').classList.add('hidden');
        }
    }

    function selectType(type, btnClasses, icon) {
        // Update hidden input
        document.getElementById('threadType').value = type;

        // Update Button UI
        const btn = document.getElementById('btnActionType');
        const submitBtn = document.getElementById('btnSubmit');

        // Reset classes
        btn.className = `px-4 py-2 font-black text-[11px] uppercase tracking-widest rounded-lg transition-colors border flex items-center gap-2 ${btnClasses}`;
        btn.innerHTML = `<i class="fa-solid ${icon}"></i> Add a ${type} <i class="fa-solid fa-chevron-up ml-1"></i>`;

        // Ganti warna tombol send jika Solution
        if(type === 'Solution') {
            submitBtn.className = 'px-6 py-2 bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2';
            submitBtn.innerHTML = '<i class="fa-solid fa-check-double"></i> Submit & Close Ticket';
        } else if(type === 'Task') {
            submitBtn.className = 'px-6 py-2 bg-amber-500 text-white font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-amber-600 transition-colors shadow-sm flex items-center gap-2';
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Task';
        } else {
            submitBtn.className = 'px-6 py-2 bg-slate-700 text-white font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-slate-800 transition-colors shadow-sm flex items-center gap-2';
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Reply';
        }

        toggleDropdown();
    }
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection

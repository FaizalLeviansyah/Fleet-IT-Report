@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto pb-20">

    {{-- <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 animate-fade-in-up gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-slate-700">
                <i class="fa-solid fa-life-ring text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tickets</h1>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-1">Home / Assistance / Tickets</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button class="px-5 py-2.5 bg-slate-800 text-white border-2 border-slate-900 rounded-xl text-[11px] font-black uppercase hover:bg-slate-700 transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Create Ticket
            </button>
        </div>
    </div> --}}

    <div class="bg-white border-2 border-slate-300 rounded-t-2xl shadow-sm p-4 animate-fade-in-up flex flex-wrap gap-3 items-center" style="animation-delay: 0.1s;">
        <button class="px-3 py-1.5 border border-slate-300 rounded text-xs font-bold text-slate-600 hover:bg-slate-50 flex items-center gap-2"><i class="fa-solid fa-filter"></i> Characteristics - Status</button>
        <span class="text-xs font-bold text-slate-400">is</span>
        <button class="px-3 py-1.5 border border-slate-300 rounded text-xs font-bold text-slate-600 hover:bg-slate-50">Not Solved <i class="fa-solid fa-caret-down ml-1"></i></button>
        <button class="px-4 py-1.5 bg-amber-400 text-amber-900 rounded font-black text-xs uppercase tracking-wider hover:bg-amber-500 transition-colors shadow-sm ml-2"><i class="fa-solid fa-magnifying-glass mr-1"></i> Search</button>
    </div>

    <div class="bg-white border-x-2 border-b-2 border-slate-300 rounded-b-2xl shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">

        <div class="p-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div class="flex items-center gap-3 pl-2">
                <button class="px-3 py-1.5 bg-white border border-slate-300 rounded shadow-sm text-xs font-bold text-slate-600 flex items-center gap-2 hover:bg-slate-50"><i class="fa-solid fa-wrench"></i> Actions <i class="fa-solid fa-caret-down"></i></button>
                <div class="h-6 w-px bg-slate-300 mx-1"></div>
                <button class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can"></i></button>
            </div>
            <div class="text-xs font-bold text-slate-500 pr-2">
                Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} rows
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar min-h-[500px]">
            <table class="w-full text-[11px] text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-500 uppercase bg-slate-100 border-b-2 border-slate-300">
                    <tr>
                        <th class="px-4 py-3 font-black text-center w-10">
                            <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 font-black">ID</th>
                        <th class="px-4 py-3 font-black w-1/4">Title</th>
                        <th class="px-4 py-3 font-black">Status</th>
                        <th class="px-4 py-3 font-black">Last Update <i class="fa-solid fa-sort-down ml-1"></i></th>
                        <th class="px-4 py-3 font-black">Opening Date</th>
                        <th class="px-4 py-3 font-black">Priority</th>
                        <th class="px-4 py-3 font-black">Requester</th>
                        <th class="px-4 py-3 font-black">Assigned To</th>
                        <th class="px-4 py-3 font-black">Category</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($tickets as $ticket)
                    <tr class="hover:bg-blue-50/50 transition-colors group cursor-pointer relative" onclick="window.location.href='{{ route('tickets.show', $ticket->id) }}'">
                        <td class="px-4 py-3 text-center" onclick="event.stopPropagation();">
                            <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-4 py-3 font-mono text-slate-500 font-bold">{{ $ticket->ticket_number ?? $ticket->id }}</td>

                        <td class="px-4 py-3 group/tooltip relative">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline truncate block max-w-xs">
                                {{ $ticket->title }}
                            </a>

                            <div class="hidden group-hover/tooltip:block absolute left-1/4 top-full mt-1 z-50 w-80 bg-white border border-slate-200 shadow-xl rounded-lg p-4 text-xs text-slate-700 whitespace-normal cursor-default" onclick="event.stopPropagation();">
                                {{ \Illuminate\Support\Str::limit($ticket->description, 200) }}
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5 font-bold text-slate-700">
                                @if($ticket->status === 'New')
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div> New
                                @elseif($ticket->status === 'Processing')
                                    <div class="w-2.5 h-2.5 rounded-full border-2 border-emerald-500 bg-transparent"></div> Processing (assigned)
                                @elseif($ticket->status === 'Solved')
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-400"></div> Solved
                                @else
                                    <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div> Withdrawn
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $ticket->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">
                            @if($ticket->priority === 'High' || $ticket->priority === 'Critical')
                                <span class="px-2 py-0.5 border border-red-200 bg-red-50 text-red-600 font-black rounded">{{ $ticket->priority }}</span>
                            @elseif($ticket->priority === 'Medium')
                                <span class="px-2 py-0.5 border border-pink-200 bg-pink-50 text-pink-600 font-black rounded">{{ $ticket->priority }}</span>
                            @else
                                <span class="px-2 py-0.5 border border-slate-200 bg-slate-50 text-slate-600 font-black rounded">{{ $ticket->priority }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-blue-800 font-bold hover:underline">{{ $ticket->requester->name ?? 'System' }}</span> <i class="fa-solid fa-circle-info text-blue-400 ml-1"></i>
                        </td>
                        <td class="px-4 py-3">
                            @if($ticket->assigned_to)
                                <span class="text-blue-800 font-bold hover:underline">{{ $ticket->assignee->name }}</span> <i class="fa-solid fa-circle-info text-blue-400 ml-1"></i>
                            @else
                                <span class="text-slate-400 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $ticket->category }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-16 text-center">
                            <div class="text-slate-400 mb-2"><i class="fa-solid fa-ticket text-4xl"></i></div>
                            <div class="text-slate-500 font-bold text-sm">Belum ada tiket insiden yang dibuat.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-white flex justify-end">
            {{ $tickets->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection

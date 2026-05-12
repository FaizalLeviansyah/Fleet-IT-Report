@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto pb-20">

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 animate-fade-in-up gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-slate-700">
                <i class="fa-solid fa-life-ring text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Create Ticket</h1>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-1">Home / Assistance / Tickets / Create</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-slate-800 text-white border-2 border-slate-900 rounded-lg text-[11px] font-black uppercase hover:bg-slate-700 transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-0 bg-white border-2 border-slate-300 rounded-2xl shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
        @csrf

        <div class="w-full lg:w-8/12 p-6 lg:border-r-2 border-slate-200 bg-slate-50">
            <div class="bg-white border-2 border-slate-200 p-6 rounded-xl shadow-sm">
                <div class="p-3 bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-200 text-xs font-bold mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-ticket"></i> Tickets will be added in entity: <span class="text-emerald-900 font-black">Root Entity</span>
                </div>

                <div class="space-y-6 pb-24">
                    <div>
                        <label for="title" class="block text-xs font-bold text-emerald-900 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required class="w-full text-sm rounded-lg border-emerald-300 focus:border-emerald-500 focus:ring-emerald-500 font-bold text-emerald-900" placeholder="Ketik judul tiket Anda di sini...">
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-emerald-900 mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="10" required class="w-full text-sm rounded-lg border-emerald-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" placeholder="Ketik deskripsi masalah Anda secara detail..."></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-emerald-50/50 border border-emerald-200 p-4 rounded-xl shadow-sm mt-6">
                <p class="text-[10px] text-emerald-600 font-bold leading-tight mb-2"><i class="fa-solid fa-circle-info"></i> <b>Lampiran / Attachment (Max: 2MB)</b></p>
                <input type="file" name="attachment" id="attachment" class="block w-full text-xs text-emerald-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 transition-all cursor-pointer">
            </div>
        </div>

        <div class="w-full lg:w-4/12 bg-white p-0">
            <div class="border-b border-slate-200">
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2 justify-between">
                    <span><i class="fa-solid fa-ticket"></i> Tickets</span>
                    <i class="fa-solid fa-chevron-down text-slate-400"></i>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label for="status" class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" id="status" required class="w-full text-xs font-bold text-emerald-600 bg-emerald-50 rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="New">New</option>
                            <option value="Processing">Processing</option>
                            <option value="Solved">Solved</option>
                            <option value="Withdrawn">Withdrawn</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-b border-slate-200">
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2 justify-between">
                    <span><i class="fa-solid fa-users"></i> Actors</span>
                    <i class="fa-solid fa-chevron-up text-slate-400"></i>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label for="requester_id" class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Requester <span class="text-red-500">*</span></label>
                        <select name="requester_id" id="requester_id" required class="w-full text-xs font-bold text-blue-800 bg-blue-50/50 rounded-lg border-blue-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Peminta --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="observer_id" class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Observer</label>
                        <select name="observer_id" id="observer_id" class="w-full text-xs font-bold text-blue-800 bg-blue-50/50 rounded-lg border-blue-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Pengamat --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="assigned_to" class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Assigned To (Technician)</label>
                        <select name="assigned_to" id="assigned_to" class="w-full text-xs font-bold text-blue-800 bg-blue-50/50 rounded-lg border-blue-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-b border-slate-200">
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2 justify-between">
                    <span><i class="fa-solid fa-circle-info"></i> Properties</span>
                    <i class="fa-solid fa-chevron-up text-slate-400"></i>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label for="priority" class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" id="priority" required class="w-full text-xs font-black uppercase tracking-widest bg-slate-50 rounded-lg border-slate-200 focus:border-slate-500 focus:ring-slate-500 text-amber-500">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Category <span class="text-red-500">*</span></label>
                        <input type="text" name="category" id="category" required class="w-full text-xs font-bold text-slate-700 rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Operation > Vessel">
                    </div>
                    <div>
                        <label for="asset_id" class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Linked Asset</label>
                        <select name="asset_id" id="asset_id" class="w-full text-xs font-bold text-slate-700 bg-slate-50 rounded-lg border-slate-200 focus:border-slate-500 focus:ring-slate-500">
                            <option value="">-- Pilih Aset --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->asset_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-slate-100 border-t-2 border-slate-200 text-right shrink-0">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Submit Ticket
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

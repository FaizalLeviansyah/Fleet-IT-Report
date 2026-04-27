@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="flex items-center justify-between mb-6 animate-fade-in-up">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Laporan Kinerja IT</h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Weekly Report Individu / Personel IT</p>
            </div>
        </div>
        <button onclick="openPersonalModal()" class="px-6 py-2.5 bg-blue-600 text-white font-black text-xs uppercase tracking-widest rounded-xl border-2 border-blue-800 hover:bg-blue-700 hover:scale-105 shadow-md transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg> Buat Laporan Baru
        </button>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto min-h-[400px] custom-scrollbar">
            <table class="w-full text-xs text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-800 uppercase bg-slate-200 border-b-2 border-slate-400">
                    <tr>
                        <th class="px-6 py-4 font-black w-10">No</th>
                        <th class="px-6 py-4 font-black">Periode Laporan</th>
                        <th class="px-6 py-4 font-black text-center">Status</th>
                        <th class="px-6 py-4 font-black text-center">Total Pekerjaan</th>
                        <th class="px-6 py-4 font-black text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse ($reports as $index => $report)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 font-black text-slate-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-black text-blue-800 text-sm">{{ \Carbon\Carbon::parse($report->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($report->end_date)->format('d M Y') }}</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase">Dibuat: {{ $report->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($report->status == 1)
                                <span class="px-2.5 py-1 rounded text-[10px] font-black border-2 bg-orange-100 text-orange-800 border-orange-300">DRAFT</span>
                            @else
                                <span class="px-2.5 py-1 rounded text-[10px] font-black border-2 bg-emerald-100 text-emerald-800 border-emerald-300">FINAL</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-xs font-bold text-slate-600"><span class="text-blue-600 font-black">{{ $report->actualTasks->count() }}</span> Aktual / <span class="text-amber-600 font-black">{{ $report->plannedTasks->count() }}</span> Plan</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="px-4 py-2 bg-slate-100 text-slate-600 border border-slate-300 rounded font-bold hover:bg-slate-200 transition-colors">Lihat Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500 font-bold">Belum ada riwayat laporan kinerja.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="personalModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-screen max-h-full bg-slate-900/60 backdrop-blur-md">
    <div class="relative p-4 w-full max-w-6xl max-h-full mx-auto">
        <div class="relative bg-white rounded-2xl shadow-2xl flex flex-col w-full max-h-[90vh] border-2 border-slate-300 overflow-hidden">

            <div class="flex items-center justify-between p-4 md:p-5 border-b-2 border-slate-200 bg-slate-50 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Form Laporan Kinerja Personel</h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">IT Support: <span class="text-blue-700 bg-blue-100 px-2 py-0.5 rounded border border-blue-200">{{ strtoupper(Auth::user()->full_name ?? Auth::user()->name) }}</span></p>
                    </div>
                </div>
                <button type="button" onclick="closePersonalModal()" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 hover:ring-2 hover:ring-red-200 transition-all rounded-lg text-sm w-8 h-8 ms-auto flex justify-center items-center border border-slate-200 shadow-sm">
                    <svg class="w-3 h-3 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-[#f4f7fb] custom-scrollbar min-h-0">
                <form id="personalForm" action="{{ route('personal.reports.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden p-4 grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 cursor-not-allowed">
                        <div>
                            <label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Periode Mulai (Senin)</label>
                            <input type="date" name="start_date" id="start_date" readonly class="w-full rounded-lg border-2 border-slate-200 text-sm font-bold bg-slate-100 text-slate-500 cursor-not-allowed pointer-events-none">
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Periode Selesai (Jumat)</label>
                            <input type="date" name="end_date" id="end_date" readonly class="w-full rounded-lg border-2 border-slate-200 text-sm font-bold bg-slate-100 text-slate-500 cursor-not-allowed pointer-events-none">
                        </div>
                    </div>

                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-blue-500">
                        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                            <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">ACTUAL PEKERJAAN MINGGU INI</h2>
                            <button type="button" onclick="addActualRow()" class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-[10px] font-black uppercase hover:bg-blue-700 shadow-sm">+ Tambah Baris</button>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <table class="w-full text-xs text-left whitespace-nowrap" id="actual-table">
                                <thead class="text-[10px] text-slate-600 uppercase bg-slate-100 border-b border-slate-300">
                                    <tr>
                                        <th class="px-2 py-2 font-black w-32">Tanggal</th>
                                        <th class="px-2 py-2 font-black">Pekerjaan</th>
                                        <th class="px-2 py-2 font-black">Hasil Singkat</th>
                                        <th class="px-2 py-2 font-black w-32">Status</th>
                                        <th class="px-2 py-2 font-black">Catatan</th>
                                        <th class="px-2 py-2 font-black w-10 text-center">X</th>
                                    </tr>
                                </thead>
                                <tbody id="actual-body">
                                    </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-emerald-500">
                        <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                            <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">PLANNING MINGGU DEPAN</h2>
                            <button type="button" onclick="addPlannedRow()" class="px-4 py-1.5 bg-emerald-600 text-white rounded-lg text-[10px] font-black uppercase hover:bg-emerald-700 shadow-sm">+ Tambah Rencana</button>
                        </div>
                        <div class="p-4 overflow-x-auto">
                            <table class="w-full text-xs text-left whitespace-nowrap" id="planned-table">
                                <thead class="text-[10px] text-slate-600 uppercase bg-slate-100 border-b border-slate-300">
                                    <tr>
                                        <th class="px-2 py-2 font-black">Rencana Pekerjaan</th>
                                        <th class="px-2 py-2 font-black">Target</th>
                                        <th class="px-2 py-2 font-black w-32">Prioritas</th>
                                        <th class="px-2 py-2 font-black w-32">Deadline</th>
                                        <th class="px-2 py-2 font-black">Catatan</th>
                                        <th class="px-2 py-2 font-black w-10 text-center">X</th>
                                    </tr>
                                </thead>
                                <tbody id="planned-body">
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-end gap-3 p-4 md:p-5 border-t-2 border-slate-200 bg-white rounded-b-xl shrink-0">
                <button type="submit" form="personalForm" name="action_type" value="draft" class="px-6 py-3 bg-orange-50 text-orange-700 border-2 border-orange-300 hover:bg-orange-100 hover:ring-4 hover:ring-orange-200 hover:scale-105 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm">
                    Simpan Draft
                </button>
                <button type="submit" id="btn-submit-personal" form="personalForm" name="action_type" value="final" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white border-2 border-blue-800 hover:bg-blue-700 hover:ring-4 hover:ring-blue-200 hover:scale-105 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>SUBMIT LAPORAN
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.body.appendChild(document.getElementById('personalModal'));
    });

    // Fungsi untuk mendapatkan tanggal lokal (WIB/Indonesia)
    function getLocalToday() {
        const date = new Date();
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        return date.toISOString().split('T')[0];
    }

    function openPersonalModal() {
        document.getElementById('personalModal').classList.remove('hidden');
        document.getElementById('personalModal').classList.add('flex');

        let curr = new Date();
        let first = curr.getDate() - curr.getDay() + 1; // Senin
        let last = first + 4; // Jumat
        document.getElementById('start_date').value = new Date(curr.setDate(first)).toISOString().split('T')[0];
        document.getElementById('end_date').value = new Date(curr.setDate(last)).toISOString().split('T')[0];

        // LOGIKA STRICT: MENCEGAH SUBMIT SEBELUM JUMAT
        const todayDay = new Date().getDay(); // 0=Minggu, 1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu
        const submitBtn = document.getElementById('btn-submit-personal');

        if (todayDay >= 1 && todayDay <= 4) {
            // Jika Senin - Kamis, ubah tombol menjadi tombol peringatan!
            submitBtn.type = 'button'; // Matikan fungsi submit bawaan
            submitBtn.classList.replace('bg-blue-600', 'bg-slate-400');
            submitBtn.classList.replace('border-blue-800', 'border-slate-500');
            submitBtn.classList.remove('hover:bg-blue-700', 'hover:scale-105');

            submitBtn.onclick = function() {
                Swal.fire({
                    title: 'Sistem Terkunci!',
                    text: 'Pengiriman laporan Final hanya dibuka pada hari Jumat. Anda harus menggunakan tombol "Simpan Draft" untuk mencicil pekerjaan hari ini.',
                    icon: 'warning',
                    confirmButtonColor: '#f97316',
                    confirmButtonText: 'Baik, Saya Akan Nyicil Draft',
                    customClass: { popup: 'border-2 border-slate-300 rounded-2xl shadow-xl' }
                });
            };
        } else {
            // Jika Jumat, Sabtu, atau Minggu, kembalikan tombol normal
            submitBtn.type = 'submit';
            submitBtn.onclick = null;
        }

        document.getElementById('actual-body').innerHTML = '';
        document.getElementById('planned-body').innerHTML = '';
        addActualRow();
        addPlannedRow();
    }

    function closePersonalModal() {
        document.getElementById('personalModal').classList.add('hidden');
        document.getElementById('personalModal').classList.remove('flex');
    }

    function addActualRow() {
        const tbody = document.getElementById('actual-body');
        const tr = document.createElement('tr');
        // Fitur Pintar: Value tanggal langsung diisi dengan getLocalToday()
        tr.innerHTML = `
            <td class="p-1"><input type="date" name="actual_date[]" value="${getLocalToday()}" required class="w-full rounded border-slate-300 text-xs font-bold focus:border-blue-600 focus:ring-1 focus:ring-blue-100"></td>
            <td class="p-1"><input type="text" name="actual_task[]" placeholder="Update Server..." required class="w-full rounded border-slate-300 text-xs font-bold focus:border-blue-600 focus:ring-1 focus:ring-blue-100"></td>
            <td class="p-1"><input type="text" name="actual_result[]" placeholder="Patch berhasil..." class="w-full rounded border-slate-300 text-xs font-bold focus:border-blue-600 focus:ring-1 focus:ring-blue-100"></td>
            <td class="p-1">
                <select name="actual_status[]" class="w-full rounded border-slate-300 text-xs font-bold focus:border-blue-600 focus:ring-1 focus:ring-blue-100">
                    <option value="Selesai">Selesai</option>
                    <option value="Pending">Pending</option>
                    <option value="On Progress">On Progress</option>
                </select>
            </td>
            <td class="p-1"><input type="text" name="actual_notes[]" placeholder="-" class="w-full rounded border-slate-300 text-xs font-bold focus:border-blue-600 focus:ring-1 focus:ring-blue-100"></td>
            <td class="p-1 text-center"><button type="button" onclick="this.closest('tr').remove()" class="p-1 bg-red-100 text-red-600 hover:bg-red-200 rounded text-xs font-black">X</button></td>
        `;
        tbody.appendChild(tr);
    }

    function addPlannedRow() {
        const tbody = document.getElementById('planned-body');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="p-1"><input type="text" name="planned_task[]" placeholder="Audit Jaringan..." required class="w-full rounded border-slate-300 text-xs font-bold focus:border-emerald-600 focus:ring-1 focus:ring-emerald-100"></td>
            <td class="p-1"><input type="text" name="planned_target[]" placeholder="100% Cek..." class="w-full rounded border-slate-300 text-xs font-bold focus:border-emerald-600 focus:ring-1 focus:ring-emerald-100"></td>
            <td class="p-1">
                <select name="planned_priority[]" class="w-full rounded border-slate-300 text-xs font-bold focus:border-emerald-600 focus:ring-1 focus:ring-emerald-100">
                    <option value="Tinggi">Tinggi</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Rendah">Rendah</option>
                </select>
            </td>
            <td class="p-1"><input type="date" name="planned_deadline[]" class="w-full rounded border-slate-300 text-xs font-bold focus:border-emerald-600 focus:ring-1 focus:ring-emerald-100"></td>
            <td class="p-1"><input type="text" name="planned_notes[]" placeholder="-" class="w-full rounded border-slate-300 text-xs font-bold focus:border-emerald-600 focus:ring-1 focus:ring-emerald-100"></td>
            <td class="p-1 text-center"><button type="button" onclick="this.closest('tr').remove()" class="p-1 bg-red-100 text-red-600 hover:bg-red-200 rounded text-xs font-black">X</button></td>
        `;
        tbody.appendChild(tr);
    }
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection

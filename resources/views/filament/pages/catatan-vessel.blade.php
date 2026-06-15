<x-filament-panels::page>
    <!-- MENGGUNAKAN SECTION ASLI FILAMENT -->
    <x-filament::section>

        <!-- JUDUL & DESKRIPSI -->
        <x-slot name="heading">
            Catatan Tambahan
        </x-slot>

        <x-slot name="description">
            Input notes untuk lampiran PDF
        </x-slot>

        <!-- DROPDOWN DI POJOK KANAN ATAS -->
        <x-slot name="headerEnd">
            <div class="w-72">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="selectedVessel">
                        <option value="">-- Pilih Armada Kapal --</option>
                        @foreach($vessels as $vessel)
                            <option value="{{ $vessel }}">{{ $vessel }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </x-slot>

        <!-- TABEL NATIVE FILAMENT STYLE -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-white/10">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white font-semibold border-b border-gray-200 dark:border-white/10">
                    <tr>
                        <th class="px-4 py-4">Waktu Laporan</th>
                        <th class="px-4 py-4">Isi Laporan Asli</th>
                        <th class="px-4 py-4">Catatan Tambahan (PDF)</th>
                        <th class="px-4 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/10 bg-white dark:bg-gray-900">
                    @forelse($laporans as $laporan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-75" wire:key="row-{{ $laporan->id }}">

                            <!-- TANGGAL -->
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $laporan->waktu_kejadian ? $laporan->waktu_kejadian->format('Y-m-d H:i:s') : '-' }}
                            </td>

                            <!-- LAPORAN ASLI -->
                            <td class="px-4 py-4 text-gray-500 dark:text-gray-400 italic">
                                {{ $laporan->isi_laporan }}
                            </td>

                            <!-- TEXTAREA STANDAR FILAMENT -->
                            <td class="px-4 py-4 w-1/2">
                                <textarea
                                    wire:model="notes.{{ $laporan->id }}"
                                    rows="2"
                                    class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-primary-500 transition duration-75 resize-y"
                                    placeholder="Ketik catatan di sini..."></textarea>
                            </td>

                            <!-- TOMBOL STANDAR FILAMENT -->
                            <td class="px-4 py-4 text-right align-middle">
                                <x-filament::button
                                    wire:click="saveNote({{ $laporan->id }})"
                                    size="sm"
                                    icon="heroicon-m-check">
                                    <span wire:loading.remove wire:target="saveNote({{ $laporan->id }})">Simpan</span>
                                    <span wire:loading wire:target="saveNote({{ $laporan->id }})">Loading..</span>
                                </x-filament::button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                🚢 Tidak ada data laporan untuk armada ini (atau armada belum dipilih).
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </x-filament::section>
</x-filament-panels::page>

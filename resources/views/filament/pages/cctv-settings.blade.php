<x-filament-panels::page>
    <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold dark:text-white">Daftar Konfigurasi IP Camera</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Atur RTSP Link, IP Address, dan kredensial login kamera.</p>
            </div>
            <button class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2 rounded-lg font-medium">
                + Tambah Kamera
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="p-4 font-medium dark:text-gray-300">Nama Kamera</th>
                        <th class="p-4 font-medium dark:text-gray-300">IP Address</th>
                        <th class="p-4 font-medium dark:text-gray-300">Port (RTSP)</th>
                        <th class="p-4 font-medium dark:text-gray-300">Status</th>
                        <th class="p-4 font-medium dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-gray-300">
                    <tr>
                        <td class="p-4 font-bold">CH-01 Haluan</td>
                        <td class="p-4">192.168.100.11</td>
                        <td class="p-4">554</td>
                        <td class="p-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Online</span></td>
                        <td class="p-4 text-blue-500 cursor-pointer hover:underline">Edit</td>
                    </tr>
                    <tr>
                        <td class="p-4 font-bold">CH-02 Buritan</td>
                        <td class="p-4">192.168.100.12</td>
                        <td class="p-4">554</td>
                        <td class="p-4"><span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Offline</span></td>
                        <td class="p-4 text-blue-500 cursor-pointer hover:underline">Edit</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>

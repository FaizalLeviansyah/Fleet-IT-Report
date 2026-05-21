<x-filament-panels::page>
    <div class="w-full bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-8">

        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Help / SOP</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Quick operational guide for login, ticketing, KPI, and support contact.</p>
        </div>

        <div class="relative w-full mb-8">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none" placeholder="Search help topics...">
        </div>

        <div class="space-y-4">
            <div class="group border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-blue-400 hover:shadow-md transition-all cursor-pointer bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">Login & Account Access</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">How to sign in and manage your account</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>

            <div class="group border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-green-400 hover:shadow-md transition-all cursor-pointer bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-green-600 transition-colors">IT Incident Ticketing</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">How to submit and track your IT issues</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>

            <div class="group border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-red-400 hover:shadow-md transition-all cursor-pointer bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.36 6.64a9 9 0 11-12.73 0M12 2v10"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-red-600 transition-colors">Contact HR/IT Support</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">How to get help when you need it urgently</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

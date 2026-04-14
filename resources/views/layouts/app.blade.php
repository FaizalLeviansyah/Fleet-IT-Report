<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet IT Report</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">

    <nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <span class="text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Fleet IT Admin</span>
            </div>
        </div>
    </nav>

    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        <div class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

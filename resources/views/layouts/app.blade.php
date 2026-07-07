<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L510 TimeKeeper</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #1a1a1a;
        }
    </style>
</head>
<body>
    <nav class="bg-[#0f0f0f] text-white p-4 shadow-lg border-b border-gray-700">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-semibold tracking-tight">L510 TimeKeeper</h1>
            <p class="text-xs text-gray-400 mt-1">Time Tracking System</p>
        </div>
    </nav>

    <main class="container mx-auto mt-6 px-4 pb-8">
        @if(session('success'))
            <div class="bg-gray-800 border border-gray-600 text-gray-200 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-gray-800 border border-gray-600 text-gray-200 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>

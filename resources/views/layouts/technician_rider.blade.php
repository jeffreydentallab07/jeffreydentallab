<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-[12px] h-screen flex flex-col">

    {{-- Mobile Navbar --}}
    <header class="bg-blue-900 text-white flex items-center justify-between px-4 py-3 md:hidden shadow-md">
        <button id="menuToggle" class="focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-8">
    </header>

    {{-- Sidebar --}}
    <aside id="sidebar" 
           class="fixed md:static inset-y-0 left-0 w-64 bg-blue-900 text-white flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">
        <div class="h-20 px-3 border-b border-blue-700 flex items-center justify-center">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-12 object-contain">
        </div>
        {{-- Add your sidebar links here --}}
        <nav class="flex-1 p-3 space-y-2 text-sm">
            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-800">Dashboard</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-800">Appointments</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-800">Reports</a>
        </nav>
    </aside>

    {{-- Overlay for mobile --}}
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden md:hidden z-40"></div>

    {{-- Main Content --}}
    <div class="flex-grow flex flex-col md:ml-64">
        <header class="bg-white p-3 flex flex-wrap items-center justify-between shadow-md z-10">
            <div class="flex items-center gap-2 border border-gray-300 rounded-lg p-2 bg-white w-full sm:w-64 md:w-96">
                <svg class="w-4 h-4 text-gray-400 mr-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 
                    16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 
                    9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 
                    4.99L20.49 19l-4.99-5z"/>
                </svg>
                <input type="text" placeholder="Search..." class="outline-none w-full text-[12px]">
            </div>

            <div class="flex items-center gap-2 mt-2 sm:mt-0">
                <div class="relative group">
                    <button id="userDropdownBtn"
                            class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 text-[12px]">
                        <div class="w-6 h-6 bg-blue-600 text-white flex items-center justify-center rounded-full font-bold text-[10px]">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                    </button>
                    <div id="userDropdownMenu"
                         class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-md border hidden group-hover:block z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-left flex items-center px-3 py-2 text-red-500 hover:bg-gray-100 text-[12px]">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleBtn = document.getElementById('menuToggle');

        toggleBtn.addEventListener('click', () => {
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            sidebar.classList.toggle('-translate-x-full', isOpen);
            overlay.classList.toggle('hidden', isOpen);
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

</body>
</html>

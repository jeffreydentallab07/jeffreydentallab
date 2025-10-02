<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex bg-white text-[12px]">

   
    <aside class="w-48 bg-blue-900 text-white flex flex-col fixed top-0 left-0 h-full">
        <div class="h-20 px-3 border-b border-blue-700 flex items-center justify-center">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-12 object-contain">
        </div>

       
    </aside>

    <div class="flex-grow flex flex-col h-full ml-48">
        <header class="bg-white p-3 flex items-center justify-between shadow-md z-10">
            <div class="flex items-center gap-2 border border-gray-300 rounded-lg w-96 p-2 bg-white">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5z"/>
                </svg>
                <input type="text" placeholder="Search..." class="outline-none w-full text-[12px]">
            </div>

            <div class="flex items-center gap-2">
                <div class="relative group">
                    <button class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 text-[12px]">
                        <div class="w-6 h-6 bg-blue-600 text-white flex items-center justify-center rounded-full font-bold text-[10px]"></div>
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-md border hidden group-hover:block z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center px-3 py-2 text-red-500 hover:bg-gray-100 text-[12px]">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow overflow-y-auto">
            <div>
                @yield('content')
            </div>
        </main>
    </div>
    <script>
  
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
  
            const userBtn = document.getElementById('userDropdownBtn');
            const userMenu = document.getElementById('userDropdownMenu');
            const openSettingsBtn = document.getElementById('openClinicSettingsBtn');
            const modal = document.getElementById('clinicSettingsModal');

            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });
            </script>
</body>
</html>
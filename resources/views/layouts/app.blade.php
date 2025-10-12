<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Denture Reports')</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <body x-data class="bg-gray-50 text-[11px]">

  
    @if(session('success'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            x-transition.opacity.duration.500ms
            class="fixed top-24 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-[9999]"
        >
        </div>
    @endif

    @if(session('error'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            x-transition.opacity.duration.500ms
            class="fixed top-24 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-[9999]"
        >
            {{ session('error') }}
        </div>
    @endif


<body class="h-screen flex bg-white text-[12px]">


    <aside class="w-48 bg-blue-900 text-white flex flex-col fixed top-0 left-0 h-full">
        <div class="h-20 px-3 border-b border-blue-700 flex items-center justify-center">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-12 object-contain">
        </div>


        <nav class="mt-4 flex-grow space-y-1">
            @php
                $links = [
                    ['url' => route('case-orders.index'), 'label' => 'Case Orders', 'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>'],
                    ['url' => route('appointments.index'), 'label' => 'Appointments', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
                    ['url' => url('deliveries'), 'label' => 'Deliveries', 'icon' => '<path d="M10 17h4V5H2v12h2"/><path d="M22 17h-4V9h3l1 2z"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>'],
                    ['url' => route('billing.index'), 'label' => 'Billing', 'icon' => '<rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>'],
                    ['url' => route('technicians.index'), 'label' => 'Technicians', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/>'],
                    ['url' => url('materials'), 'label' => 'Materials', 'icon' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="M3.3 7l8.7 5 8.7-5"/><path d="M12 22V12"/>'],
                    ['url' => route('riders.index'), 'label' => 'Riders', 'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                    ['url' => route('clinics.index'), 'label' => 'Clinics', 'icon' => '<path d="M3 21V7a2 2 0 0 1 2-2h4V3h6v2h4a2 2 0 0 1 2 2v14"/><path d="M16 21V11H8v10"/><path d="M12 21v-4"/>'],
                    ['url' => route('reports.index'), 'label' => 'Reports', 'icon' => '<path d="M3 3h18v4H3z"/><path d="M3 9h18v12H3z"/><path d="M8 13h2v5H8zM14 13h2v5h-2z"/>']
                ];
            @endphp

            @foreach($links as $link)
                <a href="{{ $link['url'] }}" 
                   class="flex items-center space-x-2 p-2 rounded-l-lg transition duration-150 
                          hover:bg-gray-300 hover:text-blue-900 
                          {{ request()->url() == $link['url'] ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        {!! $link['icon'] !!}
                    </svg>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>
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

                <a href="{{ url('dashboard') }}" class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/>
                    </svg>
                </a>

<div id="notification-container" class="relative">
    <button id="notification-bell-btn" 
            class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-300 transition relative z-30">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/>
        </svg>

        @if(isset($notificationCount) && $notificationCount > 0)
           
            <span id="notification-count" 
                  class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                {{ $notificationCount }}
            </span>
        @endif
    </button>

 
    @if(isset($notificationCount) && $notificationCount > 0)
        <div id="notification-popup" 
             class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-300 hidden z-40 origin-top-right scale-95 opacity-0 transition-all duration-200">
            <div class="p-4 border-b border-gray-300 flex justify-between items-center bg-gray-50 rounded-t-xl">
                <h5 class="text-base font-semibold text-gray-700">Notifications</h5>
                <span class="text-xs text-red-500 font-medium">New</span>
            </div>
            <div class="p-4 space-y-2">
                @foreach($notifications as $note)
                    <a href="{{ $note['link'] }}" class="block text-sm text-gray-700 hover:bg-gray-100 p-2 rounded">
                        {{ $note['message'] }}
                    </a>
                @endforeach
            </div>
            <div class="p-2 border-t border-gray-200 text-center bg-gray-50 rounded-b-xl">
                <a href="{{ url('notifications') }}" class="text-[12px] font-medium text-blue-900 hover:text-blue-700">
                    View All Notifications
                </a>
            </div>
        </div>
    @endif
</div>
                @php
                    // Only show admin/staff menu for web guard (default Auth::user())
                    $user = Auth::user();
                @endphp
                @if($user && in_array($user->role, ['admin', 'staff']))
                <div class="relative" id="userMenuContainer">
                    <button id="userMenuBtn" type="button" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 text-[12px] focus:outline-none">
                        <div class="w-6 h-6 bg-blue-600 text-white flex items-center justify-center rounded-full font-bold text-[10px]">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span>{{ $user->name }}</span>
                        <span class="ml-1 text-gray-500">({{ ucfirst($user->role) }})</span>
                        <svg class="w-4 h-4 ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="userDropdown" class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-md border hidden z-50">
                        <button onclick="openModal('settingsModal')" class="w-full text-left flex items-center px-3 py-2 hover:bg-gray-100 text-[12px]">Settings</button>
                        <form method="POST" action="{{ route('landing') }}">
                            @csrf
                            <button type="button" 
        class="w-full text-left px-3 py-2 text-red-500 hover:bg-gray-300 text-[12px]" 
        onclick="window.location.href='{{ route('landing') }}'">
  Sign out
</button>
                        </form>
                    </div>
                </div>
                @endif

            </div>
        </header>
        <main class="flex-grow overflow-y-auto">
            <div>
                @yield('content')
            </div>
        </main>
    </div>
    <div id="settingsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl w-96 p-6 relative">
            <h2 class="text-lg font-semibold mb-4">Settings</h2>
            <div>
                <h3 class="font-medium mb-2">Select Wallpaper</h3>
                <div class="grid grid-cols-3 gap-2">
                    @for ($i = 1; $i <= 9; $i++)
                        <img src="{{ asset('images/'.$i.'.jpg') }}" 
                             onclick="changeWallpaper('{{ asset('images/'.$i.'.jpg') }}')" 
                             class="w-full h-20 object-cover rounded cursor-pointer hover:ring-2 hover:ring-blue-500">
                    @endfor
                </div>
            </div>
            <div class="mt-4">
                <h3 class="font-medium mb-2">Other Settings</h3>
                <div class="space-y-2">
                    <label class="flex items-center gap-2"><input type="checkbox" class="form-checkbox"> Enable Notifications</label>
                    <label class="flex items-center gap-2"><input type="checkbox" class="form-checkbox"> Dark Mode</label>
                    <label class="flex items-center gap-2"><input type="checkbox" class="form-checkbox"> Compact Sidebar</label>
                </div>
            </div>
            <button onclick="closeModal('settingsModal')" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Close</button>
        </div>
    </div>

    <script>
        const bellBtn = document.getElementById('notification-bell-btn');
        const popup = document.getElementById('notification-popup');
        const container = document.getElementById('notification-container');

        bellBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if(popup.classList.contains('hidden')) {
                popup.classList.remove('hidden');
                void popup.offsetWidth; 
                popup.classList.add('scale-100', 'opacity-100');
                popup.classList.remove('scale-95', 'opacity-0');
            } else {
                popup.classList.add('scale-95', 'opacity-0');
                popup.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => { popup.classList.add('hidden'); }, 200);
            }
        });

        document.addEventListener('click', (e) => {
            if (!popup.classList.contains('hidden') && !container.contains(e.target)) {
                popup.classList.add('scale-95', 'opacity-0');
                popup.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => { popup.classList.add('hidden'); }, 200);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
                popup.classList.add('scale-95', 'opacity-0');
                popup.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => { popup.classList.add('hidden'); }, 200);
            }
        });

        function openModal(id){
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }
        function closeModal(id){
            document.getElementById(id).classList.remove('flex');
            document.getElementById(id).classList.add('hidden');
        }
        function changeWallpaper(url){
            document.body.style.backgroundImage = `url(${url})`;
            document.body.style.backgroundSize = 'cover';
            document.body.style.backgroundPosition = 'center';
        }

 
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');
        const userMenuContainer = document.getElementById('userMenuContainer');
        if(userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
            document.addEventListener('click', function(e) {
                if (!userMenuContainer.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
    </script>

</body>
</html>

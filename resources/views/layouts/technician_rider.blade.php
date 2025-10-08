<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <title>@yield('title', 'Dashboard')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex flex-col bg-white text-[12px]">

  
  <header class="bg-white p-3 flex items-center justify-between shadow-md z-20">
    <div class="flex items-center gap-2">
   
      <button id="menuToggle" class="md:hidden p-2 rounded-md hover:bg-gray-100 focus:outline-none">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <div class="flex items-center gap-2 border border-gray-300 rounded-lg p-2 bg-white w-48 sm:w-72 md:w-96">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
          <path
            d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 
            5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16
            c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 
            4.99L20.49 19l-4.99-5z" />
        </svg>
        <input type="text" placeholder="Search..." class="outline-none w-full text-[12px]">
      </div>
    </div>

    <div class="flex items-center gap-2">
      <div class="relative group">
        <button class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 text-[12px]">
          <div class="w-6 h-6 bg-blue-600 text-white flex items-center justify-center rounded-full font-bold text-[10px]"></div>
          <span>{{ Auth::user()->name }}</span>
        </button>
        <div
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

  <div class="flex flex-1 overflow-hidden">

    <aside id="sidebar"
      class="fixed md:relative top-0 left-0 h-full md:h-auto md:flex flex-col bg-blue-900 text-white w-48 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-30">
      <div class="h-20 px-3 border-b border-blue-700 flex items-center justify-center">
        <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-12 object-contain">
      </div>
    
      <nav class="flex-1 overflow-y-auto p-3">
        <a href="#" class="block px-3 py-2 hover:bg-blue-800 rounded">Dashboard</a>
        <a href="#" class="block px-3 py-2 hover:bg-blue-800 rounded">Reports</a>
        <a href="#" class="block px-3 py-2 hover:bg-blue-800 rounded">Settings</a>
      </nav>
    </aside>

    <main class="flex-1 overflow-y-auto bg-white p-3 md:ml-0">
      <div class="mt-2">
        @yield('content')
      </div>
    </main>
  </div>

  <script>
    
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');

    menuToggle.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
    });
  </script>
</body>
</html>

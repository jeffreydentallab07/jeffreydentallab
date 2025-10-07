<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
   <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Clinic Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .notification-popup-transition {
            transition: transform 0.2s ease-out, opacity 0.2s ease-out;
        }
    </style>
</head>
<body class="h-screen flex bg-white text-[12px]">


    <aside class="w-48 bg-blue-900 text-white flex flex-col fixed top-0 left-0 h-full">
        <div class="h-20 px-3 border-b border-blue-700 flex items-center justify-center">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-12 object-contain">
        </div>

       <nav class="mt-4 flex-grow space-y-1">
    <a href="{{ route('clinic.new-case-orders.index') }}" class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.new-case-orders.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v12a2 2 0 01-2 2z" />
        </svg>
        <span>Case Orders</span>
    </a>

    <a href="{{ route('clinic.appointments.index') }}" class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.appointments.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M8 7V3m8 4V3m-9 8h10m-10 4h10M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>Appointments</span>
    </a>

    <a href="{{ route('clinic.patients.index') }}" class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.patients.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M5.121 17.804A8.962 8.962 0 0112 15c2.5 0 4.735 1.03 6.379 2.684M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Patients</span>
    </a>

    <a href="{{ route('clinic.dentists.index') }}" class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.dentists.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zM6 20v-1a4 4 0 014-4h4a4 4 0 014 4v1H6z" />
        </svg>
        <span>Dentists</span>
    </a>

    <a href="{{ route('clinic.billing.index') }}" class="flex items-center space-x-2 p-2 rounded-l-lg hover:bg-gray-300 hover:text-blue-900 {{ request()->routeIs('clinic.billing.*') ? 'bg-gray-300 text-blue-900 font-semibold' : 'text-indigo-300' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v2m14 0v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9m14 0h-4" />
        </svg>
        <span>Billing</span>
    </a>
</nav>

        <div class="p-4 border-t border-blue-700 text-xs space-y-2">
            <p class="uppercase tracking-wide text-indigo-300 font-semibold">Follow Us</p>
            <div class="flex items-center gap-3 text-indigo-200">
               <a href="https://www.facebook.com/jeffrey.dental.lab.2025" target="_blank" class="hover:text-white">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M22 12a10 10 0 1 0-11.5 9.95v-7.05H8v-3h2.5V9.5a3.5 3.5 0 0 1 3.7-3.8c1 0 2 .1 2 .1v2.3h-1.2c-1.2 0-1.6.8-1.6 1.6v1.8H17l-.3 3h-2.5v7.05A10 10 0 0 0 22 12"/>
    </svg>
</a>
                <a href="#" class="hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23 3a10.9 10.9 0 0 1-3.14.86A4.48 4.48 0 0 0 22.4.36a9.08 9.08 0 0 1-2.88 1.1A4.52 4.52 0 0 0 16.62 0a4.48 4.48 0 0 0-4.5 4.5c0 .35.04.7.12 1.03A12.94 12.94 0 0 1 1.64.88 4.48 4.48 0 0 0 3.04 6.5a4.48 4.48 0 0 1-2.04-.56v.06a4.52 4.52 0 0 0 3.6 4.41 4.48 4.48 0 0 1-2.03.08 4.48 4.48 0 0 0 4.18 3.12A9 9 0 0 1 0 19.54a12.73 12.73 0 0 0 6.92 2.03c8.3 0 12.84-6.87 12.84-12.84 0-.2 0-.41-.01-.61A9.22 9.22 0 0 0 24 4.56a9.1 9.1 0 0 1-2.6.71A4.52 4.52 0 0 0 23 3z"/>
                    </svg>
                </a>
            </div>
            <p class="mt-2 text-indigo-300 text-[12px]">@JeffreyDentalLab</p>
        </div>
    </aside>

    <div class="flex-grow flex flex-col h-full ml-48">
       
        <header class="bg-white p-3 flex items-center justify-between shadow-md z-10">
            
            <div class="flex items-center gap-2 border border-gray-300 rounded-lg w-96 p-2 bg-white">
                <svg class="w-4 h-4 text-gray-300 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5z"/>
                </svg>
                <input type="text" placeholder="Search..." class="outline-none w-full text-[12px]">
            </div>

         
            <div class="flex items-center gap-2">
               
                <a href="{{ route('clinic.dashboard') }}" class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 12l9-9 9 9M4 10v10h5v-6h6v6h5V10" /></svg>
                </a>

                <div id="notification-container" class="relative">
                    <button id="notification-bell-btn" class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-300 transition relative z-30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full"></span>
                    </button>

                    
                    <div id="notification-popup" class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-300 hidden z-40 origin-top-right notification-popup-transition scale-95 opacity-0">
                        <div class="p-4 border-b border-gray-300 flex justify-between items-center bg-gray-30 rounded-t-xl">
                            <h5 class="text-base font-semibold text-gray-300">Notifications</h5>
                            <span class="text-xs text-gray-300 font-medium">New</span>
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-300">
                            <div class="p-3 hover:bg-gray-30 cursor-pointer">
                                <p class="text-[13px] font-medium text-blue-900">New Case Order</p>
                                <p class="text-[11px] text-gray-300 truncate">Case #1234 submitted successfully.</p>
                                <span class="text-[10px] text-gray-300">5 minutes ago</span>
                            </div>
                        </div>
                        <div class="p-2 border-t border-gray-300 text-center bg-gray-30 rounded-b-xl">
                            <a href="{{ url('notifications') }}" class="text-[12px] font-medium text-blue-900 hover:text-blue-700">View All</a>
                        </div>
                    </div>
                </div>

              
<div class="relative">
    <button id="userDropdownBtn" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-300 text-[12px]">
        <div class="w-6 h-6 bg-blue-600 text-white flex items-center justify-center rounded-full font-bold text-[10px]">
            {{ strtoupper(substr(Auth::guard('clinic')->user()->clinic_name, 0, 2)) }}
        </div>
        <span>{{ Auth::guard('clinic')->user()->clinic_name }}</span>
    </button>
    <div id="userDropdownMenu" class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-md border hidden z-50">
        <button id="openClinicSettingsBtn" class="w-full text-left px-3 py-2 hover:bg-gray-300 text-[12px]">Settings</button>
        <form method="POST" action="{{ route('clinic.logout') }}">
            @csrf
          <button type="button" 
        class="w-full text-left px-3 py-2 text-red-500 hover:bg-gray-300 text-[12px]" 
        onclick="window.location.href='{{ route('landing') }}'">
  Sign out
</button>
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

   
  <div id="clinicSettingsModal" class="hidden fixed inset-0 bg-gray-300 bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
            
            <!-- Header -->
            <div class="p-6 border-b border-gray-300 flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-300">Clinic Settings</h2>
                <button onclick="closeModal('clinicSettingsModal')" class="text-gray-300 hover:text-gray-300 text-2xl">&times;</button>
            </div>

            <!-- Section Title -->
            <div class="flex border-b border-gray-300 px-6 py-2 bg-blue-900 text-white">
                <span class="px-4 py-2 border-b-2 border-white font-medium">Clinic Details</span>
            </div>

            <!-- Form -->
            <div class="p-6 space-y-6">
                <form method="POST" action="{{ route('clinic.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Photo Upload -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <img id="previewClinicPhoto" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md"
                                src="{{ Auth::guard('clinic')->user()->photo ? asset('storage/' . Auth::guard('clinic')->user()->photo) : 'https://via.placeholder.com/150' }}"
                                alt="Clinic Photo">
                            <input type="file" name="photo"
                                class="absolute bottom-0 right-0 text-xs opacity-0 cursor-pointer w-20 h-20"
                                onchange="previewClinicImage(event)">
                        </div>
                        <div>
                            <p class="text-gray-300 font-medium">Clinic Photo</p>
                            <p class="text-sm text-gray-300">Upload a new photo if you want to change it.</p>
                        </div>
                    </div>

                    <!-- Text Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Clinic Name</label>
                            <input type="text" name="clinic_name" value="{{ Auth::guard('clinic')->user()->clinic_name }}"
                                class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Email</label>
                            <input type="email" name="email" value="{{ Auth::guard('clinic')->user()->email }}"
                                class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ Auth::guard('clinic')->user()->contact_number ?? '' }}"
                                class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Address</label>
                            <input type="text" name="address" value="{{ Auth::guard('clinic')->user()->address ?? '' }}"
                                class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button" onclick="closeModal('clinicSettingsModal')" class="py-2 px-4 rounded-md text-gray-300 font-medium hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
      
document.addEventListener('DOMContentLoaded', function() {
    @if(session('login_success'))
        // Create toast element
        const toast = document.createElement('div');
        toast.innerText = "{{ session('login_success') }}";
        toast.className = "fixed top-20 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in";

        // Optional: smooth fade out
        toast.style.transition = "opacity 0.5s ease-out, transform 0.5s ease-out";

        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translateY(-10px)";
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    @endif
});


      function openModal(id){ document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
        function closeModal(id){ document.getElementById(id).classList.remove('flex'); document.getElementById(id).classList.add('hidden'); }

        // Preview Clinic Photo
        function previewClinicImage(event) {
            const output = document.getElementById('previewClinicPhoto');
            output.src = URL.createObjectURL(event.target.files[0]);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Notification popup
            const bellButton = document.getElementById('notification-bell-btn');
            const popup = document.getElementById('notification-popup');
            const container = document.getElementById('notification-container');
            function closePopup() { popup.classList.add('scale-95','opacity-0'); popup.classList.remove('scale-100','opacity-100'); setTimeout(()=>popup.classList.add('hidden'),200); }
            function openPopup() { popup.classList.remove('hidden'); void popup.offsetWidth; popup.classList.add('scale-100','opacity-100'); popup.classList.remove('scale-95','opacity-0'); }
            bellButton.addEventListener('click', function(e){ e.stopPropagation(); popup.classList.contains('hidden') ? openPopup() : closePopup(); });
            document.addEventListener('click', function(e){ if(!popup.classList.contains('hidden') && !container.contains(e.target)) closePopup(); });
            document.addEventListener('keydown', function(e){ if(e.key==='Escape' && !popup.classList.contains('hidden')) closePopup(); });

            // User dropdown and settings modal
            const userBtn = document.getElementById('userDropdownBtn');
            const userMenu = document.getElementById('userDropdownMenu');
            const openSettingsBtn = document.getElementById('openClinicSettingsBtn');
            const modal = document.getElementById('clinicSettingsModal');

            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });

            openSettingsBtn.addEventListener('click', function() {
                userMenu.classList.add('hidden');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            document.addEventListener('click', function(e) {
                if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            document.addEventListener('keydown', function(e){
                if(e.key === 'Escape' && !modal.classList.contains('hidden')){
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" href="{{ asset('images/logo3.ico') }}" type="image/x-icon">
  <title>Denture Laboratory System</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    @keyframes slideIn {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .slide-in { animation: slideIn 0.9s ease forwards; }
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      20%,60% { transform: translateX(-10px); }
      40%,80% { transform: translateX(10px); }
    }
    .shake { animation: shake 0.4s ease; }
    .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
  </style>
</head>
<body class="antialiased bg-white text-gray-800">

  <header class="fixed w-full z-40">
    <div class="bg-teal-900/80 backdrop-blur-sm">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <a href="#home" class="flex items-center space-x-3">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="h-10 w-auto">
          </a>

          <nav class="hidden md:flex items-center space-x-6 text-white">
            <a href="#services" class="hover:text-teal-200 transition">Our Services</a>
            <a href="#about" class="hover:text-teal-200 transition">About Us</a>
            <a href="#contact" class="hover:text-teal-200 transition">Contact Us</a>
            <button id="openLabLoginBtn" class="px-4 py-2 rounded-lg bg-transparent border border-white/20 hover:bg-white/10 transition">Lab Login</button>
          </nav>

          <div class="md:hidden flex items-center">
            <button id="mobileMenuBtn" aria-label="Toggle menu" class="text-white mr-3">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
            <button id="openLabLoginBtnMobile" class="text-white">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 7a4 4 0 110-8 4 4 0 010 8z"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div id="mobileMenu" class="hidden md:hidden bg-teal-900/95">
        <div class="px-4 py-3 space-y-2">
          <a href="#services" class="block text-white py-2">Our Services</a>
          <a href="#about" class="block text-white py-2">About Us</a>
          <a href="#contact" class="block text-white py-2">Contact Us</a>
          <div class="pt-2">
            <button id="openSignupModalMobile" class="w-full bg-white text-teal-900 rounded-md py-2 font-semibold">Register Clinic</button>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main id="home" class="pt-20">
   
    <section class="relative w-full min-h-screen bg-cover bg-center flex items-center" style="background-image: url('{{ asset('images/bg.jpg') }}');">
      <div class="absolute inset-0 bg-teal-900/60"></div>

      <div class="relative z-10 max-w-6xl mx-auto px-6 py-20 slide-in">
        <div class="bg-white rounded-2xl shadow-xl md:flex md:items-center overflow-hidden">
          <div class="p-8 md:w-1/2 space-y-6">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-blue-900">Your Smile, Our Masterpiece</h1>
            <p class="text-gray-700 text-base md:text-lg">A modern system for dental clinics and laboratories — appointments, case orders, schedules, deliveries, and billing in one place.</p>
          </div>

          <div class="p-8 md:w-1/2 flex flex-col justify-center space-y-6">
            <div class="relative w-full">
              <input id="search" type="text" placeholder=" " class="peer block w-full rounded-full bg-gray-100 px-4 pt-5 pb-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500" />
              <label for="search" class="absolute left-4 top-2 text-gray-500 text-sm transition-all peer-placeholder-shown:top-5 peer-placeholder-shown:text-base peer-focus:top-2 peer-focus:text-teal-500 peer-focus:text-sm">Search for Dental lab services...</label>
              <svg class="absolute right-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>

             <div class="w-full">
    <button 
      id="openSignupModal" 
      class="w-full bg-blue-900 text-white py-3 rounded-full font-semibold shadow-md hover:bg-[#127a95] transition duration-300"
    >
      Register Clinic
    </button>
  </div>
          </div>
        </div>
      </div>
    </section>

    <section id="services" class="py-16 bg-gray-50">
      <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="text-3xl font-bold text-blue-900 mb-4">Our Services</h2>
        <p class="text-gray-700">High-quality dental prosthetics, denture repairs, and custom dental lab solutions for clinics and patients.</p>
      </div>
    </section>

    <section id="about" class="py-12 px-4">
      <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-6 items-center">
        <div class="bg-white rounded-2xl p-4 flex justify-center items-center shadow-lg">
          <img src="{{ asset('images/s.png') }}" alt="team" class="max-h-80 object-contain rounded-xl">
        </div>

        <div class="bg-blue-900 text-white rounded-2xl p-6">
          <h3 class="text-xs font-semibold uppercase opacity-80">Who We Are</h3>
          <h2 class="text-2xl font-bold mt-2 mb-3">Precision Crafted Smiles</h2>
          <p class="text-sm opacity-90 mb-6">Our dental laboratory focuses on excellence and precision in crafting dentures and prosthetics. Trusted by dental clinics and patients alike.</p>

          <a href="#learn-more" class="inline-flex items-center px-4 py-2 rounded-full bg-white text-teal-600 font-medium shadow-sm">Learn More</a>
        </div>
      </div>
    </section>

    <section id="contact" class="py-16 bg-gray-100 px-4">
      <div class="max-w-5xl mx-auto grid md:grid-cols-3 gap-6">
       
        <div class="bg-white rounded-3xl shadow-2xl p-6 text-center transition-transform hover:-translate-y-2">
          <h3 class="text-xl font-semibold text-blue-900 mb-4">Phone Number</h3>
          <div class="flex flex-col gap-3 items-center">
            <a href="tel:+639067732353" class="flex items-center space-x-3 px-4 py-2 rounded-xl bg-gray-100 w-full justify-center">
              <img src="{{ asset('images/tml.png') }}" alt="TM" class="w-6 h-6"><span>0906 773 2353</span>
            </a>
            <a href="tel:0987654321" class="flex items-center space-x-3 px-4 py-2 rounded-xl bg-gray-100 w-full justify-center">
              <img src="{{ asset('images/smart.png') }}" alt="Smart" class="w-6 h-6"><span>09 8765 4321</span>
            </a>
            <a href="tel:0911223344" class="flex items-center space-x-3 px-4 py-2 rounded-xl bg-gray-100 w-full justify-center">
              <img src="{{ asset('images/dito.png') }}" alt="DITO" class="w-6 h-6"><span>09 1122 3344</span>
            </a>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl p-6 text-center transition-transform hover:-translate-y-2">
          <h3 class="text-xl font-semibold text-blue-900 mb-4">Address</h3>
          <button id="openMapModalBtn" class="w-full p-4 rounded-xl bg-white text-black shadow-md hover:scale-105 transition text-sm">
            Zone 7 Bulua District 1<br>9000 Cagayan de Oro City<br>Misamis Oriental Philippines
          </button>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl p-6 text-center transition-transform hover:-translate-y-2">
          <h3 class="text-xl font-semibold text-blue-900 mb-6">Social Media</h3>
          <div class="flex justify-center gap-4">
            <a href="https://www.facebook.com/jeffrey.dental.lab.2025" target="_blank" class="w-12 h-12 rounded-xl flex items-center justify-center bg-white shadow-md hover:bg-blue-600 transition">
              <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook" class="w-6 h-6">
            </a>
            <a href="#" class="w-12 h-12 rounded-xl flex items-center justify-center bg-white shadow-md hover:scale-105 transition">
              <img src="https://upload.wikimedia.org/wikipedia/commons/e/e7/Instagram_logo_2016.svg" alt="Instagram" class="w-6 h-6">
            </a>
            <a href="mailto:jeffreydentallab143@gmail.com" class="w-12 h-12 rounded-xl flex items-center justify-center bg-white shadow-md hover:bg-red-500 transition">
              <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/Mail_%28iOS%29.svg" alt="Email" class="w-6 h-6">
            </a>
            <a href="#" class="w-12 h-12 rounded-xl flex items-center justify-center bg-white shadow-md hover:scale-105 transition">
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png" alt="LinkedIn" class="w-6 h-6">
            </a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div id="signupModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg overflow-hidden">
      <div class="flex flex-col md:flex-row">
        <div class="w-full md:w-1/2 relative flex items-center justify-center p-6" style="background-image:url('{{ asset('images/bg.jpg') }}'); background-size:cover;">
          <div class="absolute inset-0 bg-black/40"></div>
          <div class="relative z-10 text-center text-white px-4">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-24 mx-auto mb-3">
            <h2 class="text-lg font-bold">Join Denture System</h2>
            <p class="text-xs mt-2">Register your clinic to manage appointments, case orders, billing, and deliveries.</p>
          </div>
        </div>

        <div class="w-full md:w-1/2 p-6 bg-white">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-blue-900">Clinic Registration</h3>
            <button data-close class="text-gray-500 hover:text-red-500" aria-label="Close signup modal">&times;</button>
          </div>

          <form id="clinicSignupForm" action="{{ route('clinic.signup') }}" method="POST" class="space-y-3">
            @csrf
            <input name="clinic_name" placeholder="Clinic Name" required class="w-full border-b p-2 focus:outline-none focus:border-[#189ab4]" />
            <input name="owner_name" placeholder="Owner Name" required class="w-full border-b p-2 focus:outline-none focus:border-[#189ab4]" />
            <input name="address" placeholder="Address (optional)" class="w-full border-b p-2 focus:outline-none focus:border-[#189ab4]" />
            <input name="contact_number" placeholder="Contact Number (optional)" class="w-full border-b p-2 focus:outline-none focus:border-[#189ab4]" />
            <input type="email" name="email" placeholder="Email" required class="w-full border-b p-2 focus:outline-none focus:border-[#189ab4]" />
            <input type="password" name="password" id="signup_password" placeholder="Password" required class="w-full border-b p-2 focus:outline-none focus:border-[#189ab4]" />
            <input type="password" name="password_confirmation" id="signup_password_confirmation" placeholder="Confirm Password" required class="w-full border-b p-2 focus:outline-none focus:border-[#189ab4]" />
            <button type="submit" class="w-full bg-blue-900 text-white py-2 rounded-md font-bold hover:bg-[#127a95]">Create Account</button>
          </form>

          <p class="text-center text-sm text-gray-600 mt-3">Already have an account? <a href="#" id="openClinicLoginFromSignup" class="text-blue-900">Login here</a></p>
        </div>
      </div>
    </div>
  </div>

  
  <div id="clinicLoginModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
  <div class="bg-white w-full max-w-4xl rounded-2xl shadow-lg overflow-hidden">
    <div class="flex flex-col md:flex-row h-full">
      <!-- Left Side -->
      <div class="w-full md:w-1/2 relative flex items-center justify-center p-6" style="background-image:url('{{ asset('images/bg.jpg') }}'); background-size:cover; background-position:center;">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 text-center text-white px-4">
          <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-32 mx-auto mb-4">
          <h2 class="text-2xl font-bold">Welcome Back</h2>
          <p class="text-sm mt-2">Log in to manage your clinic's appointments, case orders, billing, and deliveries.</p>
        </div>
      </div>

      <!-- Right Side -->
      <div class="w-full md:w-1/2 p-6 bg-white flex flex-col justify-center">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-2xl font-bold text-blue-900">Clinic Login</h3>
          <button data-close class="text-gray-500 hover:text-red-500 text-2xl leading-none">&times;</button>
        </div>

        <form id="clinicLoginForm" action="{{ route('clinic.login.post') }}" method="POST" class="space-y-3" novalidate>
          @csrf

          <div>
            <input type="email" id="clinicEmail" name="email" placeholder="Enter Email" required pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
              class="w-full border p-3 rounded-md focus:outline-none focus:border-[#189ab4]" />
            <p id="clinicEmailError" class="text-red-600 text-sm mt-1 hidden">Please enter a valid email.</p>
          </div>
          <div class="relative">
            <input type="password" name="password" id="clinicPassword" placeholder="Enter Password" required minlength="8"
              class="w-full border p-3 rounded-md pr-10 focus:outline-none focus:border-[#189ab4]" />
            <button type="button" id="clinicTogglePassword" class="absolute right-3 top-3 text-gray-500" aria-label="Toggle password">
              <svg id="clinicEyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
            <p id="clinicPasswordError" class="text-red-600 text-sm mt-1 hidden">Password must be at least 8 characters long.</p>
          </div>

          <div class="flex items-center justify-between text-sm text-gray-600">
            <label class="flex items-center space-x-2">
              <input type="checkbox" name="remember" class="text-[#189ab4]">
              <span>Remember Me</span>
            </label>
            <a href="#" class="text-[#189ab4] hover:underline">Forgot Password?</a>
          </div>

          <button type="submit" class="w-full bg-blue-900 text-white rounded-md font-bold py-3 hover:bg-[#127a95]">Login</button>
        </form>

        <p class="text-center text-xs text-gray-700 mt-3">
          Don’t have an account?
          <a href="#" id="openSignupFromLogin" class="text-blue-900 font-semibold hover:underline">Sign up here</a>
        </p>
      </div>
    </div>
  </div>
</div>



<div id="labLoginModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
  <div class="bg-white w-full max-w-4xl rounded-2xl shadow-lg overflow-hidden">
    <div class="flex flex-col md:flex-row h-full">
   
      <div class="w-full md:w-1/2 relative flex items-center justify-center p-6" style="background-image:url('{{ asset('images/bg.jpg') }}'); background-size:cover; background-position:center;">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 text-center text-white px-4">
          <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="w-32 mx-auto mb-4">
          <h2 class="text-2xl font-bold">Jeffrey Dental Laboratory</h2>
          <p class="text-sm mt-2">Lab portal — manage orders and deliveries.</p>
        </div>
      </div>

      <div class="w-full md:w-1/2 p-6 bg-white flex flex-col justify-center">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-2xl font-bold text-blue-900">Laboratory Login</h3>
          <button data-close class="text-gray-500 hover:text-red-500 text-2xl leading-none">&times;</button>
        </div>

        <form id="labLoginForm" action="{{ url('/login') }}" method="POST" class="space-y-3" novalidate>
          @csrf
          <div>
            <input id="labEmail" name="email" type="email" placeholder="Enter Email" required pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" class="w-full border p-3 rounded-md focus:outline-none focus:border-[#189ab4]" />
            <p id="labEmailError" class="text-red-600 text-sm mt-1 hidden">Please enter a valid email.</p>
          </div>

          <div class="relative">
            <input id="labPassword" name="password" type="password" placeholder="Enter Password" required minlength="8" class="w-full border p-3 rounded-md pr-10 focus:outline-none focus:border-[#189ab4]" />
            <button type="button" id="toggleLabPassword" class="absolute right-3 top-3 text-gray-500" aria-label="Toggle lab password">
              <svg id="labEyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
            <p id="labPasswordError" class="text-red-600 text-sm mt-1 hidden">Password must be at least 8 characters long.</p>
          </div>

          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center space-x-2"><input type="checkbox" name="remember" class="text-[#189ab4]"> <span class="text-gray-700">Remember Me</span></label>
            <a href="#" class="text-[#189ab4] hover:underline">Forgot Password?</a>
          </div>

          <button type="submit" class="w-full bg-blue-900 text-white rounded-md font-bold py-3 hover:bg-[#127a95]">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>


  
  <div id="mapModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl relative">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-blue-600">Google Maps Location</h2>
        <button data-close class="text-gray-500 hover:text-gray-800">&times;</button>
      </div>

      <iframe src="https://www.google.com/maps/embed?pb=!3m2!1sen!2sph!4v1759895887714!5m2!1sen!2sph!6m8!1m7!1sGXumgqKB9fSHfdDq7-g70Q!2m2!1d8.504174306065364!2d124.610842342069!3f343.02643523285644!4f-7.326250593273585!5f0.7820865974627469" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

      <div class="mt-4 text-center">
        <a href="https://maps.app.goo.gl/rannarDsNdJJebVX9" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Open in Google Maps</a>
      </div>
    </div>
  </div>

  
  <div id="toastContainer" class="fixed top-5 right-5 z-[9999] space-y-2"></div>

  
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      
      const showToast = (message, type = 'error') => {
        const container = document.getElementById('toastContainer');
        const el = document.createElement('div');
        el.className = (type === 'success')
          ? 'bg-green-500 text-white px-4 py-2 rounded shadow-lg'
          : 'bg-red-500 text-white px-4 py-2 rounded shadow-lg';
        el.textContent = message;
        container.appendChild(el);
        setTimeout(() => el.remove(), 3500);
      };

      
      const openById = (id) => {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      };
      const closeModalEl = (el) => {
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
      };

      
      document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const modal = e.target.closest('.fixed.inset-0');
          if (modal) closeModalEl(modal);
        });
      });

      
      document.querySelectorAll('.fixed.inset-0').forEach(modal => {
        modal.addEventListener('click', (e) => {
          if (e.target === modal) closeModalEl(modal);
        });
      });

     
      document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
        const mm = document.getElementById('mobileMenu');
        mm.classList.toggle('hidden');
      });

      
      document.getElementById('openSignupModal')?.addEventListener('click', () => openById('signupModal'));
      document.getElementById('openSignupModalMobile')?.addEventListener('click', () => openById('signupModal'));
      document.getElementById('openClinicLoginFromSignup')?.addEventListener('click', (e) => { e.preventDefault(); closeModalEl(document.getElementById('signupModal')); openById('clinicLoginModal'); });
      document.getElementById('openSignupFromLogin')?.addEventListener('click', (e) => { e.preventDefault(); closeModalEl(document.getElementById('clinicLoginModal')); openById('signupModal'); });

     
      document.getElementById('openMapModalBtn')?.addEventListener('click', () => openById('mapModal'));

     
      document.getElementById('openLabLoginBtn')?.addEventListener('click', (e) => { e.preventDefault(); openById('labLoginModal'); });
      document.getElementById('openLabLoginBtnMobile')?.addEventListener('click', (e) => { e.preventDefault(); openById('labLoginModal'); });

      
      const togglePassword = (btnId, inputId) => {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        if (!btn || !input) return;
        btn.addEventListener('click', () => {
          const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
          input.setAttribute('type', type);
        });
      };
      togglePassword('clinicTogglePassword', 'clinicPassword');
      togglePassword('toggleLabPassword', 'labPassword');

     
      const labEmail = document.getElementById('labEmail');
      labEmail?.addEventListener('input', () => {
        const err = document.getElementById('labEmailError');
        if (!labEmail.checkValidity()) err.classList.remove('hidden'); else err.classList.add('hidden');
      });
      const labPassword = document.getElementById('labPassword');
      labPassword?.addEventListener('input', () => {
        const err = document.getElementById('labPasswordError');
        if (labPassword.value.length > 0 && labPassword.value.length < 8) err.classList.remove('hidden'); else err.classList.add('hidden');
      });

      
      const clinicLoginForm = document.getElementById('clinicLoginForm');
      if (clinicLoginForm) {
        clinicLoginForm.addEventListener('submit', async (e) => {
          e.preventDefault();
          const modalBox = clinicLoginForm.closest('.bg-white');
          const formData = new FormData(clinicLoginForm);

          try {
            const resp = await fetch(clinicLoginForm.action, {
              method: 'POST',
              headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': formData.get('_token') || '' },
              body: formData
            });
            const data = await resp.json().catch(() => null);

            if (resp.ok && data?.success) {
              window.location.href = data.redirect || "{{ route('clinic.dashboard') }}";
            } else {
              
              setTimeout(() => modalBox?.classList.remove('shake'), 500);
              showToast((data && data.message) || 'Invalid email or password.');
            }
          } catch (err) {
            modalBox?.classList.add('shake');
            setTimeout(() => modalBox?.classList.remove('shake'), 500);
            showToast('Login failed. Please try again.');
          }
        });
      }

      const clinicEmail = document.getElementById('clinicEmail');
  clinicEmail?.addEventListener('input', () => {
    const err = document.getElementById('clinicEmailError');
    if (!clinicEmail.checkValidity()) err.classList.remove('hidden');
    else err.classList.add('hidden');
  });

  const clinicPassword = document.getElementById('clinicPassword');
  clinicPassword?.addEventListener('input', () => {
    const err = document.getElementById('clinicPasswordError');
    if (clinicPassword.value.length > 0 && clinicPassword.value.length < 8) err.classList.remove('hidden');
    else err.classList.add('hidden');
  });
      const labLoginForm = document.getElementById('labLoginForm');
      if (labLoginForm) {
        labLoginForm.addEventListener('submit', async (e) => {
          e.preventDefault();
          const container = labLoginForm.closest('.bg-white');
          const formData = new FormData(labLoginForm);
          try {
            const resp = await fetch(labLoginForm.action, {
              method: 'POST',
              headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': formData.get('_token') || '' },
              body: formData
            });
            const data = await resp.json().catch(() => null);
            if (resp.ok && data?.success) {
              window.location.href = data.redirect || '/dashboard';
            } else {
              container?.classList.add('shake');
              setTimeout(() => container?.classList.remove('shake'), 500);
              showToast((data && data.message) || 'Invalid credentials.');
            }
          } catch (err) {
            container?.classList.add('shake');
            setTimeout(() => container?.classList.remove('shake'), 500);
            showToast('Login failed. Please try again.');
          }
        });
      }
      @if(session('signup_success'))
        showToast("{{ session('signup_success') }}", 'success');
      @endif
    });
  
  </script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>

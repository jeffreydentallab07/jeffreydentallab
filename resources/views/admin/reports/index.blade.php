@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="p-8 bg-gradient-to-br from-gray-200 to-gray-400 min-h-screen">


    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @php
            $reports = [
                ['route' => 'reports.caseorders', 'title' => 'Case Orders Report', 'desc' => 'View all case orders', 'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>'],
                ['route' => 'reports.appointments', 'title' => 'Appointments Report', 'desc' => 'View all appointments', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
                ['route' => 'reports.deliveries', 'title' => 'Deliveries Report', 'desc' => 'Track deliveries', 'icon' => '<path d="M10 17h4V5H2v12h2"/><path d="M22 17h-4V9h3l1 2z"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>'],
                ['route' => 'reports.billings', 'title' => 'Billing Report', 'desc' => 'View billing details', 'icon' => '<rect x="2" y="4" width="20" height="16" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>'],
                ['route' => 'reports.technicians', 'title' => 'Technicians Report', 'desc' => 'View technicians performance', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/>'],
                ['route' => 'reports.riders', 'title' => 'Riders Report', 'desc' => 'View riders deliveries', 'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                ['route' => 'reports.clinics', 'title' => 'Clinics Report', 'desc' => 'View clinics activity', 'icon' => '<path d="M3 21V7a2 2 0 0 1 2-2h4V3h6v2h4a2 2 0 0 1 2 2v14"/><path d="M16 21V11H8v10"/><path d="M12 21v-4"/>'],
            ];
        @endphp

        @foreach ($reports as $r)
            <a href="{{ route($r['route']) }}"
               class="group relative bg-white/70 backdrop-blur-md rounded-2xl p-6 shadow-md border border-gray-200 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:bg-white">
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                <div class="relative z-10 flex items-center space-x-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor"
                         class="w-10 h-10 text-blue-600 group-hover:text-blue-800 transition-colors duration-300">
                        {!! $r['icon'] !!}
                    </svg>
                    <div>
                        <h2 class="font-bold text-lg text-gray-800 mb-1 group-hover:text-blue-700">
                            {{ $r['title'] }}
                        </h2>
                        <p class="text-sm text-gray-600 group-hover:text-gray-800">
                            {{ $r['desc'] }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection

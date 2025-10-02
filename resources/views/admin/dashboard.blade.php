@extends('layouts.app')
@section('title', 'Lab Home')

@section('content')
<div class="p-5 bg-gray-300 min-h-screen text-[12px] space-y-5">

    {{-- Top Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
        <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-xl hover:-translate-y-2 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Clinics</h6>
            <h3 class="text-2xl font-bold text-[#2a7b8e] mt-2">5</h3>
        </div>
        <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-xl hover:-translate-y-2 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Appointments</h6>
            <h3 class="text-2xl font-bold text-[#2a7b8e] mt-2">12</h3>
        </div>
        <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-xl hover:-translate-y-2 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Materials</h6>
            <h3 class="text-2xl font-bold text-[#2a7b8e] mt-2">150</h3>
        </div>
        <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-xl hover:-translate-y-2 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Job Orders</h6>
            <h3 class="text-2xl font-bold text-[#2a7b8e] mt-2">36</h3>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">

        {{-- Left Column --}}
        <div class="col-span-12 lg:col-span-8 flex flex-col gap-5">

            {{-- Reports Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 flex flex-col justify-center items-center hover:shadow-xl hover:-translate-y-2 hover:scale-105 transition transform duration-300">
                    <h6 class="font-semibold text-gray-700 mb-2 border-b border-gray-200 w-full pb-2 text-center">Appointments Report</h6>
                    <p class="text-3xl font-bold text-[#2a7b8e] mt-2">12</p>
                    <p class="text-sm text-gray-500 mt-1">Total appointments scheduled</p>
                </div>
                <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 flex flex-col justify-center items-center hover:shadow-xl hover:-translate-y-2 hover:scale-105 transition transform duration-300">
                    <h6 class="font-semibold text-gray-700 mb-2 border-b border-gray-200 w-full pb-2 text-center">Materials Report</h6>
                    <p class="text-3xl font-bold text-[#2a7b8e] mt-2">150</p>
                    <p class="text-sm text-gray-500 mt-1">Total materials in inventory</p>
                </div>
            </div>

            {{-- Recent Appointments Table --}}
            <section class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 overflow-hidden flex flex-col mt-5 hover:shadow-xl hover:-translate-y-1 transition transform duration-300">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h6 class="font-semibold text-gray-700">Recent Appointments</h6>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-[#2a7b8e] text-white sticky top-0 shadow">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Clinic</th>
                                <th class="px-4 py-2 text-left font-semibold">Technician</th>
                                <th class="px-4 py-2 text-left font-semibold">Date & Time</th>
                                <th class="px-4 py-2 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-800">
                            <tr class="hover:bg-white/80">
                                <td class="px-4 py-2">Smile Dental</td>
                                <td class="px-4 py-2">John Doe</td>
                                <td class="px-4 py-2 text-[11px] text-gray-500">2025-08-12 10:00 AM</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="bg-green-200 text-green-900 font-medium px-2 py-0.5 rounded-full text-xs">Completed</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-white/80">
                                <td class="px-4 py-2">Bright Smile</td>
                                <td class="px-4 py-2">Jane Smith</td>
                                <td class="px-4 py-2 text-[11px] text-gray-500">2025-08-13 2:00 PM</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="bg-yellow-200 text-yellow-900 font-medium px-2 py-0.5 rounded-full text-xs">Pending</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Right Column --}}
        <div class="col-span-12 lg:col-span-4 flex flex-col gap-5">

            {{-- Job Orders Report Cards --}}
            <div class="grid grid-cols-1 gap-3">
                <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 text-center hover:shadow-xl hover:-translate-y-2 hover:scale-105 transition transform duration-300">
                    <p class="text-sm text-gray-500">Completed</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">25</p>
                </div>
                <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 text-center hover:shadow-xl hover:-translate-y-2 hover:scale-105 transition transform duration-300">
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">8</p>
                </div>
            </div>

            <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 overflow-x-auto hover:shadow-xl hover:-translate-y-1 transition transform duration-300">
                <table class="min-w-full">
                    <thead class="bg-[#2a7b8e] text-white sticky top-0 shadow">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Status</th>
                            <th class="px-4 py-2 text-center font-semibold">Count</th>
                            <th class="px-4 py-2 text-center font-semibold">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-800">
                        <tr class="hover:bg-white/80">
                            <td class="px-4 py-2">Completed</td>
                            <td class="px-4 py-2 text-center">25</td>
                            <td class="px-4 py-2 text-center">62.5%</td>
                        </tr>
                        <tr class="hover:bg-white/80">
                            <td class="px-4 py-2">Pending</td>
                            <td class="px-4 py-2 text-center">8</td>
                            <td class="px-4 py-2 text-center">20%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

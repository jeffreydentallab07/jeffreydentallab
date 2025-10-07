@extends('layouts.clinic')

@section('title', 'Clinic Home')

@section('content')
<div class="p-6 bg-gray-300 min-h-screen text-[13px] space-y-6">

   
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
        <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Case Orders</h6>
            <h3 class="text-2xl font-bold text-[#189ab4] mt-2">5</h3>
        </div>
        <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Completed</h6>
            <h3 class="text-2xl font-bold text-green-600 mt-2">42</h3>
        </div>
        <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-5 hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
            <h6 class="text-gray-500 text-xs uppercase tracking-wider">Pending</h6>
            <h3 class="text-2xl font-bold text-yellow-600 mt-2">14</h3>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8 flex flex-col gap-6">

        
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-6 flex flex-col items-center hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
                    <h6 class="font-semibold text-gray-700 mb-2 border-b border-gray-200 w-full text-center pb-2">Appointments Report</h6>
                    <p class="text-3xl font-bold text-[#189ab4] mt-2">12</p>
                    <p class="text-sm text-gray-500 mt-1">Total appointments scheduled</p>
                </div>
                <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 p-6 flex flex-col items-center hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition transform duration-300">
                    <h6 class="font-semibold text-gray-700 mb-2 border-b border-gray-200 w-full text-center pb-2">Billing Summary</h6>
                    <p class="text-3xl font-bold text-[#10b981] mt-2">₱42,500</p>
                    <p class="text-sm text-gray-500 mt-1">Total billing this month</p>
                </div>
            </div>

            <section class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition transform duration-300">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h6 class="font-semibold text-gray-700">Recent Appointments</h6>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-[#189ab4] text-white sticky top-0 shadow">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Technician</th>
                                <th class="px-4 py-2 text-left font-semibold">Date & Time</th>
                                <th class="px-4 py-2 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-800">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 text-center">Tech John</td>
                                <td class="px-4 py-2 text-center text-[11px] text-gray-500">2025-08-26 10:00 AM</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="bg-green-200 text-green-900 font-medium px-2 py-0.5 rounded-full text-xs">Completed</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 text-center">Tech Anna</td>
                                <td class="px-4 py-2 text-center text-[11px] text-gray-500">2025-08-27 02:00 PM</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="bg-yellow-200 text-yellow-900 font-medium px-2 py-0.5 rounded-full text-xs">Pending</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

   
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-white backdrop-blur-lg rounded-xl shadow-lg border border-white/80 overflow-x-auto hover:shadow-xl hover:-translate-y-1 transition transform duration-300">
                <table class="min-w-full">
                    <thead class="bg-[#189ab4] text-white sticky top-0 shadow">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Status</th>
                            <th class="px-4 py-2 text-center font-semibold">Count</th>
                            <th class="px-4 py-2 text-center font-semibold">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-800">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2">Completed</td>
                            <td class="px-4 py-2 text-center">42</td>
                            <td class="px-4 py-2 text-center">70%</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2">Pending</td>
                            <td class="px-4 py-2 text-center">14</td>
                            <td class="px-4 py-2 text-center">30%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div 
    x-data="dashboardData({ 
        clinic: {{ $clinicCount }},
        appointment: {{ $appointmentCount }},
        material: {{ $materialCount }},
        caseOrder: {{ $caseOrderCount }}
    })"
    x-init="startAutoRefresh()"
    class="p-8 bg-gray-300 min-h-screen"
    style="font-family: 'Century Gothic', sans-serif;"
>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        <div @click="openModal('clinics')" class="bg-white rounded-xl shadow-lg border border-white/80 p-5 cursor-pointer transition hover:shadow-xl hover:-translate-y-2 hover:scale-105 duration-300">
            <h6 class="text-black text-xs uppercase tracking-wider">Clinics</h6>
            <h3 x-text="clinic" class="text-2xl font-bold text-blue-900 mt-2"></h3>
        </div>
        <div @click="openModal('appointments')" class="bg-white rounded-xl shadow-lg border border-white/80 p-5 cursor-pointer transition hover:shadow-xl hover:-translate-y-2 hover:scale-105 duration-300">
            <h6 class="text-black text-xs uppercase tracking-wider">Appointments</h6>
            <h3 x-text="appointment" class="text-2xl font-bold text-blue-900 mt-2"></h3>
        </div>
        <div @click="openModal('materials')" class="bg-white rounded-xl shadow-lg border border-white/80 p-5 cursor-pointer transition hover:shadow-xl hover:-translate-y-2 hover:scale-105 duration-300">
            <h6 class="text-black text-xs uppercase tracking-wider">Materials</h6>
            <h3 x-text="material" class="text-2xl font-bold text-blue-900 mt-2"></h3>
        </div>
        <div @click="openModal('caseOrders')" class="bg-white rounded-xl shadow-lg border border-white/80 p-5 cursor-pointer transition hover:shadow-xl hover:-translate-y-2 hover:scale-105 duration-300">
            <h6 class="text-black text-xs uppercase tracking-wider">Case Orders</h6>
            <h3 x-text="caseOrder" class="text-2xl font-bold text-blue-900 mt-2"></h3>
        </div>
    </div>

   
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-blue-900 mb-4">Recent Appointments</h2>
        <div class="overflow-x-auto rounded-xl shadow-lg mt-4 mx-[15px]">
            <table class="min-w-full border-separate border-spacing-0">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-4 py-2 text-left">Clinic Name</th>
                        <th class="px-4 py-2 text-left">Assigned Technician</th>
                        <th class="px-4 py-2 text-left">Schedule</th>
                        <th class="px-4 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800">
                    @forelse($recentAppointments as $appt)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $appt->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $appt->technician->name ?? 'Unassigned' }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appt->schedule_datetime)->format('M j, Y - g:i A') }}</td>
                            <td class="px-4 py-2 text-center">
                                @if(in_array(strtolower($appt->work_status), ['completed', 'finished']))
                                    <span class="bg-green-200 text-green-900 font-medium px-2 py-0.5 rounded-full text-xs">{{ ucfirst($appt->work_status) }}</span>
                                @elseif(strtolower($appt->work_status) === 'pending')
                                    <span class="bg-yellow-200 text-yellow-900 font-medium px-2 py-0.5 rounded-full text-xs">Pending</span>
                                @else
                                    <span class="bg-blue-200 text-blue-900 font-medium px-2 py-0.5 rounded-full text-xs">{{ ucfirst($appt->work_status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-black">No recent appointments</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

 
    <div x-show="showModal === 'clinics'" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white w-full max-w-3xl rounded-xl shadow-lg p-6 relative">
            <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h2 class="text-lg font-semibold text-blue-900 mb-4">Clinic List</h2>
              <div class="overflow-x-auto rounded-xl shadow-lg mt-4 mx-[15px]">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                            <th class="px-4 py-2 text-left">Clinic Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clinics as $clinic)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $clinic->clinic_name }}</td>
                                <td class="px-4 py-2">{{ $clinic->email }}</td>
                                <td class="px-4 py-2">{{ $clinic->contact_number ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  
    <div x-show="showModal === 'appointments'" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-lg p-6 relative">
            <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h2 class="text-lg font-semibold text-blue-900 mb-4">Appointment List</h2>
              <div class="overflow-x-auto rounded-xl shadow-lg mt-4 mx-[15px]">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                            <th class="px-4 py-2">Clinic</th>
                            <th class="px-4 py-2">Technician</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appt)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $appt->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $appt->technician->name ?? 'Unassigned' }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appt->schedule_datetime)->format('M j, Y - g:i A') }}</td>
                               <td class="px-4 py-2">
            @if(strtolower($appt->work_status) === 'finished' || strtolower($appt->work_status) === 'completed')
                <span class="bg-green-200 text-green-900 font-medium px-2 py-0.5 rounded-full text-xs">
                    {{ ucfirst($appt->work_status) }}
                </span>
            @elseif(strtolower($appt->work_status) === 'pending')
                <span class="bg-yellow-200 text-yellow-900 font-medium px-2 py-0.5 rounded-full text-xs">
                    Pending
                </span>
            @elseif(strtolower($appt->work_status) === 'in progress')
                <span class="bg-blue-200 text-blue-900 font-medium px-2 py-0.5 rounded-full text-xs">
                    In Progress
                </span>
            @else
                <span class="bg-gray-200 text-gray-800 font-medium px-2 py-0.5 rounded-full text-xs">
                    {{ ucfirst($appt->work_status) }}
                </span>
            @endif
        </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   
    <div x-show="showModal === 'materials'" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white w-full max-w-3xl rounded-xl shadow-lg p-6 relative">
            <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h2 class="text-lg font-semibold text-blue-900 mb-4">Material List</h2>
            <div class="overflow-y-auto max-h-[60vh]">
                <table class="min-w-full border">
                    <thead class="bg-blue-900 text-white">
                     <thead class="bg-blue-900 text-white">
    <tr>
        <th class="px-4 py-2 text-left">Material Name</th>
        <th class="px-4 py-2 text-left">Price</th>
    </tr>
</thead>

                    </thead>
                    <tbody>
                       @foreach($materials as $m)
<tr class="hover:bg-gray-50">
    <td class="px-4 py-2">{{ $m->name }}</td>
    <td class="px-4 py-2">₱{{ number_format($m->price, 2) }}</td>
</tr>
@endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="showModal === 'caseOrders'" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white w-full max-w-4xl rounded-xl shadow-lg p-6 relative">
            <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
            <h2 class="text-lg font-semibold text-blue-900 mb-4">Case Order List</h2>
            <div class="overflow-x-auto rounded-xl shadow-lg mt-4 mx-[15px]">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                            <th class="px-4 py-2">Case ID</th>
                            <th class="px-4 py-2">Clinic</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($caseOrders as $c)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $c->co_id }}</td>
                                <td class="px-4 py-2">{{ $c->clinic->clinic_name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $c->case_type ?? 'N/A' }}</td>
                                <td class="px-4 py-2">
            @php
                $status = strtolower($c->status);
            @endphp

            @if($status === 'approved')
                <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                    {{ ucfirst($c->status) }}
                </span>
            @elseif($status === 'pending')
                <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">
                    {{ ucfirst($c->status) }}
                </span>
            @elseif($status === 'rejected')
                <span class="bg-red-200 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">
                    {{ ucfirst($c->status) }}
                </span>
            @else
                <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">
                    {{ ucfirst($c->status) }}
                </span>
            @endif
        </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
function dashboardData(initial) {
    return {
        clinic: initial.clinic,
        appointment: initial.appointment,
        material: initial.material,
        caseOrder: initial.caseOrder,
        showModal: null,

        async refreshCounts() {
            try {
                const res = await fetch('/admin/dashboard/live-counts');
                const data = await res.json();
                this.clinic = data.clinicCount;
                this.appointment = data.appointmentCount;
                this.material = data.materialCount;
                this.caseOrder = data.caseOrderCount;
            } catch (err) {
                console.error('Auto-update failed:', err);
            }
        },
        startAutoRefresh() {
            this.refreshCounts();
            setInterval(() => this.refreshCounts(), 5000);
        },
        openModal(type) {
            this.showModal = type;
        },
        closeModal() {
            this.showModal = null;
        }
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

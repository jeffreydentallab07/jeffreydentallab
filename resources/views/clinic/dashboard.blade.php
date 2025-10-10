@extends('layouts.clinic')

@section('content')
<div 
    x-data="clinicDashboardData({ 
        caseOrders: {{ $caseOrdersCount }},
        appointments: {{ $appointmentsCount }},
        dentists: {{ $dentistsCount }},
        patients: {{ $patientsCount }}
    })"
    x-init="startAutoRefresh()"
    class="p-8 bg-gray-300 min-h-screen"
    style="font-family: 'Century Gothic', sans-serif;"
>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        <template x-for="(value, label) in { 'Case Orders': caseOrders, 'Appointments': appointments, 'Dentists': dentists, 'Patients': patients }" :key="label">
            <div class="bg-white rounded-xl shadow-lg border border-white/80 p-5 transition hover:shadow-xl hover:-translate-y-2 hover:scale-105 duration-300">
                <h6 class="text-black text-xs uppercase tracking-wider" x-text="label"></h6>
                <h3 class="text-2xl font-bold text-blue-900 mt-2" x-text="value"></h3>
            </div>
        </template>
    </div>
  
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-blue-900 mb-4">Recent Case Orders</h2>
        <div class="overflow-y-auto max-h-96 rounded-xl shadow-inner">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="sticky top-0 bg-blue-900 text-white z-10">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">Case ID</th>
                        <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">Patient Name</th>
                        <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800 bg-white">
                    @forelse ($recentCases as $case)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $case->co_id }}</td>
                            <td class="px-6 py-3">{{ $case->patient_name ?? 'N/A' }}</td>
                            <td class="px-6 py-3">
                                @php $status = strtolower($case->status); @endphp
                                @if($status === 'approved')
                                    <span class="bg-green-200 text-green-900 font-medium px-2 py-1 rounded-full text-xs">Approved</span>
                                @elseif($status === 'pending')
                                    <span class="bg-yellow-200 text-yellow-900 font-medium px-2 py-1 rounded-full text-xs">Pending</span>
                                @elseif($status === 'rejected')
                                    <span class="bg-red-200 text-red-900 font-medium px-2 py-1 rounded-full text-xs">Rejected</span>
                                @else
                                    <span class="bg-gray-200 text-gray-900 font-medium px-2 py-1 rounded-full text-xs">{{ ucfirst($case->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">{{ \Carbon\Carbon::parse($case->created_at)->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-black">No case orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>


<script>
function clinicDashboardData(initial) {
    return {
        caseOrders: initial.caseOrders,
        appointments: initial.appointments,
        dentists: initial.dentists,
        patients: initial.patients,

        async refreshCounts() {
            try {
                const res = await fetch("{{ route('clinic.liveCounts') }}");
                const data = await res.json();
                this.caseOrders = data.caseOrdersCount;
                this.appointments = data.appointmentsCount;
                this.dentists = data.dentistsCount;
                this.patients = data.patientsCount;

               
                if (Array.isArray(data.dentistReports)) {
                    const container = document.getElementById('dentistReportsContainer');
                    container.innerHTML = data.dentistReports.map(d => `
                        <div class="p-6 border rounded-2xl bg-white shadow hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer"
                             onclick="window.location.href='/clinic/dentists/${d.dentist_id}'">
                            <h3 class="text-lg text-blue-900 font-semibold mb-1">${d.dentist_name}</h3>
                            <p class="text-gray-700 text-sm">Total Cases: <span class="font-semibold">${d.total_cases}</span></p>
                            <p class="text-green-700 text-sm">Completed: <span class="font-semibold">${d.completed_cases}</span></p>
                            <p class="text-yellow-600 text-sm">Pending: <span class="font-semibold">${d.pending_cases}</span></p>
                        </div>
                    `).join('');
                }
            } catch (err) {
                console.error('Auto-refresh failed:', err);
            }
        },

        startAutoRefresh() {
            this.refreshCounts();
            setInterval(() => this.refreshCounts(), 5000);
        }
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

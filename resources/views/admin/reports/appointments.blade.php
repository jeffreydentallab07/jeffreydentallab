@extends('layouts.app')

@section('title', 'Appointments Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen text-sm">
    <h1 class="text-xl font-bold mb-4">📅 Appointments Report</h1>

    <!-- ✅ Filter Form -->
    <form id="filterForm" method="GET" action="{{ route('reports.appointments') }}"
          class="mb-6 bg-white p-4 rounded shadow flex flex-wrap gap-4 items-end">

        <!-- Clinic -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">Clinic</label>
            <select name="clinic_id" class="p-2 border rounded auto-submit">
                <option value="">All Clinics</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->id }}" {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                        {{ $clinic->clinic_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Dentist -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">Dentist</label>
            <select name="dentist_id" class="p-2 border rounded auto-submit">
                <option value="">All Dentists</option>
                @foreach($dentists as $dentist)
                    <option value="{{ $dentist->id }}" {{ request('dentist_id') == $dentist->id ? 'selected' : '' }}>
                        {{ $dentist->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Technician -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">Technician</label>
            <select name="technician_id" class="p-2 border rounded auto-submit">
                <option value="">All Technicians</option>
                @foreach($technicians as $tech)
                    <option value="{{ $tech->id }}" {{ request('technician_id') == $tech->id ? 'selected' : '' }}>
                        {{ $tech->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">Status</label>
            <select name="work_status" class="p-2 border rounded auto-submit">
                <option value="">All Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('work_status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- From -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="border p-2 rounded w-44 auto-submit">
        </div>

        <!-- To -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="border p-2 rounded w-44 auto-submit">
        </div>

        <!-- Reset -->
        <div class="flex gap-2">
            <button type="button" id="resetBtn" class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">
                Reset
            </button>
        </div>
    </form>

    <!-- ✅ Report Table -->
    <div id="reportTable">
        <table class="min-w-full bg-white rounded shadow text-sm">
            <thead>
                <tr class="bg-gray-200 text-left text-xs uppercase tracking-wider">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Case Type</th>
                    <th class="px-4 py-2">Clinic</th>
                    <th class="px-4 py-2">Dentist</th>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Technician</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Schedule</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $appt->id }}</td>
                        <td class="px-4 py-2">{{ $appt->caseOrder->case_type ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $appt->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $appt->caseOrder->dentist->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $appt->caseOrder->patient->full_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $appt->technician->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ ucfirst($appt->work_status) }}</td>
                        <td class="px-4 py-2">{{ $appt->schedule_datetime->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">No appointments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- ✅ Pagination -->
        <div class="mt-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>

<!-- ✅ AJAX Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');

    const clinicSelect = filterForm.querySelector('select[name="clinic_id"]');
    const dentistSelect = filterForm.querySelector('select[name="dentist_id"]');

    // Auto-submit on any filter change
    filterForm.querySelectorAll('select, input[type="date"]').forEach(el => {
        el.addEventListener('change', applyFilter);
    });

    // Reset
    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        dentistSelect.innerHTML = `<option value="">All Dentists</option>`;
        applyFilter();
    });

    // Update dentists when clinic changes
    clinicSelect.addEventListener('change', function() {
        const clinicId = this.value;
        dentistSelect.innerHTML = `<option value="">All Dentists</option>`;

        if (!clinicId) {
            applyFilter();
            return;
        }

        fetch(`/appointments/dentists/${clinicId}`)
            .then(res => res.json())
            .then(data => {
                let options = `<option value="">All Dentists</option>`;
                data.forEach(d => options += `<option value="${d.id}">${d.name}</option>`);
                dentistSelect.innerHTML = options;
                applyFilter();
            })
            .catch(err => console.error("Dentist fetch error:", err));
    });

    function applyFilter(page = 1) {
        const params = new URLSearchParams(new FormData(filterForm)).toString();
        fetch(`${filterForm.action}?${params}&page=${page}`, { headers: { "X-Requested-With": "XMLHttpRequest" }})
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                reportTable.innerHTML = doc.querySelector('#reportTable').innerHTML;
                bindPagination();
            })
            .catch(err => console.error("AJAX Filter Error:", err));
    }

    function bindPagination() {
        document.querySelectorAll('#reportTable .pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = new URL(this.href).searchParams.get('page');
                applyFilter(page);
            });
        });
    }

    bindPagination();
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Billings Report')

@section('content')
<div class="p-6 bg-gray-300 min-h-screen text-sm">
    <h1 class="text-xl font-bold mb-4">Billings Report</h1>

    <!-- ✅ Filter Form -->
    <form id="filterForm" method="GET" action="{{ route('reports.billings') }}"
          class="mb-6 p-4 rounded-lg flex flex-wrap gap-4 items-end bg-gray-200 shadow-inner border border-gray-400">

        <!-- Clinic Filter -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">Clinic</label>
            <select name="clinic_id"
                    class="p-2 border border-gray-300 rounded bg-gray-50 shadow-inner focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
                <option value="">All Clinics</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->clinic_id }}" {{ request('clinic_id') == $clinic->clinic_id ? 'selected' : '' }}>
                        {{ $clinic->clinic_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- From Date -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="border border-gray-300 rounded bg-gray-50 shadow-inner p-2 w-44 focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
        </div>

        <!-- To Date -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   class="border border-gray-300 rounded bg-gray-50 shadow-inner p-2 w-44 focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
        </div>

        <!-- Buttons -->
        <div class="flex flex-wrap gap-2 items-center mt-2">
            <!-- Reset -->
            <button type="button" id="resetBtn"
                class="bg-gray-500 text-white px-4 py-2 rounded shadow-[inset_1px_1px_2px_rgba(255,255,255,0.4),inset_-2px_-2px_3px_rgba(0,0,0,0.2)] hover:shadow-md hover:bg-gray-600 transition">
                Reset
            </button>

            <!-- Save Options Dropdown -->
            <div class="relative inline-block text-left">
                <button id="saveOptionsBtn" type="button"
                    class="bg-blue-600 text-white px-4 py-2 rounded shadow-[inset_1px_1px_2px_rgba(255,255,255,0.3),inset_-2px_-2px_3px_rgba(0,0,0,0.25)] hover:shadow-md hover:bg-blue-700 transition flex items-center gap-1">
                    💾 Save Options
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="saveDropdown"
                     class="hidden absolute mt-1 w-44 bg-white border border-gray-300 rounded shadow-lg z-50">
                    <a href="{{ route('reports.billings.export', ['type' => 'pdf'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📄 Save as PDF</a>
                    <a href="{{ route('reports.billings.export', ['type' => 'word'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📝 Save as Word</a>
                    <a href="{{ route('reports.billings.export', ['type' => 'excel'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📊 Save as Excel</a>
                </div>
            </div>

            <!-- Print -->
            <button onclick="window.print()" type="button"
                class="bg-gray-700 text-white px-4 py-2 rounded shadow-[inset_1px_1px_2px_rgba(255,255,255,0.3),inset_-2px_-2px_3px_rgba(0,0,0,0.3)] hover:shadow-md hover:bg-gray-800 transition text-xs">
                🖨️ Print Report
            </button>
        </div>
    </form>

    <!-- ✅ Report Table -->
    <div id="reportTable">
        <div class="overflow-x-auto rounded-xl shadow-lg mt-4 max-h-[60vh] overflow-y-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="sticky top-0 bg-blue-900 text-white z-10">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Clinic</th>
                        <th class="px-4 py-2">Appointment ID</th>
                        <th class="px-4 py-2">Created At</th>
                        <th class="px-4 py-2">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($billings as $billing)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $billing->billing_id }}</td>
                            <td class="px-4 py-2">{{ $billing->appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $billing->appointment_id }}</td>
                            <td class="px-4 py-2">{{ $billing->created_at->format('F j, Y g:i A') }}</td>
                            <td class="px-4 py-2 font-semibold text-right">₱{{ number_format($billing->total_amount, 2) }}
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                No billings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $billings->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');
    const btn = document.getElementById('saveOptionsBtn');
    const dropdown = document.getElementById('saveDropdown');

    // Dropdown toggle
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });
    document.addEventListener('click', e => {
        if (!btn.contains(e.target) && !dropdown.contains(e.target))
            dropdown.classList.add('hidden');
    });

    // Auto-submit filters
    document.querySelectorAll('.auto-submit').forEach(el => {
        el.addEventListener('change', () => applyFilter());
    });

    resetBtn.addEventListener('click', () => {
        filterForm.reset();
        applyFilter();
    });

    function applyFilter(page = 1) {
        const params = new URLSearchParams(new FormData(filterForm)).toString();
        fetch(`${filterForm.action}?${params}&page=${page}`, {
            headers: {"X-Requested-With": "XMLHttpRequest"}
        })
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

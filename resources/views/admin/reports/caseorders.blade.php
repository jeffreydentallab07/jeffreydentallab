@extends('layouts.app')

@section('title', 'Case Orders Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen text-sm">
    <h1 class="text-xl font-bold mb-4">📊 Case Orders Report</h1>

    <!-- ✅ Filter Form -->
    <form id="filterForm" method="GET" action="{{ route('reports.caseorders') }}"
          class="mb-6 bg-white p-4 rounded shadow flex flex-wrap gap-4 items-end">

        <!-- Clinic Filter -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">Clinic</label>
            <select name="clinic_id" class="p-2 border rounded auto-submit">
                <option value="">All Clinics</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->clinic_id }}" {{ request('clinic_id') == $clinic->clinic_id ? 'selected' : '' }}>
                        {{ $clinic->clinic_name }}
                    </option>
                @endforeach
            </select>
        </div>

       <!-- Status Filter -->
<div>
    <label class="block text-gray-600 text-xs mb-1">Status</label>
    <select name="status" class="p-2 border rounded auto-submit">
        <option value="">All Status</option>
        @foreach($statuses as $status)
            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                {{ ucwords(str_replace('_', ' ', $status)) }}
            </option>
        @endforeach
    </select>
</div>


        <!-- Date From -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" 
                   class="border p-2 rounded w-44 auto-submit">
        </div>

        <!-- Date To -->
        <div>
            <label class="block text-gray-600 text-xs mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" 
                   class="border p-2 rounded w-44 auto-submit">
        </div>

        <!-- Reset Button -->
        <div class="flex gap-2">
            <button type="button" id="resetBtn"
               class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">
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
                    <th class="px-4 py-2">Status / Delivery</th>
                    <th class="px-4 py-2">Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($caseOrders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $order->co_id }}</td>
                        <td class="px-4 py-2">{{ $order->case_type }}</td>
                        <td class="px-4 py-2">{{ $order->clinic->clinic_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $order->dentist->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $order->patient->full_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <span class="
                                px-2 py-1 rounded text-xs 
                                @if($order->status == 'pending') bg-yellow-200 text-yellow-800 
                                @elseif($order->status == 'in-progress') bg-blue-200 text-blue-800
                                @elseif($order->status == 'completed') bg-green-200 text-green-800
                                @else bg-gray-200 text-gray-600
                                @endif
                            ">
                                @if($order->status !== 'completed')
                                    {{ ucfirst($order->status) }}
                                @else
                                    {{ $order->delivery->delivery_status ?? 'No delivery info' }}
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                            No case orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- ✅ Pagination -->
        <div class="mt-4">
            {{ $caseOrders->links() }}
        </div>
    </div>
</div>

<!-- ✅ AJAX Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');

    // Auto-submit with AJAX
    document.querySelectorAll('.auto-submit').forEach(element => {
        element.addEventListener('change', function() {
            applyFilter();
        });
    });

    // Reset filter
    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        applyFilter();
    });

    // Function to apply filter via AJAX
    function applyFilter(page = 1) {
        let formData = new FormData(filterForm);
        let params = new URLSearchParams(formData).toString();

        fetch(`${filterForm.action}?${params}&page=${page}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
            let newTable = doc.querySelector('#reportTable').innerHTML;
            reportTable.innerHTML = newTable;
            bindPagination();
        })
        .catch(err => console.error("AJAX Filter Error:", err));
    }

    function bindPagination() {
        document.querySelectorAll('#reportTable .pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page');
                applyFilter(page);
            });
        });
    }

    bindPagination();
});
</script>
@endsection

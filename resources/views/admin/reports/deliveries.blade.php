@extends('layouts.app')

@section('title', 'Deliveries Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-xl font-bold mb-4">🚚 Deliveries Report</h1>

  
    <form id="filterForm" method="GET" action="{{ route('reports.deliveries') }}"
          class="mb-6 bg-white p-4 rounded shadow flex flex-wrap gap-4 items-end">

      
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

     
        <div>
            <label class="block text-gray-600 text-xs mb-1">Status</label>
            <select name="status" class="p-2 border rounded auto-submit">
                <option value="">All Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

      
        <div>
            <label class="block text-gray-600 text-xs mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="border p-2 rounded w-44 auto-submit">
        </div>

      
        <div>
            <label class="block text-gray-600 text-xs mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="border p-2 rounded w-44 auto-submit">
        </div>

     
        <div class="flex gap-2">
            <button type="button" id="resetBtn" class="bg-gray-500 text-white px-4 py-2 text-left text-sm font-medium rounded shadow hover:bg-gray-600">
                Reset
            </button>
        </div>
    </form>

   
    <div id="reportTable">
        <table class="min-w-full border-separate border-spacing-0">
        <thead>
            <tr class="bg-blue-900 text-white">
                    <th class="px-4 py-2 text-left text-sm font-medium">Delivery ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Case Order</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Clinic</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Delivery Date</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                       <tr class="bg-white hover:bg-gray-50">
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $delivery->delivery_id }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $delivery->appointment->caseOrder->co_id ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $delivery->appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $delivery->delivery_date ? $delivery->delivery_date->format('Y-m-d') : 'N/A' }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ ucfirst($delivery->delivery_status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No deliveries found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

      
        <div class="mt-4">
            {{ $deliveries->links() }}
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');

   
    filterForm.querySelectorAll('select, input[type="date"]').forEach(el => {
        el.addEventListener('change', applyFilter);
    });

  
    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        applyFilter();
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

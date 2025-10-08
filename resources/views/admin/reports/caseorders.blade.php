@extends('layouts.app')

@section('title', 'Case Orders Report')

@section('content')
<div class="p-6 bg-gray-300 min-h-screen text-sm">
    <h1 class="text-xl font-bold mb-4">Case Orders Report</h1>
<form id="filterForm" method="GET" action="{{ route('reports.caseorders') }}"
      class="mb-6 p-4 rounded-lg flex flex-wrap gap-4 items-end bg-gray-200 shadow-inner border border-gray-400">

    
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

 
    <div>
        <label class="block text-gray-600 text-xs mb-1">Status</label>
        <select name="status"
                class="p-2 border border-gray-300 rounded bg-gray-50 shadow-inner focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
            <option value="">All Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
    </div>


    <div>
        <label class="block text-gray-600 text-xs mb-1">From</label>
        <input type="date" name="from" value="{{ request('from') }}"
               class="border border-gray-300 rounded bg-gray-50 shadow-inner p-2 w-44 focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
    </div>

    <div>
        <label class="block text-gray-600 text-xs mb-1">To</label>
        <input type="date" name="to" value="{{ request('to') }}"
               class="border border-gray-300 rounded bg-gray-50 shadow-inner p-2 w-44 focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
    </div>

    <div class="flex flex-wrap gap-2 items-center mt-2">

 
        <button type="button" id="resetBtn"
            class="bg-gray-500 text-white px-4 py-2 rounded shadow-[inset_1px_1px_2px_rgba(255,255,255,0.4),inset_-2px_-2px_3px_rgba(0,0,0,0.2)] hover:shadow-md hover:bg-gray-600 transition">
            Reset
        </button>

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
                <a href="{{ route('reports.caseorders.export', ['type' => 'pdf'] + request()->all()) }}"
                   class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📄 Save as PDF</a>
                <a href="{{ route('reports.caseorders.export', ['type' => 'word'] + request()->all()) }}"
                   class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📝 Save as Word</a>
                <a href="{{ route('reports.caseorders.export', ['type' => 'excel'] + request()->all()) }}"
                   class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📊 Save as Excel</a>
            </div>
        </div>

   
        <button onclick="window.print()" type="button"
            class="bg-gray-700 text-white px-4 py-2 rounded shadow-[inset_1px_1px_2px_rgba(255,255,255,0.3),inset_-2px_-2px_3px_rgba(0,0,0,0.3)] hover:shadow-md hover:bg-gray-800 transition text-xs">
            🖨️ Print Report
        </button>
    </div>
</form>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('saveOptionsBtn');
    const dropdown = document.getElementById('saveDropdown');

    btn.addEventListener('click', function() {
        dropdown.classList.toggle('hidden');
    });


    document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>

    <div id="reportTable">
       <div class="overflow-x-auto rounded-xl shadow-lg mt-4 max-h-[60vh] overflow-y-auto">
    <table class="min-w-full bg-white border border-gray-200">
        <thead class="sticky top-0 bg-blue-900 text-white z-10">
            <tr>
                    <th class="px-4 py-2">Case Order ID</th>
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
                       <td class="px-4 py-2">{{ $order->created_at->format('F j, Y') }}</td>
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

        <div class="mt-4">
            {{ $caseOrders->links() }}
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');


    document.querySelectorAll('.auto-submit').forEach(el => {
        el.addEventListener('change', () => applyFilter());
    });

    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        applyFilter();
    });

    function applyFilter(page = 1) {
        let formData = new FormData(filterForm);
        let params = new URLSearchParams(formData).toString();

        fetch(`${filterForm.action}?${params}&page=${page}`, {
            headers: {"X-Requested-With": "XMLHttpRequest"}
        })
        .then(res => res.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
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

@extends('layouts.app')

@section('title', 'Case Orders Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen text-sm">
    <h1 class="text-xl font-bold mb-4"> Case Orders Report</h1>

 
    <form id="filterForm" method="GET" action="{{ route('reports.caseorders') }}"
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
                {{ ucwords(str_replace('_', ' ', $status)) }}
            </option>
        @endforeach
    </select>
</div>


        
        <div>
            <label class="block text-gray-600 text-xs mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" 
                   class="border p-2 rounded w-44 auto-submit">
        </div>

     
        <div>
            <label class="block text-gray-600 text-xs mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" 
                   class="border p-2 rounded w-44 auto-submit">
        </div>

       
        <div class="flex gap-2">
            <button type="button" id="resetBtn"
               class="bg-gray-500 text-white px-4 py-2 text-left text-sm font-medium rounded shadow hover:bg-gray-600">
                Reset
            </button>
        </div>
    </form>

    
    <div id="reportTable">
       <table class="min-w-full border-separate border-spacing-0">
        <thead>
            <tr class="bg-blue-900 text-white">
                    <th class="px-4 py-2 text-left text-sm font-medium">ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Case Type</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Clinic</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Dentist</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Patient</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Status / Delivery</th>
                    <th class="px-4 py-2 text-left text-sm font-medium">Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($caseOrders as $order)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $order->co_id }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $order->case_type }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $order->clinic->clinic_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $order->dentist->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-left text-sm font-medium">{{ $order->patient->full_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium">
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
                        <td class="px-4 py-2 text-left text-sm font-medium">{{ $order->created_at->format('Y-m-d') }}</td>
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

   
    document.querySelectorAll('.auto-submit').forEach(element => {
        element.addEventListener('change', function() {
            applyFilter();
        });
    });

  
    resetBtn.addEventListener('click', function() {
        filterForm.reset();
        applyFilter();
    });

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

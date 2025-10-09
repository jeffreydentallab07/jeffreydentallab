@extends('layouts.app')

@section('title', 'Deliveries Report')

@section('content')
<div class="p-6 bg-gray-300 min-h-screen text-sm">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center justify-center bg-white border border-gray-300 rounded-full p-2 shadow hover:bg-gray-100 transition focus:ring-2 focus:ring-blue-400 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                 class="w-4 h-4 text-gray-700">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
      <h1 class="text-xl font-bold mb-4"> Deliveries Report</h1>
    </div>

   
    <form id="filterForm" method="GET" action="{{ route('reports.deliveries') }}"
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
                    <a href="{{ route('reports.deliveries.export', ['type' => 'pdf'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📄 Save as PDF</a>
                    <a href="{{ route('reports.deliveries.export', ['type' => 'word'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📝 Save as Word</a>
                    <a href="{{ route('reports.deliveries.export', ['type' => 'excel'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📊 Save as Excel</a>
                </div>
            </div>

            
            <button onclick="window.print()" type="button"
                class="bg-gray-700 text-white px-4 py-2 rounded shadow-[inset_1px_1px_2px_rgba(255,255,255,0.3),inset_-2px_-2px_3px_rgba(0,0,0,0.3)] hover:shadow-md hover:bg-gray-800 transition text-xs">
                🖨️ Print Report
            </button>
        </div>
    </form>

    
    <div id="reportTable">
        <div class="overflow-x-auto rounded-xl shadow-lg mt-4 max-h-[60vh] overflow-y-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="sticky top-0 bg-blue-900 text-white z-10">
                     <tr class="text-left">
                        <th class="px-4 py-2">Delivery ID</th>
                        <th class="px-4 py-2">Case Order</th>
                        <th class="px-4 py-2">Clinic</th>
                        <th class="px-4 py-2">Delivery Date</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $delivery->delivery_id }}</td>
                            <td class="px-6 py-3 text-left">
    @if($delivery->appointment && $delivery->appointment->caseOrder)
        <button type="button"
                onclick="openModal('modal{{ $delivery->appointment->caseOrder->co_id }}')"
                class="text-blue-600 hover:underline">
            {{ 'CASE-' . str_pad($delivery->appointment->caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
        </button>
    @else
        <span class="text-gray-500">N/A</span>
    @endif
</td>

@if($delivery->appointment && $delivery->appointment->caseOrder)
    @php $caseOrder = $delivery->appointment->caseOrder; @endphp
    <div id="modal{{ $caseOrder->co_id }}" 
         class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-2">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative font-sans"
             role="dialog" 
             aria-labelledby="modalTitle{{ $caseOrder->co_id }}" 
             aria-modal="true">

          
            <div class="p-4 bg-blue-900 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md"
                             src="{{ $caseOrder->clinic->profile_photo 
                                 ? asset('storage/uploads/clinic_photos/' . $caseOrder->clinic->profile_photo) 
                                 : asset('images/user.png') }}"
                             alt="{{ $caseOrder->clinic->clinic_name }}">
                    </div>
                    <div>
                       <h2 id="modalTitle{{ $caseOrder->co_id }}" 
    class="text-xl font-semibold text-white">{{ $caseOrder->clinic->clinic_name }}</h2>
<p class="text-xs text-blue-100">{{ $caseOrder->clinic->address }}</p>
<p class="text-xs text-blue-100">Contact: {{ $caseOrder->clinic->contact_number }}</p>

                    </div>
                </div>
                <button onclick="closeModal('modal{{ $caseOrder->co_id }}')" 
                        class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
            </div>

          
            <div class="p-4 space-y-4 text-sm text-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Case No.</label>
                        <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Case Type</label>
                        <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $caseOrder->case_type }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Patient Name</label>
                        <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $caseOrder->patient?->patient_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 text-xs">Dentist Name</label>
                        <p class="mt-1 px-2 py-1 border rounded bg-gray-100">
                            {{ $caseOrder->patient?->dentist ? 'Dr. ' . $caseOrder->patient->dentist->name : 'N/A' }}
                        </p>
                    </div>
                    <div class="col-span-2">
                        <label class="block font-medium text-gray-700 text-xs">Notes</label>
                        <p class="mt-1 px-2 py-1 border rounded bg-gray-100 whitespace-pre-line">{{ $caseOrder->notes ?? 'N/A' }}</p>
                    </div>
                    <div class="col-span-2">
                        <label class="block font-medium text-gray-700 text-xs">Created At</label>
                        <p class="mt-1 px-2 py-1 border rounded bg-gray-100">
                            {{ \Carbon\Carbon::parse($caseOrder->created_at)->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>

              
                <div class="flex justify-end space-x-3 mt-3">
                    <button type="button" 
                            onclick="closeModal('modal{{ $caseOrder->co_id }}')" 
                            class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">
                        Back
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

                            <td class="px-4 py-2">{{ $delivery->appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                {{ $delivery->created_at ? $delivery->created_at->format('F j, Y') : 'N/A' }}
                            </td>
                          <td class="px-4 py-2">
    <span class="
        px-2 py-1 rounded text-xs
        @if($delivery->delivery_status == 'pending') bg-yellow-200 text-yellow-800
        @elseif($delivery->delivery_status == 'in-transit') bg-blue-200 text-blue-800
        @elseif($delivery->delivery_status == 'finished') bg-green-200 text-green-800
        @elseif($delivery->delivery_status == 'delivered') bg-green-100 text-green-700 font-semibold
        @else bg-gray-200 text-gray-600
        @endif
    ">
        {{ ucfirst($delivery->delivery_status) }}
    </span>
</td>


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
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');
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

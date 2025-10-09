@extends('layouts.app')

@section('title', 'Riders Report')

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
        <h1 class="text-xl font-bold mb-4">Riders Report</h1>
    </div>

    <form id="filterForm" method="GET" action="{{ route('reports.riders') }}"
          class="mb-6 p-4 rounded-lg flex flex-wrap gap-4 items-end bg-gray-200 shadow-inner border border-gray-400">

        {{-- Clinic Filter --}}
        <div>
            <label class="block text-gray-600 text-xs mb-1">Clinic</label>
            <select name="clinic_id"
                    class="p-2 border border-gray-300 rounded bg-gray-50 shadow-inner focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
                <option value="">All Clinics</option>
                @foreach($clinics as $clinic)
                    <option value="{{ $clinic->id }}" {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                        {{ $clinic->clinic_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Rider Name Search --}}
        <div>
            <label class="block text-gray-600 text-xs mb-1">Rider Name</label>
            <input type="text" name="rider_name" placeholder="Search Rider..."
                   value="{{ request('rider_name') }}"
                   class="p-2 border border-gray-300 rounded bg-gray-50 shadow-inner focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
        </div>

        {{-- Date Range --}}
        <div>
            <label class="block text-gray-600 text-xs mb-1">From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="p-2 border border-gray-300 rounded bg-gray-50 auto-submit">
        </div>
        <div>
            <label class="block text-gray-600 text-xs mb-1">To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="p-2 border border-gray-300 rounded bg-gray-50 auto-submit">
        </div>

        {{-- Delivery Status --}}
        <div>
            <label class="block text-gray-600 text-xs mb-1">Status</label>
            <select name="status"
                    class="p-2 border border-gray-300 rounded bg-gray-50 shadow-inner focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            </select>
        </div>

        {{-- Delivery Count Range --}}
        <div>
            <label class="block text-gray-600 text-xs mb-1">Min Deliveries</label>
            <input type="number" name="min_deliveries" placeholder="0" value="{{ request('min_deliveries') }}"
                   class="p-2 border border-gray-300 rounded bg-gray-50 auto-submit w-28">
        </div>

        <div>
            <label class="block text-gray-600 text-xs mb-1">Max Deliveries</label>
            <input type="number" name="max_deliveries" placeholder="100" value="{{ request('max_deliveries') }}"
                   class="p-2 border border-gray-300 rounded bg-gray-50 auto-submit w-28">
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap gap-2 items-center mt-2">
            <button type="button" id="resetBtn"
                class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition">
                Reset
            </button>

            <div class="relative inline-block text-left">
                <button id="saveOptionsBtn" type="button"
                    class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition flex items-center gap-1">
                    💾 Save Options
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="saveDropdown"
                     class="hidden absolute mt-1 w-44 bg-white border border-gray-300 rounded shadow-lg z-50">
                    <a href="{{ route('reports.riders.export', ['type' => 'pdf'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📄 Save as PDF</a>
                    <a href="{{ route('reports.riders.export', ['type' => 'word'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📝 Save as Word</a>
                    <a href="{{ route('reports.riders.export', ['type' => 'excel'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📊 Save as Excel</a>
                </div>
            </div>

            <a href="{{ route('reports.riders.print', request()->query()) }}" target="_blank"
                class="bg-gray-700 text-white px-4 py-2 rounded shadow hover:bg-gray-800 transition text-xs">
                🖨️ Print Report
            </a>
        </div>
    </form>

    {{-- Riders Table --}}
    <div id="ridersTable">
        <div class="overflow-x-auto rounded-xl shadow-lg mt-4 max-h-[60vh] overflow-y-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="sticky top-0 bg-blue-900 text-white z-10">
                    <tr>
                        <th class="px-4 py-2">Rider ID</th>
                        <th class="px-4 py-2">Rider Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Contact Number</th>
                        <th class="px-4 py-2 text-center">Deliveries Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riders as $rider)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">{{ $rider->id }}</td>
                            <td class="px-4 py-2">{{ $rider->name }}</td>
                            <td class="px-4 py-2">{{ $rider->email }}</td>
                            <td class="px-4 py-2">{{ $rider->contact_number ?? '-' }}</td>
                            <td class="px-4 py-2 text-center font-semibold">{{ $rider->deliveries_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                No riders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $riders->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('ridersTable');
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
        if (el.tagName === "INPUT" && el.type === "text") {
            el.addEventListener('keyup', () => applyFilter());
        }
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
            reportTable.innerHTML = doc.querySelector('#ridersTable').innerHTML;
            bindPagination();
        })
        .catch(err => console.error("AJAX Filter Error:", err));
    }

    function bindPagination() {
        document.querySelectorAll('#ridersTable .pagination a').forEach(link => {
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

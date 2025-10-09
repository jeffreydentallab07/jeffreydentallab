@extends('layouts.app')

@section('title', 'Clinics Report')

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
        <h1 class="text-xl font-bold mb-4">Clinics Report</h1>
    </div>

    <form id="filterForm" method="GET" action="{{ route('reports.clinics') }}"
          class="mb-6 p-4 rounded-lg flex flex-wrap gap-4 items-end bg-gray-200 shadow-inner border border-gray-400">

        <div>
            <label class="block text-gray-600 text-xs mb-1">Search Clinic</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Enter clinic name..."
                   class="border border-gray-300 rounded bg-gray-50 shadow-inner p-2 w-56 focus:outline-none focus:ring-2 focus:ring-blue-400 auto-submit">
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
                    <a href="{{ route('reports.clinics.export', ['type' => 'pdf'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📄 Save as PDF</a>
                    <a href="{{ route('reports.clinics.export', ['type' => 'word'] + request()->all()) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100 text-gray-700">📝 Save as Word</a>
                    <a href="{{ route('reports.clinics.export', ['type' => 'excel'] + request()->all()) }}"
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
                    <tr>
                        <th class="px-4 py-2">Clinic ID</th>
                        <th class="px-4 py-2">Clinic Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Case Orders</th>
                        <th class="px-4 py-2">Appointments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clinics as $clinic)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $clinic->clinic_id }}</td>
                            <td class="px-4 py-2">{{ $clinic->clinic_name }}</td>
                            <td class="px-4 py-2">{{ $clinic->email }}</td>
                            <td class="px-4 py-2">{{ $clinic->case_orders_count }}</td>
                            <td class="px-4 py-2">{{ $clinic->appointments_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                No clinics found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $clinics->links() }}
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

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });
    document.addEventListener('click', e => {
        if (!btn.contains(e.target) && !dropdown.contains(e.target))
            dropdown.classList.add('hidden');
    });

    
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

@extends('layouts.app')

@section('title', 'Technicians Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen text-sm">
    <h1 class="text-xl font-bold mb-4">Technicians Report</h1>

   
    <form id="filterForm" action="{{ route('reports.technicians') }}" method="GET" class="mb-4 flex items-center space-x-2">
        <input type="text" name="search" placeholder="Search by name/email..." class="px-4 py-2 text-left text-sm font-medium text-left rounded">
        <button type="button" id="resetBtn" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Reset</button>
    </form>

   
    <div id="reportTable">
       <table class="min-w-full border-separate border-spacing-0">
        <thead>
            <tr class="bg-blue-900 text-white">
                    <th class="px-4 py-2 text-left text-sm font-medium text-left">#</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-left">Name</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-left">Email</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-left">Contact Number</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-left">Appointments Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($technicians as $tech)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-4 py-2 text-left text-sm font-medium text-left">{{ $loop->iteration + ($technicians->currentPage() - 1) * $technicians->perPage() }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium text-left">{{ $tech->name }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium text-left">{{ $tech->email }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium text-left">{{ $tech->contact_number }}</td>
                        <td class="px-4 py-2 text-left text-sm font-medium text-left">{{ $tech->appointments_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-left text-sm font-medium text-left text-center">No technicians found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $technicians->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');

    filterForm.querySelectorAll('input').forEach(el => el.addEventListener('input', applyFilter));

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

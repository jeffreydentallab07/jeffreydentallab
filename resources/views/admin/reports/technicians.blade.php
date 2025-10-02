@extends('layouts.app')

@section('title', 'Technicians Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen text-sm">
    <h1 class="text-xl font-bold mb-4">🛠️ Technicians Report</h1>

    <!-- ✅ Filter Form -->
    <form id="filterForm" action="{{ route('reports.technicians') }}" method="GET" class="mb-4 flex items-center space-x-2">
        <input type="text" name="search" placeholder="Search by name/email..." class="p-2 border rounded">
        <button type="button" id="resetBtn" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Reset</button>
    </form>

    <!-- ✅ Table Container -->
    <div id="reportTable">
        <table class="w-full table-auto border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Contact Number</th>
                    <th class="p-2 border">Appointments Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($technicians as $tech)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration + ($technicians->currentPage() - 1) * $technicians->perPage() }}</td>
                        <td class="p-2 border">{{ $tech->name }}</td>
                        <td class="p-2 border">{{ $tech->email }}</td>
                        <td class="p-2 border">{{ $tech->contact_number }}</td>
                        <td class="p-2 border">{{ $tech->appointments_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-2 border text-center">No technicians found.</td>
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

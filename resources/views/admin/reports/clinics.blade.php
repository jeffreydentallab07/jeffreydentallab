@extends('layouts.app')

@section('title', 'Clinics Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-xl font-bold mb-4">Clinics Report</h1>

    <table class="min-w-full bg-white rounded shadow text-sm" id="reportTable">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="px-4 py-2">Clinic ID</th>
                <th class="px-4 py-2">Clinic Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Case Orders</th>
                <th class="px-4 py-2">Appointments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clinics as $clinic)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $clinic->clinic_id }}</td>
                    <td class="px-4 py-2">{{ $clinic->clinic_name }}</td>
                    <td class="px-4 py-2">{{ $clinic->email }}</td>
                    <td class="px-4 py-2">{{ $clinic->case_orders_count }}</td>
                    <td class="px-4 py-2">{{ $clinic->appointments_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $clinics->links() }}
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterForm = document.getElementById('filterForm');
    const reportTable = document.getElementById('reportTable');
    const resetBtn = document.getElementById('resetBtn');

    // Auto-submit on input change
    document.querySelectorAll('.auto-submit').forEach(el => el.addEventListener('input', applyFilter));

    resetBtn?.addEventListener('click', function() {
        filterForm.reset();
        applyFilter();
    });

    function applyFilter(page = 1) {
        const params = new URLSearchParams(new FormData(filterForm)).toString();
        fetch(`${filterForm.action}?${params}&page=${page}`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
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

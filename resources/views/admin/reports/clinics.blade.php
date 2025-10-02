@extends('layouts.app')

@section('title', 'Clinics Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-xl font-bold mb-4">Clinics Report</h1>
<table class="min-w-full border-separate border-spacing-0">
        <thead>
            <tr class="bg-blue-900 text-white">
                <th class="px-4 py-2 text-left text-sm font-medium">Clinic ID</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Clinic Name</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Email</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Case Orders</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Appointments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clinics as $clinic)
               <tr class="bg-white hover:bg-gray-50">
                    <td class="px-4 py-2 text-left text-sm font-medium">{{ $clinic->clinic_id }}</td>
                    <td class="px-4 py-2 text-left text-sm font-medium">{{ $clinic->clinic_name }}</td>
                    <td class="px-4 py-2 text-left text-sm font-medium">{{ $clinic->email }}</td>
                    <td class="px-4 py-2 text-left text-sm font-medium">{{ $clinic->case_orders_count }}</td>
                    <td class="px-4 py-2 text-left text-sm font-medium">{{ $clinic->appointments_count }}</td>
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

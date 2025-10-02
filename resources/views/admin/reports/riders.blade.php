@extends('layouts.app')

@section('title', 'Riders Report')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen text-sm">

    <h1 class="text-xl font-bold mb-4">🏍️ Riders Report</h1>

    <!-- Filter by Clinic -->
    <div class="mb-4">
        <label for="clinic_id" class="font-medium">Filter by Clinic:</label>
        <select id="clinic_id" class="border rounded px-2 py-1">
            <option value="">All Clinics</option>
            @foreach($clinics as $clinic)
                <option value="{{ $clinic->id }}">{{ $clinic->clinic_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Riders Table -->
    <div id="ridersTable">
        <table class="w-full table-auto border border-gray-300 bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2 text-left">#</th>
                    <th class="border px-4 py-2 text-left">Rider Name</th>
                    <th class="border px-4 py-2 text-left">Email</th>
                    <th class="border px-4 py-2 text-left">Contact Number</th>
                    <th class="border px-4 py-2 text-left">Deliveries Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riders as $rider)
                    <tr>
                        <td class="border px-4 py-2">{{ $rider->id }}</td>
                        <td class="border px-4 py-2">{{ $rider->name }}</td>
                        <td class="border px-4 py-2">{{ $rider->email }}</td>
                        <td class="border px-4 py-2">{{ $rider->contact_number ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $rider->deliveries_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="border px-4 py-2 text-center" colspan="5">No riders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $riders->links() }}
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.getElementById('clinic_id').addEventListener('change', function() {
        let clinicId = this.value;
        let url = "{{ route('reports.riders') }}?clinic_id=" + clinicId;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('ridersTable').innerHTML = html;
        })
        .catch(err => console.error(err));
    });
</script>
@endsection

@extends('layouts.clinic')

@section('content')
<main class="p-6">
    <h2 class="text-2xl font-bold mb-6">Patients List</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Dentist</th>
                    <th class="px-4 py-2 border">Contact</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $patient->patient_id }}</td>
                        <td class="px-4 py-2 border">{{ $patient->full_name }}</td>
                        <td class="px-4 py-2 border">{{ $patient->dentist->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border">{{ $patient->contact_no }}</td>
                        <td class="px-4 py-2 border">{{ $patient->email }}</td>
                        <td class="px-4 py-2 border">{{ $patient->address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>
@endsection

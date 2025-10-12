@extends('layouts.clinic')
@section('page-title', 'Appointments List')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">
    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Appointment ID</th>
                    <th class="px-6 py-3 text-left">Patient Name</th>
                    <th class="px-6 py-3 text-left">Technician Name</th>
                    <th class="px-6 py-3 text-left">Scheduled At</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody wire:poll.5s>
                @forelse ($appointments as $appointment)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-3 text-left">{{ $appointment->appointment_id }}</td>
                        <td class="px-6 py-3 text-left">{{ $appointment->caseOrder->patient->patient_name ?? '-' }}</td>
                        <td class="px-6 py-3 text-left">{{ $appointment->technician->name ?? 'Not assigned' }}</td>
                        <td class="px-6 py-3 text-left">{{ \Carbon\Carbon::parse($appointment->schedule_datetime)->format('M d, Y h:i A') }}</td>
                       <td class="px-6 py-3 text-left">
    @php
        $status = $appointment->work_status !== 'finished'
            ? ucfirst($appointment->work_status)
            : ($appointment->delivery->delivery_status ?? 'No delivery info');

        // define color based on status
        $colorClass = match(strtolower($status)) {
            'delivered' => 'text-green-600 font-semibold',
            'pending' => 'text-yellow-500 font-semibold',
            'in progress' => 'text-blue-600 font-semibold',
            'cancelled' => 'text-red-600 font-semibold',
            default => 'text-gray-700'
        };
    @endphp

    <span class="{{ $colorClass }}">{{ $status }}</span>
</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            No appointments found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

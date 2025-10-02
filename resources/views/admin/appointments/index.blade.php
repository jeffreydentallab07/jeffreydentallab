@extends('layouts.app')

@section('page-title', 'Appointments List')

@section('content')
<link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

<div class="fixed top-4 right-4 z-50 space-y-2">
    @if(session('success'))
        <div id="successToast" 
             class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-down">
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-white font-bold">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div id="errorToast" 
             class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-down">
            <span class="font-medium">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-white font-bold">&times;</button>
        </div>
    @endif
</div>
    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Case Order No.</th>
                    <th class="px-6 py-3 text-left">Technician Name</th>
                    <th class="px-6 py-3 text-left">Material Used</th>
                    <th class="px-6 py-3 text-left">Work Status</th>
                    <th class="px-6 py-3 text-left">Purpose</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($appointments as $appointment)
                    @php
                      if (!$appointment->technician_id) {
    $displayStatus = 'Pending';
    $statusClass = 'text-yellow-600 font-semibold'; // plain text, auto color
} elseif ($appointment->work_status === 'finished') {
    $displayStatus = 'Finished';
    $statusClass = 'text-gray-600 font-semibold';
} else {
    $displayStatus = 'In Progress';
    $statusClass = 'text-green-600 font-semibold';
}

                    @endphp
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-3 font-semibold text-blue-600 cursor-pointer hover:underline"
                            onclick="openAppointmentModal({{ $appointment->appointment_id }})">
                            {{ $appointment->co_id }}
                        </td>
                        <td class="px-6 py-3 text-gray-700">
                            @if($appointment->technician)
                                <span class="font-semibold">{{ $appointment->technician->name }}</span>
                            @else
                                <button 
                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition"
                                    onclick="openAssignTechnicianModal({{ $appointment->appointment_id }})">
                                    Assign Technician
                                </button>
                            @endif
                        </td>
                       <td class="px-6 py-3 text-gray-700">
    {{ $appointment->material->name ?? '-' }}
</td>

                        <td class="px-6 py-3"><span class="{{ $statusClass }}">{{ $displayStatus }}</span></td>
                        <td class="px-6 py-3 text-gray-700">{{ $appointment->purpose }}</td>
                        <td class="px-6 py-3 flex flex-col gap-2">
                              @if($appointment->work_status === 'finished' && !$appointment->billing)
                                <form action="{{ route('appointments.createBilling', $appointment->appointment_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600">
                                        Create Billing
                                    </button>
                                </form>
                            @elseif($appointment->billing)
                                <span class="text-gray-700 font-semibold italic">Billing Created</span>
                            @endif
                            @if($appointment->work_status === 'finished' && !$appointment->delivery)
                                <form action="{{ route('deliveries.createFromAppointment', $appointment->appointment_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600"
                                            onclick="this.disabled=true; this.innerText='Processing...'; this.form.submit();">
                                        Create Delivery
                                    </button>
                                </form>
                            @elseif($appointment->delivery)
                                <span class="text-gray-700 font-semibold italic">Delivery Created</span>
                            @endif

                          
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-gray-500 text-center bg-white">No appointments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

<div id="assignTechnicianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Assign Technician</h2>
            <button onclick="closeAssignTechnicianModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="p-6">
            <form id="assignTechnicianForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT') 
                <div>
                    <label class="block font-medium text-gray-700">Select Technician</label>
                  <select name="technician_id" class="w-full border rounded p-2">
    @foreach($technicians as $tech)
        @php
            $isBusy = $tech->appointments()
                          ->whereIn('work_status', ['in progress', 'pending'])
                          ->where('appointment_id', '!=', $appointment->appointment_id ?? 0)
                          ->exists();
        @endphp
        <option value="{{ $tech->id }}" {{ $isBusy ? 'disabled class=text-red-400' : '' }}>
            {{ $tech->name }} {{ $isBusy ? '(Busy)' : '(Available)' }}
        </option>
    @endforeach
</select>

                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeAssignTechnicianModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="appointmentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Appointment Details</h2>
            <button onclick="closeAppointmentModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="p-6" id="appointmentModalContent">
      
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success toast
    const successToast = document.getElementById('successToast');
    if (successToast) {
        setTimeout(() => {
            successToast.style.transition = 'opacity 0.5s ease-out';
            successToast.style.opacity = '0';
            setTimeout(() => successToast.remove(), 500); // remove from DOM
        }, 3000); // hides after 3 seconds
    }

    // Auto-hide error toast
    const errorToast = document.getElementById('errorToast');
    if (errorToast) {
        setTimeout(() => {
            errorToast.style.transition = 'opacity 0.5s ease-out';
            errorToast.style.opacity = '0';
            setTimeout(() => errorToast.remove(), 500);
        }, 3000);
    }
});
function openAppointmentModal(id) {
    // dito mo ilalagay ang content, pwede AJAX call para makuha ang data
    document.getElementById('appointmentModalContent').innerHTML = `<p>Loading details for appointment ID ${id}...</p>`;
    document.getElementById('appointmentModal').classList.remove('hidden');
}

function closeAppointmentModal() {
    document.getElementById('appointmentModal').classList.add('hidden');
}

function openAssignTechnicianModal(id) {
    const form = document.getElementById('assignTechnicianForm');
    form.action = `/appointments/${id}/assign-technician`;
    document.getElementById('assignTechnicianModal').classList.remove('hidden');
}

function closeAssignTechnicianModal() {
    document.getElementById('assignTechnicianModal').classList.add('hidden');
}
</script>

@endsection

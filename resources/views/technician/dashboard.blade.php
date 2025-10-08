@extends('layouts.technician_rider')

@section('title', 'Technician Home')

@section('content')
<div class="fixed top-4 right-4 z-50 space-y-2">
    {{-- Toasts --}}
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

<div class="container mx-auto mt-20 px-2 sm:px-4">
    @if($appointments->isEmpty())
        <p class="text-gray-600 text-center text-sm sm:text-base">No appointments assigned to you.</p>
    @else
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-blue-900 text-white">
                            <th class="px-3 sm:px-6 py-3 text-left">Appointment No.</th>
                            <th class="px-3 sm:px-6 py-3 text-left">Case Type</th>
                            <th class="px-3 sm:px-6 py-3 text-left">Note</th>
                            <th class="px-3 sm:px-6 py-3 text-left">Work Status</th>
                            <th class="px-3 sm:px-6 py-3 text-left">Material</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            @php
                                $isFinished = $appointment->work_status === 'finished';
                                $hasMaterial = !empty($appointment->material_id);
                                $materialName = $appointment->material ? $appointment->material->name : 'Not Selected';
                            @endphp
                            <tr class="hover:bg-blue-50 transition">
                                {{-- Appointment # --}}
                                <td class="px-3 sm:px-6 py-3 text-gray-700 whitespace-nowrap">
                                    @if($appointment->caseOrder)
                                        <button type="button"
                                                onclick="openModal('modal{{ $appointment->appointment_id }}')"
                                                class="text-blue-600 hover:underline font-semibold">
                                            {{ $appointment->appointment_id }}
                                        </button>

                                        {{-- Appointment Details Modal --}}
                                        <div id="modal{{ $appointment->appointment_id }}"
                                             class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-3">
                                            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg sm:max-w-2xl max-h-[90vh] overflow-y-auto relative font-sans">
                                                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                                                    <div class="flex items-center space-x-4">
                                                        <img class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-4 border-white shadow-md"
                                                             src="{{ $appointment->caseOrder->clinic->profile_photo 
                                                                 ? asset('storage/uploads/clinic_photos/' . $appointment->caseOrder->clinic->profile_photo) 
                                                                 : asset('images/user.png') }}"
                                                             alt="{{ $appointment->caseOrder->clinic->clinic_name }}">
                                                        <div>
                                                            <h2 class="text-base sm:text-xl font-semibold text-gray-800">{{ $appointment->caseOrder->clinic->clinic_name }}</h2>
                                                            <p class="text-xs text-gray-500">{{ $appointment->caseOrder->clinic->address }}</p>
                                                            <p class="text-xs text-gray-500">Contact: {{ $appointment->caseOrder->clinic->contact_number }}</p>
                                                        </div>
                                                    </div>
                                                    <button onclick="closeModal('modal{{ $appointment->appointment_id }}')" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
                                                </div>

                                                <div class="p-4 space-y-4 text-sm text-gray-700">
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block font-medium text-gray-700 text-xs">Case No.</label>
                                                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">
                                                                {{ 'CASE-' . str_pad($appointment->caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <label class="block font-medium text-gray-700 text-xs">Case Type</label>
                                                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $appointment->caseOrder->case_type }}</p>
                                                        </div>
                                                        <div>
                                                            <label class="block font-medium text-gray-700 text-xs">Patient Name</label>
                                                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $appointment->caseOrder->patient?->patient_name ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <label class="block font-medium text-gray-700 text-xs">Dentist Name</label>
                                                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $appointment->caseOrder->patient?->dentist?->name ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <label class="block font-medium text-gray-700 text-xs">Notes</label>
                                                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100 whitespace-pre-line">{{ $appointment->caseOrder->notes ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end mt-3">
                                                        <button type="button" onclick="closeModal('modal{{ $appointment->appointment_id }}')" class="px-3 py-1.5 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                {{-- Case Type --}}
                                <td class="px-3 sm:px-6 py-3 text-gray-700">{{ $appointment->caseOrder?->case_type ?? 'N/A' }}</td>

                                {{-- Note --}}
                                <td class="px-3 sm:px-6 py-3 text-gray-700">
                                    <div class="mt-1 px-2 py-1 border rounded bg-gray-100 whitespace-pre-line max-w-[120px] sm:max-w-xs max-h-16 overflow-auto">
                                        {{ $appointment->caseOrder?->notes ?? 'N/A' }}
                                    </div>
                                </td>

                                {{-- Work Status --}}
                                <td class="px-3 sm:px-6 py-3 text-gray-700">
                                    @if($isFinished)
                                        <span class="text-gray-600 font-semibold text-xs sm:text-sm">✓ Completed</span>
                                    @elseif($hasMaterial)
                                        <button type="button" 
                                                onclick="openFinishModal('{{ $appointment->appointment_id }}')"
                                                class="bg-blue-900 hover:bg-blue-800 text-white text-[11px] sm:text-xs px-3 py-1 rounded pulse-button">
                                            Mark as Finished
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs italic">Select material first</span>
                                    @endif
                                </td>

                                {{-- Material --}}
                                <td class="px-3 sm:px-6 py-3 text-gray-700">
                                    <form id="materialForm_{{ $appointment->appointment_id }}" 
                                          action="{{ route('technician.appointment.update', $appointment->appointment_id) }}" 
                                          method="POST" class="inline-block">
                                        @csrf
                                        <select name="material_id"
                                                class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-blue-900 focus:border-blue-900 bg-white w-28 sm:w-32 
                                                {{ !$isFinished && !$hasMaterial ? 'pulse-select' : '' }} 
                                                {{ $isFinished ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : '' }}"
                                                onchange="this.form.submit()" 
                                                {{ $isFinished ? 'disabled' : '' }}>
                                            <option value="">Select</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->material_id }}" 
                                                        {{ $appointment->material_id == $material->material_id ? 'selected' : '' }}>
                                                    {{ $material->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

{{-- Confirmation Modal --}}
<div id="finishModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 text-center transform transition-all duration-300">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Confirm Finish</h2>
        <p class="text-gray-600 mb-6">Are you sure you want to mark this appointment as finished?</p>
        <div class="flex justify-center gap-3">
            <button id="cancelFinishBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-sm">Cancel</button>
            <button id="confirmFinishBtn" class="px-4 py-2 rounded bg-blue-900 text-white hover:bg-blue-800 text-sm">Confirm</button>
        </div>
    </div>
</div>

<style>
@keyframes pulseBlue {
    0%, 100% { box-shadow: 0 0 0 0 rgba(30, 58, 138, 0.5); transform: scale(1); }
    50% { box-shadow: 0 0 15px 3px rgba(30, 58, 138, 0.5); transform: scale(1.05); }
}
.pulse-select, .pulse-button {
    animation: pulseBlue 1.5s infinite ease-in-out;
}
.pulse-button { font-weight: 600; transition: transform 0.2s ease; }
.pulse-button:hover { transform: scale(1.07); }
</style>

<script>
let currentAppointmentId = null;

function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function openFinishModal(id) {
    currentAppointmentId = id;
    document.getElementById('finishModal').classList.remove('hidden');
}

document.getElementById('cancelFinishBtn').addEventListener('click', () => {
    document.getElementById('finishModal').classList.add('hidden');
    currentAppointmentId = null;
});

document.getElementById('confirmFinishBtn').addEventListener('click', () => {
    if (currentAppointmentId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/technician/appointments/finish/${currentAppointmentId}`;
        form.innerHTML = `@csrf
            <input type="hidden" name="appointment_id" value="${currentAppointmentId}">
            <input type="hidden" name="work_status" value="finished">`;
        document.body.appendChild(form);
        form.submit();
    }
    document.getElementById('finishModal').classList.add('hidden');
});

document.addEventListener('DOMContentLoaded', () => {
    const toastSuccess = document.getElementById('successToast');
    if (toastSuccess) setTimeout(() => { toastSuccess.style.opacity = '0'; setTimeout(()=>toastSuccess.remove(), 500); }, 3000);
    const toastError = document.getElementById('errorToast');
    if (toastError) setTimeout(() => { toastError.style.opacity = '0'; setTimeout(()=>toastError.remove(), 500); }, 3000);
});
</script>
@endsection

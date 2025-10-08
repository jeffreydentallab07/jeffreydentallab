@extends('layouts.technician_rider')

@section('title', 'Rider Home')

@section('content')

<div class="fixed top-24 right-4 z-50 space-y-2">
    @if(session('success'))
        <div id="successToast"
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-down">
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-green-700 font-bold text-lg">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div id="errorToast"
             class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-down">
            <span class="font-medium">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-2 text-red-700 font-bold text-lg">&times;</button>
        </div>
    @endif
</div>

<div class="container mx-auto mt-24 px-4">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Delivery ID</th>
                    <th class="px-6 py-3 text-left">Case No.</th>
                    <th class="px-6 py-3 text-left">Clinic Name</th>
                    <th class="px-6 py-3 text-left">Delivery Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-3 text-gray-700">
                            #{{ $delivery->delivery_id }}
                        </td>
                       <td class="px-6 py-3 text-gray-700">
    @if($delivery->appointment?->caseOrder)
        <button type="button"
                onclick="openModal('modal{{ $delivery->appointment->caseOrder->co_id }}')"
                class="text-blue-600 hover:underline font-medium">
            {{ 'CASE-' . str_pad($delivery->appointment->caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
        </button>

        <!-- Modal -->
        <div id="modal{{ $delivery->appointment->caseOrder->co_id }}"
             class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-2">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative font-sans" role="dialog">
                
                <!-- Header -->
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <img class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md"
                                 src="{{ $delivery->appointment->caseOrder->clinic->profile_photo 
                                     ? asset('storage/uploads/clinic_photos/' . $delivery->appointment->caseOrder->clinic->profile_photo) 
                                     : asset('images/user.png') }}"
                                 alt="{{ $delivery->appointment->caseOrder->clinic->clinic_name }}">
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">
                                {{ $delivery->appointment->caseOrder->clinic->clinic_name }}
                            </h2>
                            <p class="text-xs text-gray-500">{{ $delivery->appointment->caseOrder->clinic->address }}</p>
                            <p class="text-xs text-gray-500">Contact: {{ $delivery->appointment->caseOrder->clinic->contact_number }}</p>
                        </div>
                    </div>
                    <button onclick="closeModal('modal{{ $delivery->appointment->caseOrder->co_id }}')" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
                </div>

                <!-- Body -->
                <div class="p-4 space-y-4 text-sm text-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-medium text-gray-700 text-xs">Case No.</label>
                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ 'CASE-' . str_pad($delivery->appointment->caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 text-xs">Case Type</label>
                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $delivery->appointment->caseOrder->case_type }}</p>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 text-xs">Patient Name</label>
                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $delivery->appointment->caseOrder->patient?->patient_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 text-xs">Dentist Name</label>
                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $delivery->appointment->caseOrder->patient?->dentist?->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block font-medium text-gray-700 text-xs">Notes</label>
                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100 whitespace-pre-line">{{ $delivery->appointment->caseOrder->notes ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block font-medium text-gray-700 text-xs">Created At</label>
                            <p class="mt-1 px-2 py-1 border rounded bg-gray-100">
                                {{ \Carbon\Carbon::parse($delivery->appointment->caseOrder->created_at)->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-3">
                        <button type="button"
                                onclick="closeModal('modal{{ $delivery->appointment->caseOrder->co_id }}')"
                                class="px-3 py-1.5 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <span class="text-gray-400 italic">N/A</span>
    @endif
</td>
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
</script>

                        <td class="px-6 py-3 text-gray-700">
                            {{ $delivery->appointment?->caseOrder?->clinic?->clinic_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-3 text-gray-700">
                            @if($delivery->delivery_status == 'delivered')
                                <span class="status-badge bg-green-100 text-green-800 px-2 py-1 rounded">Delivered</span>
                            @elseif($delivery->delivery_status == 'in transit')
                                <span class="status-badge bg-yellow-100 text-yellow-800 px-2 py-1 rounded">In Transit</span>
                            @elseif($delivery->delivery_status == 'ready to deliver')
                                <span class="status-badge bg-blue-100 text-blue-800 px-2 py-1 rounded">Ready to Deliver</span>
                            @else
                                <span class="text-gray-500 italic">Unknown</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            @if($delivery->delivery_status == 'in transit')
                                <form action="{{ route('rider.deliveries.markDelivered', $delivery->delivery_id) }}" method="POST"
                                      onsubmit="return confirm('Mark this delivery as DELIVERED?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors pulse-button">
                                        Mark as Delivered
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-500 text-xs">No active actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
                            No deliveries assigned to you.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
@keyframes pulseEffect {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(30, 58, 138, 0.4); 
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 15px 3px rgba(30, 58, 138, 0.5);
    }
}
.pulse-button {
    animation: pulseEffect 1.5s infinite ease-in-out;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.pulse-button:hover {
    box-shadow: 0 0 20px 5px rgba(30, 58, 138, 0.6);
}
</style>

{{-- 4. Toast Auto-hide --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const successToast = document.getElementById('successToast');
    const errorToast = document.getElementById('errorToast');

    const fadeOutAndRemove = (toast) => {
        if (toast) {
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }
    };

    fadeOutAndRemove(successToast);
    fadeOutAndRemove(errorToast);
});
</script>

@endsection

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

<div class="container mx-auto mt-20 px-3 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        
        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
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
                            <td class="px-6 py-3 text-gray-700">#{{ $delivery->delivery_id }}</td>
                            <td class="px-6 py-3 text-gray-700">
                                @if($delivery->appointment?->caseOrder)
                                    <button type="button"
                                            onclick="openModal('modal{{ $delivery->appointment->caseOrder->co_id }}')"
                                            class="text-blue-600 hover:underline font-medium">
                                        {{ 'CASE-' . str_pad($delivery->appointment->caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
                                    </button>
                                @else
                                    <span class="text-gray-400 italic">N/A</span>
                                @endif
                            </td>
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
                            <td class="px-6 py-3 text-center">
                                @if($delivery->delivery_status == 'in transit')
                                    <button type="button"
                                            onclick="showConfirmModal({{ $delivery->delivery_id }})"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors pulse-button">
                                        Mark as Delivered
                                    </button>
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

        {{-- Mobile Cards --}}
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($deliveries as $delivery)
                <div class="p-4 space-y-2 hover:bg-blue-50 transition">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-blue-900">
                            Delivery #{{ $delivery->delivery_id }}
                        </h3>
                        <span class="text-xs text-gray-500">
                            {{ ucfirst($delivery->delivery_status) }}
                        </span>
                    </div>
                    <div class="text-sm">
                        <p><strong>Case:</strong> 
                            @if($delivery->appointment?->caseOrder)
                                <button type="button"
                                        onclick="openModal('modal{{ $delivery->appointment->caseOrder->co_id }}')"
                                        class="text-blue-600 hover:underline">
                                    {{ 'CASE-' . str_pad($delivery->appointment->caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
                                </button>
                            @else
                                <span class="text-gray-400 italic">N/A</span>
                            @endif
                        </p>
                        <p><strong>Clinic:</strong> {{ $delivery->appointment?->caseOrder?->clinic?->clinic_name ?? 'N/A' }}</p>
                        <p><strong>Status:</strong> 
                            @if($delivery->delivery_status == 'delivered')
                                <span class="text-green-700 font-medium">Delivered</span>
                            @elseif($delivery->delivery_status == 'in transit')
                                <span class="text-yellow-700 font-medium">In Transit</span>
                            @elseif($delivery->delivery_status == 'ready to deliver')
                                <span class="text-blue-700 font-medium">Ready to Deliver</span>
                            @else
                                <span class="text-gray-500 italic">Unknown</span>
                            @endif
                        </p>
                    </div>
                    <div class="mt-3 text-right">
                        @if($delivery->delivery_status == 'in transit')
                            <button type="button"
                                    onclick="showConfirmModal({{ $delivery->delivery_id }})"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors pulse-button">
                                Mark as Delivered
                            </button>
                        @else
                            <span class="text-gray-500 text-xs">No active actions</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="p-4 text-center text-gray-500 italic">No deliveries assigned to you.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-lg w-80 p-6 text-center">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Confirm Delivery</h2>
        <p class="text-sm text-gray-600 mb-5">Are you sure you want to mark this delivery as <strong>DELIVERED</strong>?</p>
        <form id="confirmForm" method="POST">
            @csrf
            @method('PUT')
            <div class="flex justify-center gap-3">
                <button type="button"
                        onclick="closeConfirmModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium px-4 py-2 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg transition">
                    Yes, Confirm
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Button pulse animation --}}
<style>
@keyframes pulseEffect {
    0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(30,58,138,0.4); }
    50% { transform: scale(1.05); box-shadow: 0 0 15px 3px rgba(30,58,138,0.5); }
}
.pulse-button { animation: pulseEffect 1.5s infinite ease-in-out; }
</style>

{{-- Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toasts = [document.getElementById('successToast'), document.getElementById('errorToast')];
    toasts.forEach(toast => {
        if (toast) {
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }
    });
});

// Confirmation Modal Functions
function showConfirmModal(deliveryId) {
    const modal = document.getElementById('confirmModal');
    const form = document.getElementById('confirmForm');
    form.action = `/rider/deliveries/${deliveryId}/mark-delivered`; // route pattern
    modal.classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}
</script>

@endsection

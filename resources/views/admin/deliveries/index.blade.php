@extends('layouts.app')

@section('page-title', 'Delivery List')

@section('content')
<link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">
    <div class="overflow-x-auto rounded-xl shadow-lg">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Delivery ID</th>
                    <th class="px-6 py-3 text-left">Case Order No.</th>
                    <th class="px-6 py-3 text-left">Rider Name</th>
                    <th class="px-6 py-3 text-left">Delivery Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $delivery->delivery_id }}</td>

                    <td class="px-6 py-3 font-medium text-gray-800">
                        <button type="button"
                            onclick="openAppointmentModal({{ $delivery->appointment->appointment_id }})"
                            class="text-blue-600 hover:underline">
                            {{ 'CASE-' . str_pad($delivery->appointment->co_id, 5, '0', STR_PAD_LEFT) }}
                        </button>
                    </td>

                    <td class="px-6 py-3 font-medium text-gray-800">
                        @if($delivery->rider_id)
                            {{ $delivery->rider?->name }}
                        @else
                            <button onclick="openModal('assignRiderModal{{ $delivery->delivery_id }}')"
                                class="px-3 py-1 bg-blue-900 text-white rounded hover:bg-blue-800 transition pulse-button">
                                Assign Rider
                            </button>

                            {{-- Modal for Assigning Rider --}}
                            <div id="assignRiderModal{{ $delivery->delivery_id }}" 
                                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                <div class="bg-white w-11/12 max-w-md rounded-lg shadow-lg p-6 relative">
                                    <button onclick="closeModal('assignRiderModal{{ $delivery->delivery_id }}')" 
                                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
                                    <h2 class="text-xl font-bold mb-4 text-blue-900">Assign Rider</h2>
                                    <form action="{{ route('deliveries.assignRider', $delivery->delivery_id) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-gray-700 mb-2">Select Rider</label>
                                            <select name="rider_id" class="w-full border rounded p-2">
                                                @foreach($riders as $rider)
                                                    <option value="{{ $rider->id }}">{{ $rider->name }} ({{ $rider->status ?? 'Available' }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" onclick="closeModal('assignRiderModal{{ $delivery->delivery_id }}')" 
                                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Assign</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </td>

                    <td class="px-6 py-3 font-medium text-gray-800">
                        @if($delivery->rider_id)
                            <span class="text-green-600 font-semibold">{{ ucfirst($delivery->delivery_status) }}</span>
                        @else
                            <span class="text-gray-500 italic">{{ ucfirst($delivery->delivery_status) }} (Waiting for Rider)</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-gray-500 text-center bg-white">No deliveries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Smooth pulse animation matching bg-blue-900 --}}
<style>
@keyframes pulseEffect {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(30, 58, 138, 0.4); /* blue-900 */
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 15px 3px rgba(30, 58, 138, 0.5); /* blue-900 */
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

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
</script>
@endsection

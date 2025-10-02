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
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $delivery->delivery_id }}</td>
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $delivery->appointment->co_id }}</td>

                    <td class="px-6 py-3 font-medium text-gray-800">
                        @if($delivery->rider_id)
                            {{ $delivery->rider?->name }}
                        @else
                            <button onclick="openModal('assignRiderModal{{ $delivery->delivery_id }}')"
                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                Assign Rider
                            </button>

                    
                            <div id="assignRiderModal{{ $delivery->delivery_id }}" 
                                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                <div class="bg-white w-11/12 max-w-md rounded-lg shadow-lg p-6 relative">
                                    <button onclick="closeModal('assignRiderModal{{ $delivery->delivery_id }}')" 
                                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
                                    <h2 class="text-xl font-bold mb-4">Assign Rider</h2>
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

                    <td class="px-6 py-3 font-medium text-gray-800">{{ ucfirst($delivery->delivery_status) }}</td>
                    <td class="px-6 py-3 font-medium text-gray-800">
                        @if($delivery->rider_id)
                            <span class="text-gray-700 font-semibold">Assigned</span>
                        @else
                            <span class="text-gray-500 italic">Waiting for Rider</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-gray-500 text-center bg-white">No deliveries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endsection

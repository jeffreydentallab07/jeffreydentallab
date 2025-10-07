@extends('layouts.app')

@section('page-title', 'Case Orders List')

@section('content')
<link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">

<style>

@keyframes wiggle {
  0%, 100% { transform: rotate(-3deg); }
  50% { transform: rotate(3deg); }
}


.animate-wiggle {
  animation: wiggle 0.5s ease-in-out infinite;
}


.animate-wiggle:hover {
  animation: none;
}
</style>


<div class="p-6 space-y-6 bg-gray-300 min-h-screen">
    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Case No.</th>
                    <th class="px-6 py-3 text-left">Dentist Name</th>
                    <th class="px-6 py-3 text-left">Case Type</th>
                    <th class="px-6 py-3 text-left">Notes</th>
                    <th class="px-6 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($caseOrders as $caseOrder)
                <tr class="text-gray-700 text-sm">
                    <td class="px-6 py-3 text-left">
                        <button type="button"
                                onclick="openModal('modal{{ $caseOrder->co_id }}')"
                                class="text-blue-600 hover:underline">
                            {{ 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}
                        </button>
                    </td>

                    <td class="px-6 py-3 text-left">
                        {{ $caseOrder->patient && $caseOrder->patient->dentist ? 'Dr. ' . $caseOrder->patient->dentist->name : 'N/A' }}
                    </td>
                    <td class="px-6 py-3 text-left">{{ $caseOrder->case_type }}</td>
                    <td class="px-6 py-4 max-w-xs">
                        <div class="max-h-16 overflow-y-auto">{{ $caseOrder->notes }}</div>
                    </td>
                    <td class="px-6 py-3 text-center">
                        @if($caseOrder->status === 'approved')
                            <span class="text-green-600 font-semibold">Approved</span>
                        @else
                       <button type="button"
        onclick="openModal('modal{{ $caseOrder->co_id }}')"
        class="px-3 py-1 bg-blue-900 text-white rounded hover:bg-blue-600 transition animate-wiggle">
    For Approval
</button>

                        @endif
                    </td>
                </tr>

            
                <div id="modal{{ $caseOrder->co_id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-2">
                    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative font-sans" role="dialog" aria-labelledby="modalTitle{{ $caseOrder->co_id }}" aria-modal="true">

                       
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <img class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-md"
     src="{{ $caseOrder->clinic?->profile_photo 
         ? asset('storage/uploads/clinic_photos/' . $caseOrder->clinic->profile_photo) 
         : asset('images/user.png') }}"
     alt="{{ $caseOrder->clinic?->clinic_name ?? 'N/A' }}">

                                </div>
                                <div>
                                    <h2 id="modalTitle{{ $caseOrder->co_id }}" class="text-xl font-semibold text-gray-800">{{ $caseOrder->clinic->clinic_name }}</h2>
                                    <p class="text-xs text-gray-500">{{ $caseOrder->clinic->address }}</p>
                                    <p class="text-xs text-gray-500">Contact: {{ $caseOrder->clinic->contact_number }}</p>
                                </div>
                            </div>
                            <button onclick="closeModal('modal{{ $caseOrder->co_id }}')" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
                        </div>

                        {{-- Case Order Details --}}
                        <div class="p-4 space-y-4 text-sm text-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-medium text-gray-700 text-xs">Case No.</label>
                                    <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ 'CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <div>
                                    <label class="block font-medium text-gray-700 text-xs">Case Type</label>
                                    <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $caseOrder->case_type }}</p>
                                </div>
                                <div>
                                    <label class="block font-medium text-gray-700 text-xs">Patient Name</label>
                                    <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $caseOrder->patient?->patient_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block font-medium text-gray-700 text-xs">Dentist Name</label>
                                    <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ $caseOrder->patient?->dentist?->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="block font-medium text-gray-700 text-xs">Notes</label>
                                    <p class="mt-1 px-2 py-1 border rounded bg-gray-100 whitespace-pre-line">{{ $caseOrder->notes ?? 'N/A' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="block font-medium text-gray-700 text-xs">Created At</label>
                                    <p class="mt-1 px-2 py-1 border rounded bg-gray-100">{{ \Carbon\Carbon::parse($caseOrder->created_at)->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex justify-end space-x-3 mt-3">
                                <button type="button" onclick="closeModal('modal{{ $caseOrder->co_id }}')" class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">Back</button>
                                <form action="{{ route('case-orders.approve', $caseOrder->co_id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Approve</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">No case orders found.</td>
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

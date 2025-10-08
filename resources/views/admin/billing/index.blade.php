@extends('layouts.app')

@section('page-title', 'Billing List')

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

    
     <div class="overflow-x-auto rounded-xl shadow-lg">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                        <th class="px-6 py-3 text-left">Appointment ID</th>
                        <th class="px-6 py-3 text-left">Material Used</th>
                        <th class="px-6 py-3 text-left">Total Amount</th>
                        <th class="px-6 py-3 text-left">Created At</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($billings as $billing)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                #{{ $billing->appointment_id }}
                            </td>
                          <td class="px-6 py-4 text-sm text-gray-600">
    @if($billing->appointment && $billing->appointment->material)
        <span class="font-medium text-gray-900">
            {{ $billing->appointment->material->name }}
        </span>
    @else
        <span class="italic text-gray-400">No Material</span>
    @endif
</td>

                            <td class="px-6 py-4 text-sm text-gray-700 font-semibold">
                                {{ $billing->formatted_total }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $billing->created_at->format('M j, Y g:ia') }}
                            </td>
                           
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
    <div class="flex items-center gap-2">
  
    <button type="button"
        onclick="openReceiptModal({{ $billing->billing_id }})"
        class="bg-blue-900 text-white p-2 rounded-lg shadow hover:bg-blue-800 transition flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke-width="1.5" 
             stroke="currentColor" 
             class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M2.25 12c0 0 3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
            <circle cx="12" cy="12" r="3" />
        </svg>
    </button>

    
    <a href="{{ url('/billing/receipt/' . $billing->billing_id . '/pdf') }}" 
   target="_blank"
   class="bg-green-600 text-white p-2 rounded-lg shadow hover:bg-green-700 transition flex items-center justify-center">
    <svg xmlns="http://www.w3.org/2000/svg" 
         viewBox="0 0 16 16" 
         fill="currentColor" 
         class="w-4 h-4">
      <path d="M7 1a.75.75 0 0 1 .75.75V6h-1.5V1.75A.75.75 0 0 1 7 1ZM6.25 6v2.94L5.03 7.72a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 0 0 1.06 0l2.5-2.5a.75.75 0 1 0-1.06-1.06L7.75 8.94V6H10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h2.25Z" />
      <path d="M4.268 14A2 2 0 0 0 6 15h6a2 2 0 0 0 2-2v-3a2 2 0 0 0-1-1.732V11a3 3 0 0 1-3 3H4.268Z" />
    </svg>
</a>

   
    <button 
        onclick="openDeleteModal({{ $billing->billing_id }})"
        class="bg-red-600 text-white p-2 rounded-lg shadow hover:bg-red-700 transition flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" 
             viewBox="0 0 16 16" 
             fill="currentColor" 
             class="w-4 h-4">
          <path fill-rule="evenodd" 
                d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" 
                clip-rule="evenodd" />
        </svg>
    </button>
</div>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8"> {{-- Adjusted colspan --}}
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 0v6m0-6H9m3 0v6m-3-6H6m3 0V9m0 0h6m-6 0H9" />
                                    </svg>
                                    <p class="text-lg font-medium">No billing records found</p>
                                    <p class="text-sm mt-1">Get started by creating a new billing record.</p>
                                    <a href="{{ route('billing.create') }}" 
                                       class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Create Billing
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
        <div class="bg-white w-[550px] max-h-[90vh] overflow-y-auto p-6 border border-gray-300 shadow-lg rounded-lg relative">
            <button onclick="closeReceiptModal()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 font-bold text-2xl">&times;</button>
            <div id="receiptContent" class="text-gray-700 text-sm"></div>
            <div class="mt-4 flex justify-end gap-2">
                <button onclick="window.print()" class="bg-[#0f4c75] text-white px-4 py-2 rounded-lg shadow hover:bg-[#0c3b57] transition">
                    Print Receipt
                </button>
                <a id="downloadPdfBtn" href="#" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                    Download PDF
                </a>
                <button onclick="closeReceiptModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
        <div class="bg-white w-[400px] p-6 rounded-lg shadow-lg text-center relative">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Delete</h2>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this billing record? This action cannot be undone.</p>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="billing_id_to_delete" id="delete_billing_id"> {{-- Changed name for clarity --}}
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        Delete Billing
                    </button>
                </div>
            </form>

            <button onclick="closeDeleteModal()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 font-bold text-xl">&times;</button>
        </div>
    </div>

@endsection

<script>
function openReceiptModal(billingId) {
    document.getElementById('receiptContent').innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="text-gray-500 mt-2">Loading receipt...</p></div>';
    document.getElementById('receiptModal').classList.remove('hidden');
    document.getElementById('downloadPdfBtn').href = `/billing/receipt/${billingId}/pdf`; 

    fetch(`/billing/receipt/${billingId}`)
        .then(res => {
            if (!res.ok) {
                return res.text().then(text => { throw new Error(text || 'Failed to load receipt'); });
            }
            return res.text();
        })
        .then(html => {
            document.getElementById('receiptContent').innerHTML = html;
        })
        .catch(err => {
            console.error('Error loading receipt:', err);
            document.getElementById('receiptContent').innerHTML = '<div class="text-center py-8 text-red-500"><p>Error: Could not load receipt. Please check server logs.</p><button onclick="openReceiptModal(' + billingId + ')" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Retry</button></div>';
        });
}

function closeReceiptModal() {
    document.getElementById('receiptModal').classList.add('hidden');
}

function openDeleteModal(billingId) {
    document.getElementById('delete_billing_id').value = billingId;
    document.getElementById('deleteForm').action = `/billing/${billingId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const toasts = document.querySelectorAll('#successToast, #errorToast');
        toasts.forEach(toast => {
            toast.style.transition = 'opacity 0.5s ease-out';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        });
    }, 5000);
});
</script>
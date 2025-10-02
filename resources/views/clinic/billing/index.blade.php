@extends('layouts.clinic')
@section('page-title', 'Billing List')
@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

<div class="overflow-x-auto rounded-xl shadow-lg mt-4">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-blue-900 text-white">
                <th class="px-6 py-3 text-left">Appointment ID</th>
                <th class="px-6 py-3 text-left">Patient Name</th>
                <th class="px-6 py-3 text-left">Case Type</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($billings as $billing)
            <tr class="hover:bg-blue-50 transition">
                <td class="px-6 py-3 text-gray-700">{{ $billing->appointment_id }}</td>
                <td class="px-6 py-3 text-gray-700">{{ $billing->patient_name ?? '-' }}</td>
                <td class="px-6 py-3 text-gray-700">{{ $billing->case_type ?? '-' }}</td>
                <td class="px-6 py-3 text-gray-700" x-data="{ open: false }">
                 
                    <button @click="open = true"
                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                        View
                    </button>

                   
                    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div @click.away="open = false" class="bg-white p-6 rounded-lg w-[700px] max-h-[90vh] overflow-y-auto">

                        
                            <div class="text-left mb-2">
                                <h1 class="text-xl font-bold uppercase">Jeffrey Dental Laboratory</h1>
                                <p class="leading-tight text-[11px]">
                                    Zone 7 Bulua District 1<br />
                                    9000 Cagayan de Oro City (CAPITAL)<br />
                                    Misamis Oriental Philippines<br />
                                    <strong>JEFFREY D. GELLANGAO - PROP.</strong><br />
                                    Non Vat Reg., TIN 408-400-984-00000
                                </p>
                            </div>

                            <div class="flex justify-between mb-2">
                                <div class="text-lg font-semibold">Delivery Receipt</div>
                                <div class="flex space-x-6">
                                    <div>No: <span class="font-bold text-red-600">{{ $billing->appointment_id }}</span></div>
                                    <div>Date: <span class="border-b border-black inline-block min-w-[120px]">{{ \Carbon\Carbon::parse($billing->created_at)->format('F j, Y g:ia') }}</span></div>
                                </div>
                            </div>


                            <table class="w-full text-left border-t border-b border-black mb-2">
                                <thead>
                                    <tr class="border-b border-black">
                                        <th class="w-10 py-1">Qty</th>
                                        <th class="w-12">Unit</th>
                                        <th>Description Articles</th>
                                        <th class="w-20 text-right">U-Price</th>
                                        <th class="w-20 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-300">
                                        <td class="py-1">1</td>
                                        <td>pc</td>
                                        <td>{{ $billing->material_name ?? 'Not set' }}</td>
                                        <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
                                        <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right font-semibold py-1">Subtotal</td>
                                        <td class="text-right">{{ number_format($billing->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right font-semibold py-1">VAT (12%)</td>
                                        <td class="text-right">{{ number_format($billing->total_amount * 0.12, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right font-bold py-2">Total ₱</td>
                                        <td class="text-right font-bold">{{ number_format($billing->total_amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="flex justify-between mt-6">
                                <div>
                                    Created by:
                                    <div class="h-6"></div>
                                    <div class="text-center text-xs font-semibold">JEFFREY GELLANGAO</div>
                                    <div class="border-t border-black w-40 text-center text-xs mt-1">Authorized Signature</div>
                                </div>
                                <div class="text-right">
                                    <p class="leading-tight text-sm">
                                        Received the above Merchandise<br />
                                        in good order and Condition
                                    </p>
                                    <div>
                                        Delivered by:
                                        <div class="h-6"></div>
                                        <div class="text-center text-xs font-semibold">{{ $billing->rider_name ?? 'Not Assigned' }}</div>
                                        <div class="border-t border-black w-40 text-center text-xs mt-1">Delivered By</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-4">
                                <button @click="open = false"
                                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    Close
                                </button>
                            </div>

                        </div>
                    </div>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-8 text-gray-500">No completed billings found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>

@endsection

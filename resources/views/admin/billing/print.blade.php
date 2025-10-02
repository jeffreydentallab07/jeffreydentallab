@extends('layouts.app')

@section('page-title', 'Billing Receipt')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-transparent p-6">
    <div class="bg-white w-[550px] p-4 border border-gray-300 shadow-lg rounded-lg">
        <div class="text-left mb-2">
            <h1 class="text-xl font-bold uppercase">Jeffrey Dental Laboratory</h1>
            <p class="leading-tight text-[11px]">
                Zone 7 Bulua District 1<br />
                9000 Cagayan de Oro City (CAPITAL)<br />
                Misamis Oriental Philippines<br />
                <strong>JEFFREY D. GELLANGO - PROP.</strong><br />
                Non Vat Reg., TIN 408-400-984-00000
            </p>
        </div>

        <div class="flex justify-between mb-2">
            <div class="text-lg font-semibold">Delivery Receipt</div>
            <div class="flex space-x-6">
                <div>No: <span class="font-bold text-red-600">60</span></div>
                <div>Date: <span class="border-b border-black inline-block min-w-[80px]">07-28-25</span></div>
            </div>
        </div>

        <div class="mb-2">
            <div>Delivered to: <span class="border-b border-black inline-block min-w-[200px]">DR. Santos</span></div>
            <div>Address: <span class="inline-block border-b border-black min-w-[300px]">&nbsp;</span></div>
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
                    <td>N. Dent Porcelain (Complete Denture)</td>
                    <td class="text-right">5500.00</td>
                    <td class="text-right">5500.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right font-semibold py-1">Subtotal</td>
                    <td class="text-right">5500.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right font-semibold py-1">VAT (12%)</td>
                    <td class="text-right">564</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right font-bold py-2">Total ₱</td>
                    <td class="text-right font-bold">5500.00</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-between mt-6">
            <div>
                Created by:
                <div class="h-6"></div>
                <div class="text-center text-xs font-semibold">JEFFREY GELLANGO</div>
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
                    <div class="text-center text-xs font-semibold">Alex Uy</div>
                    <div class="border-t border-black w-40 text-center text-xs mt-1">Delivered By</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

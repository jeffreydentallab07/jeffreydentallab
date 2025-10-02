@extends('layouts.technician_rider')

@section('title', 'Rider Home')

@section('content')

{{-- 1. Notifications Toast Area (Fixed Position) --}}
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

{{-- 2. Main Content (Rider Deliveries Table) --}}
<div class="container mx-auto mt-24 px-4">


    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
       
            <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-blue-900 text-white">
                        <th class="px-6 py-3 text-left">Delivery ID</th>
                        <th class="px-6 py-3 text-left">Case Order #</th>
                        <th class="px-6 py-3 text-left">Clinic Name</th>
                        <th class="px-6 py-3 text-left">Material</th>
                        <th class="px-6 py-3 text-left">Working Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                  <tr class="hover:bg-blue-50 transition">
                    @forelse($deliveries as $delivery)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-3 text-gray-700">
                                #{{ $delivery->delivery_id }}
                            </td>
                            <td class="px-6 py-3 text-gray-700">
                                {{ $delivery->appointment?->co_id ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3 text-gray-700">
                                {{ $delivery->appointment?->caseOrder?->clinic?->clinic_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3 text-gray-700">
                                {{ $delivery->appointment?->material?->material_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3 text-gray-700">
                                @if($delivery->delivery_status == 'delivered')
                                    <span class="status-badge bg-green-100 text-green-800">Delivered</span>
                                @elseif($delivery->delivery_status == 'cancelled')
                                    <span class="status-badge bg-red-100 text-red-800">Cancelled</span>
                                @else
                                    <form action="{{ route('rider.deliveries.updateStatus', $delivery->delivery_id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <select name="delivery_status"
                                                class="border border-gray-300 rounded-md shadow-sm py-1 px-2 text-xs focus:ring-[#189ab4] focus:border-[#189ab4] w-32"
                                                onchange="this.form.submit()">
                                            <option value="ready to deliver" {{ $delivery->delivery_status == 'ready to deliver' ? 'selected' : '' }}>Ready to Deliver</option>
                                            <option value="in transit" {{ $delivery->delivery_status == 'in transit' ? 'selected' : '' }}>In Transit</option>
                                            <option value="delivered" {{ $delivery->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $delivery->delivery_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                @if($delivery->delivery_status == 'ready to deliver')
                                    <form action="{{ route('rider.deliveries.updateStatus', $delivery->delivery_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="delivery_status" value="in transit">
                                        <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors">
                                            Start Delivery
                                        </button>
                                    </form>
                                @elseif($delivery->delivery_status == 'in transit')
                                    <form action="{{ route('rider.deliveries.markDelivered', $delivery->delivery_id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to mark this delivery as DELIVERED?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors">
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
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">No deliveries assigned to you.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 3. Scripts --}}
<script>
    // Auto-hide toast notifications after 5 seconds
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
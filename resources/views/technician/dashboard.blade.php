@extends('layouts.technician_rider')

@section('title', 'Technician Home')

@section('content')
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

<div class="container mx-auto mt-20 px-4">

        @if($appointments->isEmpty())
            <p class="text-gray-600 text-center">No appointments assigned to you.</p>
        @else
          <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-blue-900 text-white">
                            <th class="px-6 py-3 text-lef">Appointment #</th>
                            <th class="px-6 py-3 text-lef">Clinic</th>
                            <th class="px-6 py-3 text-lef">Case Type</th>
                            <th class="px-6 py-3 text-lef">Note</th>
                            <th class="px-6 py-3 text-lef">Work Status</th>
                            <th class="px-6 py-3 text-lef">Material</th>
                            <th class="px-6 py-3 text-lef">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            @php
                                $isFinished = $appointment->work_status === 'finished';
                                $materialName = $appointment->material ? $appointment->material->name : 'Not Selected';
                            @endphp
                               <tr class="hover:bg-blue-50 transition">
                              
                                <td class="px-6 py-3 text-gray-700">{{ $appointment->appointment_id }}</td>

                             
                                <td class="px-6 py-3 text-gray-700">
                                    {{ $appointment->caseOrder?->clinic?->clinic_name ?? 'N/A' }}
                                </td>

                             
                                <td class="px-6 py-3 text-gray-700">
                                    {{ $appointment->caseOrder?->case_type ?? 'N/A' }}
                                </td>

                             
                              <td class="px-6 py-3 text-gray-700">
    <div class="mt-1 px-2 py-1 border rounded bg-gray-100 whitespace-pre-line max-w-xs max-h-16 overflow-auto">
        {{ $appointment->caseOrder?->notes ?? 'N/A' }}
    </div>
</td>

                                {{-- Work Status --}}
                                <td class="px-6 py-3 text-gray-700">
                                    <form id="workStatusForm_{{ $appointment->appointment_id }}" 
                                          action="{{ route('technician.appointment.update', $appointment->appointment_id) }}" 
                                          method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="appointment_id" value="{{ $appointment->appointment_id }}">
                                        <select name="work_status"
                                                class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-[#189ab4] focus:border-[#189ab4] bg-white {{ $isFinished ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : '' }}"
                                                {{ $isFinished ? 'disabled' : '' }}
                                                data-previous-value="{{ $appointment->work_status }}">
                                            <option value="in progress" {{ $appointment->work_status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="finished" {{ $appointment->work_status == 'finished' ? 'selected' : '' }}>Finished</option>
                                        </select>
                                    </form>
                                </td>

                               
                                <td class="px-6 py-3 text-gray-700">
                                    <form id="materialForm_{{ $appointment->appointment_id }}" 
                                          action="{{ route('technician.appointment.update', $appointment->appointment_id) }}" 
                                          method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="appointment_id" value="{{ $appointment->appointment_id }}">
                                        <select name="material_id"
                                                class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-[#189ab4] focus:border-[#189ab4] bg-white w-32 {{ $isFinished ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : '' }}"
                                                onchange="this.form.submit()"
                                                {{ $isFinished ? 'disabled' : '' }}>
                                            <option value="">Select Material</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->material_id }}" 
                                                        {{ $appointment->material_id == $material->material_id ? 'selected' : '' }}>
                                                    {{ $material->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>

                               
                                <td class="px-6 py-3 text-gray-700">
                                    @if($isFinished)
                                        <span class="text-gray-500 text-xs font-semibold">✓ Completed</span>
                                    @else
                                        <span class="text-gray-500 text-xs">Awaiting Updates</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>


<div id="finishModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Confirm Finish</h2>
        <p class="text-gray-600 mb-6">Are you sure you want to mark this appointment as finished?</p>
        <div class="flex justify-end gap-3">
            <button id="cancelFinishBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmFinishBtn" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Confirm</button>
        </div>
    </div>
</div>


<script>
 
document.querySelectorAll('select[name="work_status"]').forEach(select => {
    select.addEventListener('change', function(event) {
        const form = this.form;
        const materialSelect = form.querySelector('select[name="material_id"]');

    
        if (this.value === 'finished' && (!materialSelect.value || materialSelect.value === "")) {
            event.preventDefault();
            alert("Please select a material before marking this as finished.");
            this.value = this.dataset.previousValue; 
            return;
        }

        if (this.value === 'finished') {
            event.preventDefault();
            currentForm = form;
            finishModal.classList.remove('hidden');
        } else {
            form.submit();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
  
    const toastSuccess = document.getElementById('successToast');
    if (toastSuccess) setTimeout(() => { toastSuccess.style.opacity = '0'; setTimeout(()=>toastSuccess.remove(), 500); }, 3000);

    const toastError = document.getElementById('errorToast');
    if (toastError) setTimeout(() => { toastError.style.opacity = '0'; setTimeout(()=>toastError.remove(), 500); }, 3000);

   
    const finishModal = document.getElementById('finishModal');
    const confirmBtn = document.getElementById('confirmFinishBtn');
    const cancelBtn = document.getElementById('cancelFinishBtn');

    let currentForm = null;

   
    document.querySelectorAll('select[name="work_status"]').forEach(select => {
        select.addEventListener('change', function(event) {
            if (this.value === 'finished') {
                event.preventDefault();
                currentForm = this.form;
                finishModal.classList.remove('hidden');
            } else {
                this.form.submit();
            }
        });
    });

    
    confirmBtn.addEventListener('click', function() {
        if (currentForm) currentForm.submit();
        finishModal.classList.add('hidden');
    });

  
    cancelBtn.addEventListener('click', function() {
        if (currentForm) {
            const previousValue = currentForm.querySelector('select[name="work_status"]').dataset.previousValue;
            currentForm.querySelector('select[name="work_status"]').value = previousValue;
        }
        finishModal.classList.add('hidden');
        currentForm = null;
    });
});
</script>
@endsection

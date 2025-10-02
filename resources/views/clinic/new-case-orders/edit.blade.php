@extends('layouts.clinic')

@section('content')
<main class="flex-1 p-6 overflow-y-auto">
    <h2 class="text-3xl font-bold text-blue-600 text-center mb-10">Edit Case Order</h2>

    <div class="max-w-2xl mx-auto bg-white p-10 border-2 border-gray-300 rounded-md shadow-lg">
        <div class="text-center mb-6">
            <div class="flex items-center justify-center mb-6 space-x-4">
                <img id="clinic-logo" src="/logoclinic.png" alt="Clinic Logo" class="w-24 h-24 object-contain rounded-full" />
                <div>
                    <h3 class="text-xl font-semibold text-gray-700 uppercase tracking-wide">
                        {{ Auth::user()->name }}
                    </h3>
                    <p class="text-sm text-gray-500">Edit Case Order Form</p>
                </div>
            </div>
            <hr class="my-4 border-t-2 border-gray-300">
        </div>

       <form action="{{ route('clinic.new-case-orders.update', $caseOrder->co_id) }}" method="POST">
    @csrf
    @method('PUT')


            <div class="mb-4">
                <label for="patient_name" class="block font-medium">Patient Name</label>
                <input type="text" id="patient_name" name="patient_name" 
                       value="{{ old('patient_name', $caseOrder->patient_name) }}" 
                       required class="mt-1 w-full border border-gray-400 rounded px-4 py-2" />
            </div>

            <div class="mb-4">
                <label for="case_date" class="block font-medium">Case Date</label>
                <input type="date" id="case_date" name="case_date" 
                       value="{{ old('case_date', $caseOrder->case_date) }}" 
                       required class="mt-1 w-full border border-gray-400 rounded px-4 py-2" />
            </div>

            <div class="mb-4">
                <label for="case_time" class="block font-medium">Case Time</label>
                <input type="time" id="case_time" name="case_time" 
                       value="{{ old('case_time', $caseOrder->case_time) }}" 
                       required class="mt-1 w-full border border-gray-400 rounded px-4 py-2" />
            </div>

            <div class="mb-4">
                <label for="case_type" class="block text-sm font-medium text-gray-700">Case Type</label>
              <select id="case_type" name="case_type" class="mt-1 block w-full border rounded-md shadow-sm">
    <option value="" disabled selected>-- Select Case Type --</option>
    @foreach ($values as $value)
        <option value="{{ $value }}" 
            {{ old('case_type', $caseOrder->case_type) == $value ? 'selected' : '' }}>
            {{ $value }}
        </option>
    @endforeach
</select>

            </div>

            <div class="mb-4">
                <label for="notes" class="block font-medium">Notes / Special Instructions</label>
                <textarea id="notes" name="notes" rows="3" 
                          class="mt-1 w-full border border-gray-400 rounded px-4 py-2" 
                          placeholder="Optional notes...">{{ old('notes', $caseOrder->notes) }}</textarea>
            </div>

            <div class="text-center mt-4 flex justify-between">
             <a href="{{ route('clinic.new-case-orders.index') }}" 
   class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition">
    Cancel
</a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Update Case Order
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

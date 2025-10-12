@extends('layouts.clinic')

@section('page-title', 'Patient List')

@section('content')
  @if(session('success'))
            <div id="successToast" class="max-w-sm w-full bg-green-500 text-white px-4 py-3 rounded shadow-lg transform translate-x-20 opacity-0 transition-all duration-500">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="errorToast" class="max-w-sm w-full bg-red-500 text-white px-4 py-3 rounded shadow-lg transform translate-x-20 opacity-0 transition-all duration-500">
                {{ session('error') }}
            </div>
        @endif
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">
<button id="openAddPatientModal"
    class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition">
    + Add Patient
</button>

<div class="overflow-x-auto rounded-xl shadow-lg mt-4">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-blue-900 text-white">
    <th class="px-6 py-3 text-left">Patient Name</th>
    <th class="px-6 py-3 text-left">Email Address</th>
    <th class="px-6 py-3 text-left">Contact No.</th>
    <th class="px-6 py-3 text-left">Address</th>
    <th class="px-6 py-3 text-left">Assigned Doctor</th> 
    <th class="px-6 py-3 text-left">Actions</th>
</tr>
</thead>
<tbody>
    @forelse($patients as $patient)
    <tr>
        <td class="px-6 py-3 font-semibold text-gray-800">{{ $patient->patient_name }}</td>
        <td class="px-6 py-3 text-gray-700">{{ $patient->email }}</td>
        <td class="px-6 py-3 text-gray-700">{{ $patient->contact_number }}</td>
        <td class="px-6 py-3 text-gray-700">{{ $patient->address }}</td>
        <td class="px-6 py-3 text-gray-700">
          Dr.  {{ $patient->dentist_name ?? 'N/A' }} {{-- display assigned doctor --}}
        </td>
        <td class="px-6 py-3 flex gap-2">
            <button 
                onclick="openEditPatientModal(
                    {{ $patient->patient_id }},
                    '{{ addslashes($patient->patient_name) }}',
                    '{{ addslashes($patient->email) }}',
                    '{{ addslashes($patient->contact_number) }}',
                    '{{ addslashes($patient->address) }}',
                    {{ $patient->dentist_id ?? 'null' }}
                )"
                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                Edit
            </button>
            <button
                onclick="openDeletePatientModal({{ $patient->patient_id }}, '{{ addslashes($patient->patient_name) }}')"
                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                Delete
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="p-4 text-gray-500 text-center bg-white">No patients found.</td>
    </tr>
    @endforelse
</tbody>

    </table>
</div><div id="addPatientModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative animate__animated" id="modalContent">

    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
      <h2 class="text-2xl font-semibold text-gray-800">Add Patient</h2>
      <button id="closeAddPatientModal" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
    </div>
   
    <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
      <span class="px-4 py-2 border-b-2 border-white font-medium">Patient Details</span>
    </div>
  
    <div class="p-6 space-y-6">
      <form id="addPatientForm" action="{{ route('clinic.patients.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="patient_name" id="patient_name"
                   class="mt-1 block w-full border-b-2 border-gray-300 focus:border-green-600 focus:outline-none text-lg py-2" required>
            <p id="nameError" class="text-red-500 text-sm mt-1 hidden">Full name should contain only letters and spaces.</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" placeholder="optional"
                   class="mt-1 block w-full border-b-2 border-gray-300 focus:border-green-600
                          focus:outline-none text-lg py-2 placeholder-gray-400 italic">
            <p id="emailError" class="text-red-500 text-sm mt-1 hidden">Please enter a valid email address.</p>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Contact Number</label>
            <input type="text" name="contact_number" id="contact_number"
                   class="mt-1 block w-full border-b-2 border-gray-300 focus:border-green-600
                          focus:outline-none text-lg py-2">
            <p id="contactError" class="text-red-500 text-sm mt-1 hidden">Contact number should contain digits only.</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" name="address" id="address"
                   class="mt-1 block w-full border-b-2 border-gray-300 focus:border-green-600
                          focus:outline-none text-lg py-2">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Assigned Doctor</label>
          <select name="dentist_id" id="dentist_id"
                  class="mt-1 block w-full border-b-2 border-gray-300 focus:border-green-600
                         focus:outline-none text-lg py-2" required>
              <option value=""> </option>
              @foreach($dentists as $dentist)
                  <option value="{{ $dentist->dentist_id }}">Dr. {{ $dentist->name }}</option>
              @endforeach
          </select>
        </div>
      </form>
    </div>

    <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
      <button id="cancelAddPatient" class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">Cancel</button>
      <button type="submit" form="addPatientForm"
              class="py-2 px-4 rounded-md bg-green-600 text-white font-medium hover:bg-green-700 shadow-md">Save Patient</button>
    </div>
  </div>
</div>

<!-- ===================== EDIT MODAL ====================== -->
<div id="editPatientModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div id="editModalContent" class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative animate__animated">
    
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
      <h2 class="text-2xl font-semibold text-gray-800">Edit Patient</h2>
      <button onclick="closeEditPatientModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
    </div>
    
    <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
      <span class="px-4 py-2 border-b-2 border-white font-medium">Patient Details</span>
    </div>
    
    <div class="p-6 space-y-6">
      <form id="editPatientForm" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="editPatientName" name="patient_name"
                   class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2" required>
            <p id="editNameError" class="text-red-500 text-sm hidden">Full name must contain letters only.</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="editPatientEmail" name="email"
                   class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
            <p id="editEmailError" class="text-red-500 text-sm hidden">Invalid email format.</p>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Contact Number</label>
            <input type="text" id="editPatientContact" name="contact_number"
                   class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
            <p id="editContactError" class="text-red-500 text-sm hidden">Contact must start with 09 and be 11 digits.</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" id="editPatientAddress" name="address"
                   class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                          focus:outline-none text-lg py-2">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Assigned Doctor</label>
          <select id="editPatientDoctor" name="dentist_id"
                  class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600
                         focus:outline-none text-lg py-2" required>
            <option value=""> </option>
            @foreach($dentists as $dentist)
              <option value="{{ $dentist->dentist_id }}">Dr. {{ $dentist->name }}</option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
    
    <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
      <button onclick="closeEditPatientModal()" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
      <button type="submit" form="editPatientForm"
              class="py-2 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md">Update</button>
    </div>
  </div>
</div>

<!-- ===================== DELETE MODAL ====================== -->
<div id="deletePatientModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 animate__animated" id="deleteModalContent">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Delete Patient</h2>
    <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="deletePatientName" class="font-semibold"></span>?</p>
    <form id="deletePatientForm" method="POST">
      @csrf
      @method('DELETE')
      <div class="flex justify-end space-x-4">
        <button type="button" onclick="closeDeletePatientModal()" class="px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
      </div>
    </form>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- ===================== SCRIPTS SECTION ====================== -->
<script>
document.addEventListener("DOMContentLoaded", () => {

  // ===================== ADD MODAL LOGIC ======================
  const addModal = document.getElementById('addPatientModal');
  const modal = document.getElementById('modalContent');
  const nameInput = document.getElementById('patient_name');
  const emailInput = document.getElementById('email');
  const contactInput = document.getElementById('contact_number');
  const nameError = document.getElementById('nameError');
  const emailError = document.getElementById('emailError');
  const contactError = document.getElementById('contactError');

  document.getElementById('openAddPatientModal')?.addEventListener('click', () => addModal.classList.remove('hidden'));
  document.getElementById('closeAddPatientModal').addEventListener('click', () => addModal.classList.add('hidden'));
  document.getElementById('cancelAddPatient').addEventListener('click', () => addModal.classList.add('hidden'));

  function shakeField(field) {
    modal.classList.remove('animate__shakeX');
    void modal.offsetWidth;
    modal.classList.add('animate__shakeX');
    field.classList.add('border-red-500');
    setTimeout(() => field.classList.remove('border-red-500'), 800);
  }

  function validateName() {
    const original = nameInput.value;
    const sanitized = original.replace(/[^A-Za-zÑñ\s]/g, '');
    if (original !== sanitized) {
      nameInput.value = sanitized;
      nameError.classList.remove('hidden');
      shakeField(nameInput);
      return false;
    }
    if (/^[A-Za-zÑñ\s]+$/.test(sanitized) && sanitized.trim().length >= 2) {
      nameError.classList.add('hidden');
      return true;
    } else {
      nameError.classList.remove('hidden');
      shakeField(nameInput);
      return false;
    }
  }

  function validateEmail() {
    const value = emailInput.value.trim();
    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || value === '';
    if (!valid) {
      emailError.classList.remove('hidden');
      shakeField(emailInput);
      return false;
    } else {
      emailError.classList.add('hidden');
      return true;
    }
  }

  function validateContact() {
    let value = contactInput.value.replace(/[^0-9]/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    contactInput.value = value;
    if (value === '' || /^09\d{9}$/.test(value)) {
      contactError.classList.add('hidden');
      return true;
    } else {
      contactError.classList.remove('hidden');
      shakeField(contactInput);
      return false;
    }
  }

  nameInput.addEventListener('input', validateName);
  emailInput.addEventListener('input', validateEmail);
  contactInput.addEventListener('input', validateContact);

  document.getElementById('addPatientForm').addEventListener('submit', e => {
    if (!validateName() || !validateEmail() || !validateContact()) {
      e.preventDefault();
      shakeField(modal);
    }
  });
// ===================== EDIT MODAL VALIDATION ======================
const editModal = document.getElementById('editModalContent');
const editNameInput = document.getElementById('editPatientName');
const editEmailInput = document.getElementById('editPatientEmail');
const editContactInput = document.getElementById('editPatientContact');
const editNameError = document.getElementById('editNameError');
const editEmailError = document.getElementById('editEmailError');
const editContactError = document.getElementById('editContactError');

function editShakeField(field) {
  editModal.classList.remove('animate__shakeX');
  void editModal.offsetWidth;
  editModal.classList.add('animate__shakeX');
  field.classList.add('border-red-500');
  setTimeout(() => field.classList.remove('border-red-500'), 800);
}

function validateEditName() {
  const original = editNameInput.value;
  const sanitized = original.replace(/[^A-Za-zÑñ\s]/g, '');
  if (original !== sanitized) {
    editNameInput.value = sanitized;
    editNameError.classList.remove('hidden');
    editShakeField(editNameInput);
    return false;
  }
  if (/^[A-Za-zÑñ\s]+$/.test(sanitized) && sanitized.trim().length >= 2) {
    editNameError.classList.add('hidden');
    return true;
  } else {
    editNameError.classList.remove('hidden');
    editShakeField(editNameInput);
    return false;
  }
}

function validateEditEmail() {
  const value = editEmailInput.value.trim();
  const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || value === '';
  if (!valid) {
    editEmailError.classList.remove('hidden');
    editShakeField(editEmailInput);
    return false;
  } else {
    editEmailError.classList.add('hidden');
    return true;
  }
}

function validateEditContact() {
  let value = editContactInput.value.replace(/[^0-9]/g, '');
  if (value.length > 11) value = value.slice(0, 11);
  editContactInput.value = value;
  if (value === '' || /^09\d{9}$/.test(value)) {
    editContactError.classList.add('hidden');
    return true;
  } else {
    editContactError.classList.remove('hidden');
    editShakeField(editContactInput);
    return false;
  }
}

editNameInput.addEventListener('input', validateEditName);
editEmailInput.addEventListener('input', validateEditEmail);
editContactInput.addEventListener('input', validateEditContact);

document.getElementById('editPatientForm').addEventListener('submit', e => {
  if (!validateEditName() || !validateEditEmail() || !validateEditContact()) {
    e.preventDefault();
    editShakeField(editModal);
  }
});

  // ===================== EDIT MODAL LOGIC ======================
  window.openEditPatientModal = (id, name, email, contact, address) => {
    const modal = document.getElementById('editPatientModal');
    const form = document.getElementById('editPatientForm');
    form.action = `/clinic/patients/${id}`;
    document.getElementById('editPatientName').value = name;
    document.getElementById('editPatientEmail').value = email || '';
    document.getElementById('editPatientContact').value = contact || '';
    document.getElementById('editPatientAddress').value = address || '';
    modal.classList.remove('hidden');
  };

  window.closeEditPatientModal = () => {
    document.getElementById('editPatientModal').classList.add('hidden');
  };

  // ===================== DELETE MODAL LOGIC ======================
  window.openDeletePatientModal = (id, name) => {
    const form = document.getElementById('deletePatientForm');
    form.action = `/clinic/patients/${id}`;
    document.getElementById('deletePatientName').innerText = name;
    document.getElementById('deletePatientModal').classList.remove('hidden');
  };

  window.closeDeletePatientModal = () => {
    document.getElementById('deletePatientModal').classList.add('hidden');
  };

  // ===================== TOAST ANIMATION ======================
  const successToast = document.getElementById('successToast');
  const errorToast = document.getElementById('errorToast');
  [successToast, errorToast].forEach(toast => {
    if (toast) {
      setTimeout(() => {
        toast.classList.remove('translate-x-20', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
      }, 100);
      setTimeout(() => {
        toast.classList.add('translate-x-20', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
      }, 4100);
    }
  });
});
</script>
@endsection

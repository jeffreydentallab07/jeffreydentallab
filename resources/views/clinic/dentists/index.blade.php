@extends('layouts.clinic')

@section('page-title', 'Dentist List')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">
    <button id="openAddDentistModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition">
        + Add Dentist
    </button>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left"></th>
                    <th class="px-6 py-3 text-left">Full Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Contact</th>
                    <th class="px-6 py-3 text-left">Address</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dentists as $dentist)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-6 py-3">
                        @if($dentist->photo)
                            <img src="{{ asset('storage/' . $dentist->photo) }}" alt="Dentist Photo"
                                 class="w-12 h-12 rounded-full object-cover border">
                        @else
                            <img src="https://via.placeholder.com/50" alt="Default Photo"
                                 class="w-12 h-12 rounded-full object-cover border">
                        @endif
                    </td>
                    <td class="px-6 py-3 font-semibold text-gray-800">Dr. {{ $dentist->name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $dentist->email }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $dentist->contact_number }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $dentist->address }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <button 
                            onclick="openEditDentistModal({{ $dentist->dentist_id }}, '{{ addslashes($dentist->name) }}', '{{ addslashes($dentist->email) }}', '{{ addslashes($dentist->contact_number) }}', '{{ addslashes($dentist->address) }}', '{{ $dentist->photo }}')"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            Edit
                        </button>
                        <button 
                            onclick="openDeleteDentistModal({{ $dentist->dentist_id }}, '{{ addslashes($dentist->name) }}', '{{ $dentist->photo }}')"
                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-gray-500 text-center bg-white">No dentists found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

   
    <div id="addDentistModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="relative">
              <img id="previewAddPhoto" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md"
                   src="https://via.placeholder.com/150" alt="Dentist Profile">
              <input type="file" name="photo" form="addDentistForm"
                     class="absolute bottom-0 right-0 text-xs opacity-0 cursor-pointer w-20 h-20"
                     onchange="previewAddImage(event)">
            </div>
            <div>
              <h2 class="text-2xl font-semibold text-gray-800">Add Dentist</h2>
              <p class="text-sm text-gray-500">Fill out dentist details</p>
            </div>
          </div>
          <button id="closeAddDentistModal" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
          <span class="px-4 py-2 border-b-2 border-white font-medium">Dentist Details</span>
        </div>
        <div class="p-6 space-y-6">
          <form id="addDentistForm" action="{{ route('clinic.dentists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" name="contact_number" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="address" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
              </div>
            </div>
          </form>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
          <button id="cancelAddDentist" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
          <button type="submit" form="addDentistForm" class="py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md">Save</button>
        </div>
      </div>
    </div>

    <!-- Edit Dentist Modal -->
    <div id="editDentistModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="relative">
              <img id="previewEditPhoto" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md" src="https://via.placeholder.com/150" alt="Dentist Profile">
              <input type="file" name="photo" form="editDentistForm" class="absolute bottom-0 right-0 text-xs opacity-0 cursor-pointer w-20 h-20" onchange="previewEditImage(event)">
            </div>
            <div>
              <h2 class="text-2xl font-semibold text-gray-800">Edit Dentist</h2>
              <p class="text-sm text-gray-500">Update dentist details</p>
            </div>
          </div>
          <button onclick="closeEditDentistModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
          <span class="px-4 py-2 border-b-2 border-white font-medium">Dentist Details</span>
        </div>
        <div class="p-6 space-y-6">
          <form id="editDentistForm" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" id="editName" name="name" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="editEmail" name="email" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input type="text" id="editContact" name="contact_number" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" id="editAddress" name="address" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
              </div>
            </div>
          </form>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
          <button onclick="closeEditDentistModal()" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
          <button type="submit" form="editDentistForm" class="py-2 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md">Update</button>
        </div>
      </div>
    </div>

    <!-- Delete Dentist Modal -->
    <div id="deleteDentistModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-lg w-96 p-6">
        <h2 class="text-lg font-semibold text-gray-800">Delete Dentist</h2>
        <div class="flex items-center gap-4 mt-2">
            <img id="deleteDentistPhoto" src="https://via.placeholder.com/50" alt="Dentist Photo" class="w-12 h-12 rounded-full object-cover border">
            <p class="text-gray-600">Are you sure you want to delete <span id="deleteDentistName" class="font-semibold"></span>?</p>
        </div>
        <form id="deleteDentistForm" method="POST" class="mt-4 flex justify-end gap-2">
          @csrf
          @method('DELETE')
          <button type="button" onclick="closeDeleteDentistModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </form>
      </div>
    </div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
      // Add Dentist Modal
      const addModal = document.getElementById('addDentistModal');
      document.getElementById('openAddDentistModal').addEventListener('click', () => addModal.classList.remove('hidden'));
      document.getElementById('closeAddDentistModal').addEventListener('click', () => addModal.classList.add('hidden'));
      document.getElementById('cancelAddDentist').addEventListener('click', () => addModal.classList.add('hidden'));
  });

  // Preview images
  function previewAddImage(event) {
      document.getElementById('previewAddPhoto').src = URL.createObjectURL(event.target.files[0]);
  }
  function previewEditImage(event) {
      document.getElementById('previewEditPhoto').src = URL.createObjectURL(event.target.files[0]);
  }

  // Edit Dentist Modal
  function openEditDentistModal(id, name, email, contact, address, photo) {
      const form = document.getElementById('editDentistForm');
      form.action = `/clinic/dentists/${id}`;
      document.getElementById('editName').value = name;
      document.getElementById('editEmail').value = email;
      document.getElementById('editContact').value = contact;
      document.getElementById('editAddress').value = address;
      document.getElementById('previewEditPhoto').src = photo ? '/storage/' + photo : 'https://via.placeholder.com/150';
      document.getElementById('editDentistModal').classList.remove('hidden');
  }
  function closeEditDentistModal() {
      document.getElementById('editDentistModal').classList.add('hidden');
  }

  // Delete Dentist Modal
  function openDeleteDentistModal(id, name, photo = null) {
      const form = document.getElementById('deleteDentistForm');
      form.action = `/clinic/dentists/${id}`;
      document.getElementById('deleteDentistName').innerText = name;
      document.getElementById('deleteDentistPhoto').src = photo ? '/storage/' + photo : 'https://via.placeholder.com/50';
      document.getElementById('deleteDentistModal').classList.remove('hidden');
  }
  function closeDeleteDentistModal() {
      document.getElementById('deleteDentistModal').classList.add('hidden');
  }
</script>
@endsection

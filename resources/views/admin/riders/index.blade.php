@extends('layouts.app')

@section('page-title', 'Rider List')

@section('content')
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">
    <button id="openAddRiderModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition">
        + Add Rider
    </button>

    <div class="overflow-x-auto rounded-xl shadow-lg mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Photo</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Contact</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riders as $rider)
                <tr class="hover:bg-gray-100 transition">
                    <td class="px-6 py-3">
                        <img src="{{ $rider->photo ? asset('storage/'.$rider->photo) : asset('images/default-avatar.png') }}"
                             class="w-12 h-12 rounded-full object-cover border" alt="Rider Photo">
                    </td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $rider->name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $rider->email }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $rider->contact_number ?? 'N/A' }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <button 
                            onclick="openEditRiderModal({{ $rider->id }}, '{{ addslashes($rider->name) }}', '{{ addslashes($rider->email) }}', '{{ addslashes($rider->contact_number ?? '') }}')"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            Edit
                        </button>
                        <button 
                            onclick="openDeleteRiderModal({{ $rider->id }}, '{{ addslashes($rider->name) }}')"
                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-gray-500 text-center bg-white">No riders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ADD RIDER MODAL -->
<div id="addRiderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-800">Add Rider</h2>
            <button id="closeAddRiderModal" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>

        <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
            <span class="px-4 py-2 border-b-2 border-white font-medium">Rider Details</span>
        </div>

        <form id="addRiderForm" action="{{ route('riders.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="text" name="contact_number" class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" name="photo" accept="image/*"
                        class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" minlength="6"
                    class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
            </div>
        </form>

        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button id="cancelAddRider" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <button type="submit" form="addRiderForm" class="py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md">Save</button>
        </div>
    </div>
</div>

<!-- EDIT RIDER MODAL -->
<div id="editRiderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Rider</h2>
            <button onclick="closeEditRiderModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>

        <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
            <span class="px-4 py-2 border-b-2 border-white font-medium">Rider Details</span>
        </div>

        <form id="editRiderForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="editRiderName" name="name" class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 text-lg py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="editRiderEmail" name="email" class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 text-lg py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="text" id="editRiderContact" name="contact_number" class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 text-lg py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" id="editRiderPhoto" name="photo" accept="image/*" class="mt-1 w-full border-b-2 border-gray-300 focus:border-indigo-600 text-lg py-2">
                </div>
            </div>
        </form>

        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button onclick="closeEditRiderModal()" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <button type="submit" form="editRiderForm" class="py-2 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md">Update</button>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div id="deleteRiderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-800">Delete Rider</h2>
            <button onclick="closeDeleteRiderModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="p-6 text-center space-y-4">
            <p class="text-gray-700 text-lg">Are you sure you want to delete <span id="deleteRiderName" class="font-semibold text-red-600"></span>?</p>
            <p class="text-gray-500 text-sm">This action cannot be undone.</p>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button onclick="closeDeleteRiderModal()" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <form id="deleteRiderForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="py-2 px-4 rounded-md bg-red-600 text-white font-medium hover:bg-red-700 shadow-md">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteRiderModal(id, name) {
    const modal = document.getElementById('deleteRiderModal');
    const form = document.getElementById('deleteRiderForm');
    const nameSpan = document.getElementById('deleteRiderName');
    form.action = `/riders/${id}`;
    nameSpan.textContent = name;
    modal.classList.remove('hidden');
}

function closeDeleteRiderModal() {
    document.getElementById('deleteRiderModal').classList.add('hidden');
}

document.addEventListener("DOMContentLoaded", () => {
    const addModal = document.getElementById('addRiderModal');
    document.getElementById('openAddRiderModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddRiderModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddRider').addEventListener('click', () => addModal.classList.add('hidden'));
});

function openEditRiderModal(id, name, email, contact) {
    const modal = document.getElementById('editRiderModal');
    const form = document.getElementById('editRiderForm');
    form.action = `/riders/${id}`;
    document.getElementById('editRiderName').value = name;
    document.getElementById('editRiderEmail').value = email;
    document.getElementById('editRiderContact').value = contact;
    modal.classList.remove('hidden');
}

function closeEditRiderModal() {
    document.getElementById('editRiderModal').classList.add('hidden');
}
</script>
@endsection

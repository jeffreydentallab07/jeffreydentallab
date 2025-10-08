@extends('layouts.app')

@section('content')


<div class="p-6 space-y-6 bg-gray-300 min-h-screen">
    <button id="openAddTechnicianModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition">
        + Add Technician
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
                @forelse($technicians as $tech)
                <tr class="hover:bg-gray-100 transition">
                    <td class="px-6 py-3">
                        <img src="{{ $tech->photo ? asset('storage/'.$tech->photo) : asset('images/default-avatar.png') }}"
                             class="w-12 h-12 rounded-full object-cover border" alt="Tech Photo">
                    </td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $tech->name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $tech->email }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $tech->contact_number ?? 'N/A' }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <button 
                            onclick="openEditTechnicianModal({{ $tech->id }}, '{{ addslashes($tech->name) }}', '{{ addslashes($tech->email) }}', '{{ addslashes($tech->contact_number ?? '') }}')"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                             Edit
                        </button>
                       <button
    onclick="openDeleteTechnicianModal({{ $tech->id }}, '{{ addslashes($tech->name) }}')"
    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
     Delete
</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-gray-500 text-center bg-white">No technicians found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="deleteTechnicianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-800">Delete Technician</h2>
            <button onclick="closeDeleteTechnicianModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>

        <div class="p-6 text-center space-y-4">
            <p class="text-gray-700 text-lg">Are you sure you want to delete <span id="deleteTechnicianName" class="font-semibold text-red-600"></span>?</p>
            <p class="text-gray-500 text-sm">This action cannot be undone.</p>
        </div>

        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button onclick="closeDeleteTechnicianModal()" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <form id="deleteTechnicianForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="py-2 px-4 rounded-md bg-red-600 text-white font-medium hover:bg-red-700 shadow-md">Delete</button>
            </form>
        </div>
    </div>
</div>

<div id="addTechnicianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-800">Add Technician</h2>
            <button id="closeAddTechnicianModal" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
            <span class="px-4 py-2 border-b-2 border-white font-medium">Technician Details</span>
        </div>

        <div class="p-6 space-y-6">
            <form id="addTechnicianForm" action="{{ route('technicians.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" name="contact_number"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Photo</label>
                        <input type="file" name="photo" accept="image/*"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" minlength="6"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" minlength="6"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button id="cancelAddTechnician" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <button type="submit" form="addTechnicianForm" class="py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md">Save</button>
        </div>
    </div>
</div>


<div id="editTechnicianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Technician</h2>
            <button onclick="closeEditTechnicianModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
            <span class="px-4 py-2 border-b-2 border-white font-medium">Technician Details</span>
        </div>

        <div class="p-6 space-y-6">
            <form id="editTechnicianForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="editTechnicianName" name="name"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="editTechnicianEmail" name="email"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" id="editTechnicianContact" name="contact_number"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Photo</label>
                        <input type="file" id="editTechnicianPhoto" name="photo" accept="image/*"
                            class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                    </div>
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button onclick="closeEditTechnicianModal()" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <button type="submit" form="editTechnicianForm" class="py-2 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md">Update</button>
        </div>
    </div>
</div>

<script>
    function openDeleteTechnicianModal(id, name) {
    const modal = document.getElementById('deleteTechnicianModal');
    const form = document.getElementById('deleteTechnicianForm');
    const nameSpan = document.getElementById('deleteTechnicianName');

    nameSpan.textContent = name;
    form.action = `/technicians/${id}`;
    modal.classList.remove('hidden');
}

function closeDeleteTechnicianModal() {
    document.getElementById('deleteTechnicianModal').classList.add('hidden');
}
document.addEventListener("DOMContentLoaded", () => {
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

    const addModal = document.getElementById('addTechnicianModal');
    document.getElementById('openAddTechnicianModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddTechnicianModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddTechnician').addEventListener('click', () => addModal.classList.add('hidden'));
});

function openEditTechnicianModal(id, name, email, contact) {
    const modal = document.getElementById('editTechnicianModal');
    const form = document.getElementById('editTechnicianForm');
    form.action = `/technicians/${id}`;
    document.getElementById('editTechnicianName').value = name;
    document.getElementById('editTechnicianEmail').value = email;
    document.getElementById('editTechnicianContact').value = contact;
    modal.classList.remove('hidden');
}

function closeEditTechnicianModal() {
    document.getElementById('editTechnicianModal').classList.add('hidden');
}


</script>
@endsection

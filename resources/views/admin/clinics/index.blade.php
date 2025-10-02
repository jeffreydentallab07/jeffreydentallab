@extends('layouts.app')

@section('page-title', 'Clinic List')

@section('content')
<link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <button id="openAddClinicModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition mb-4">
        + Add Clinic
    </button>

    @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-700 border border-green-300">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto rounded-xl shadow-lg">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Clinic ID</th>
                    <th class="px-6 py-3 text-left">Owner Name</th>
                    <th class="px-6 py-3 text-left">Clinic Name</th>
                    <th class="px-6 py-3 text-left">Address</th>
                    <th class="px-6 py-3 text-left">Contact Number</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clinics as $clinic)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $clinic->clinic_id }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $clinic->owner_name }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $clinic->clinic_name }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $clinic->address }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $clinic->contact_number }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $clinic->email }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <button data-id="{{ $clinic->clinic_id }}" class="editBtn px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Edit
                        </button>
                        <form action="{{ route('clinics.destroy', $clinic->clinic_id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this clinic?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500 bg-white">No clinics found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Clinic Modal -->
    <div id="addClinicModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-11/12 max-w-lg rounded-xl shadow-lg p-6 relative">
            <button id="closeAddClinicModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Add Clinic</h2>
            <form action="{{ route('clinics.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-semibold mb-1">Owner Name</label>
                    <input type="text" name="owner_name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Clinic Name</label>
                    <input type="text" name="clinic_name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Address</label>
                    <textarea name="address" rows="3" class="w-full border rounded p-2" required></textarea>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Contact Number</label>
                    <input type="text" name="contact_number" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded p-2" required>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="cancelAddClinic" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Clinic Modal -->
    <div id="editClinicModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-11/12 max-w-lg rounded-xl shadow-lg p-6 relative">
            <button id="closeEditClinicModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Edit Clinic</h2>
            <form id="editClinicForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-semibold mb-1">Owner Name</label>
                    <input type="text" name="owner_name" id="edit_owner_name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Clinic Name</label>
                    <input type="text" name="clinic_name" id="edit_clinic_name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Address</label>
                    <textarea name="address" id="edit_address" rows="3" class="w-full border rounded p-2" required></textarea>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Contact Number</label>
                    <input type="text" name="contact_number" id="edit_contact_number" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email" id="edit_email" class="w-full border rounded p-2" required>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="cancelEditClinic" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const addModal = document.getElementById('addClinicModal');
    const editModal = document.getElementById('editClinicModal');

    // Add Modal
    document.getElementById('openAddClinicModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddClinicModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddClinic').addEventListener('click', () => addModal.classList.add('hidden'));

    // Edit Modal (event delegation)
    document.addEventListener('click', function(e){
        if(e.target.closest('.editBtn')){
            const btn = e.target.closest('.editBtn');
            const clinicId = btn.dataset.id;

            fetch(`/clinics/${clinicId}/edit`)
                .then(res => res.json())
                .then(data => {
                    editModal.classList.remove('hidden');
                    const form = document.getElementById('editClinicForm');
                    form.action = `/clinics/${clinicId}`;
                    document.getElementById('edit_owner_name').value = data.owner_name;
                    document.getElementById('edit_clinic_name').value = data.clinic_name;
                    document.getElementById('edit_address').value = data.address;
                    document.getElementById('edit_contact_number').value = data.contact_number;
                    document.getElementById('edit_email').value = data.email;
                });
        }
    });

    document.getElementById('closeEditClinicModal').addEventListener('click', () => editModal.classList.add('hidden'));
    document.getElementById('cancelEditClinic').addEventListener('click', () => editModal.classList.add('hidden'));
});
</script>
@endsection

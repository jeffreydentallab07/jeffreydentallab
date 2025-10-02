@extends('layouts.app')

@section('page-title', 'Technician List')

@section('content')
<link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">

<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <button id="openAddTechnicianModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition mb-4">
        + Add Technician
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

    <div class="overflow-x-auto rounded-xl shadow-lg mt-4 mx-[15px]">
        <table class="min-w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-blue-900 text-white">
                    <th class="px-6 py-3 text-left">Photo</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Contact Number</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($technicians as $technician)
                    <tr class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <img src="{{ $technician->photo ? asset('storage/' . $technician->photo) : asset('images/default-avatar.png') }}"
                                 alt="{{ $technician->name }}" class="w-12 h-12 object-cover rounded-full mx-auto border">
                        </td>
                        <td class="px-6 py-3 font-semibold text-gray-800">{{ $technician->name }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $technician->email }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $technician->contact_number ?? 'N/A' }}</td>
                        <td class="px-6 py-3 flex gap-2">
                          <!-- Edit Technician Button -->
<button 
    onclick="openEditTechnicianModal({{ $technician->id }}, '{{ addslashes($technician->name) }}', '{{ $technician->contact_number ?? '' }}', '{{ addslashes($technician->email) }}', '{{ $technician->photo ?? '' }}')"
    class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition flex items-center justify-center"
    aria-label="Edit Technician">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
        <path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z" />
        <path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z" />
    </svg>
</button>

<!-- Delete Technician Button -->
<button 
    onclick="openDeleteTechnicianModal('{{ route('technicians.destroy', $technician->id) }}')"
    class="text-red-600 hover:text-red-800 transition p-1 rounded flex items-center justify-center"
    aria-label="Delete Technician">
     <svg xmlns="http://www.w3.org/2000/svg" 
             viewBox="0 0 16 16" 
             fill="currentColor" 
             class="w-4 h-4">
          <path fill-rule="evenodd" 
                d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" 
                clip-rule="evenodd" />
        </svg>
</button>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500 bg-white">No technicians found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Technician Modal -->
<div id="addTechnicianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img id="previewAddTechnicianPhoto" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md"
                         src="https://via.placeholder.com/150" alt="Technician Profile">
                    <input type="file" name="photo" form="addTechnicianForm"
                           class="absolute bottom-0 right-0 text-xs opacity-0 cursor-pointer w-20 h-20"
                           onchange="previewAddTechnicianImage(event)">
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Add Technician</h2>
                    <p class="text-sm text-gray-500">Fill out technician details</p>
                </div>
            </div>
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
                        <input type="text" name="name" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" name="contact_number" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2" required>
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

<!-- Edit Technician Modal -->
<div id="editTechnicianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img id="previewEditTechnicianPhoto" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md"
                         src="https://via.placeholder.com/150" alt="Technician Profile">
                    <input type="file" name="photo" form="editTechnicianForm"
                           class="absolute bottom-0 right-0 text-xs opacity-0 cursor-pointer w-20 h-20"
                           onchange="previewEditTechnicianImage(event)">
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Edit Technician</h2>
                    <p class="text-sm text-gray-500">Update technician details</p>
                </div>
            </div>
            <button onclick="closeEditTechnicianModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
            <span class="px-4 py-2 border-b-2 border-white font-medium">Technician Details</span>
        </div>
        <div class="p-6 space-y-6" id="editTechnicianModalContent"></div>
        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button onclick="closeEditTechnicianModal()" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <button onclick="submitEditTechnicianForm()" class="py-2 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md">Update</button>
        </div>
    </div>
</div>

<!-- Delete Technician Modal -->
<div id="deleteTechnicianModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden relative">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Delete Technician</h2>
            <button onclick="closeDeleteTechnicianModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="p-6 space-y-4 text-gray-700">
            <p>Are you sure you want to delete this technician? This action cannot be undone.</p>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-4">
            <button onclick="closeDeleteTechnicianModal()" class="px-4 py-2 rounded-md bg-gray-300 text-gray-700 hover:bg-gray-400">Cancel</button>
            <form id="deleteTechnicianForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview Add/Edit Technician Images
    function previewAddTechnicianImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('previewAddTechnicianPhoto').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    function previewEditTechnicianImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('previewEditTechnicianPhoto').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Add Technician Modal
    const addModal = document.getElementById('addTechnicianModal');
    document.getElementById('openAddTechnicianModal')?.addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddTechnicianModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddTechnician').addEventListener('click', () => addModal.classList.add('hidden'));

    // Edit Technician Modal
    function openEditTechnicianModal(id, name, contact_number, email, photo) {
        const modalContent = document.getElementById('editTechnicianModalContent');
        modalContent.innerHTML = `
            <form id="editTechnicianForm" method="POST" action="/technicians/${id}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" value="${name}" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2" required>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="${email}" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium text-gray-700">Contact Number</label>
                        <input type="text" name="contact_number" value="${contact_number}" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2" required>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700">Profile Photo</label>
                        <input type="file" name="photo" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none py-2">
                    </div>
                </div>
            </form>
        `;
        document.getElementById('editTechnicianModal').classList.remove('hidden');
    }
    function closeEditTechnicianModal() {
        document.getElementById('editTechnicianModal').classList.add('hidden');
    }
    function submitEditTechnicianForm() {
        document.getElementById('editTechnicianForm').submit();
    }

    // Delete Technician Modal
    function openDeleteTechnicianModal(actionUrl) {
        const form = document.getElementById('deleteTechnicianForm');
        form.action = actionUrl;
        document.getElementById('deleteTechnicianModal').classList.remove('hidden');
    }
    function closeDeleteTechnicianModal() {
        document.getElementById('deleteTechnicianModal').classList.add('hidden');
    }
</script>
@endsection

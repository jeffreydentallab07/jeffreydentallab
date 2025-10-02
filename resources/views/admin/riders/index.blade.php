@extends('layouts.app')

@section('page-title', 'Rider List')

@section('content')
<link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">

    <!-- Add Rider Button -->
    <button id="openAddRiderModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition mb-4">
        + Add Rider
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

    <div class="overflow-x-auto rounded-xl shadow-lg">
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
                @forelse($riders as $rider)
                <tr class="bg-white hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <img src="{{ $rider->photo ? asset('storage/' . $rider->photo) : asset('images/default-avatar.png') }}" 
                             alt="{{ $rider->name }}" class="w-12 h-12 object-cover rounded-full mx-auto">
                    </td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $rider->name }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $rider->email }}</td>
                    <td class="px-6 py-3 font-semibold text-gray-800">{{ $rider->contact_number ?? 'N/A' }}</td>
                    <td class="px-6 py-3 flex gap-2">
                       <!-- Edit Rider Button -->
<button 
    onclick="openEditRiderModal({{ $rider->id }}, '{{ addslashes($rider->name) }}', '{{ $rider->contact_number ?? '' }}', '{{ addslashes($rider->email) }}', '{{ $rider->photo ?? '' }}')" 
    class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition flex items-center justify-center"
    aria-label="Edit Rider">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
        <path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z" />
        <path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z" />
    </svg>
</button>

<!-- Delete Rider Button -->
<button 
    onclick="openDeleteRiderModal('{{ route('riders.destroy', $rider->id) }}')" 
    class="text-red-600 hover:text-red-800 transition p-1 rounded flex items-center justify-center"
    aria-label="Delete Rider">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
        <path fill-rule="evenodd" 
              d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" 
              clip-rule="evenodd" />
    </svg>
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

    <div id="addRiderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
        {{-- Header with Rider Photo --}}
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img id="previewAddRiderPhoto" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md"
                         src="https://via.placeholder.com/150" alt="Rider Profile">
                    <input type="file" name="photo" form="addRiderForm"
                           class="absolute bottom-0 right-0 text-xs opacity-0 cursor-pointer w-20 h-20"
                           onchange="previewAddRiderImage(event)">
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Add Rider</h2>
                    <p class="text-sm text-gray-500">Fill out rider details</p>
                </div>
            </div>
            <button id="closeAddRiderModal" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>

        {{-- Tab Header --}}
        <div class="flex border-b border-gray-200 px-6 py-2 bg-blue-900 text-white">
            <span class="px-4 py-2 border-b-2 border-white font-medium">Rider Details</span>
        </div>

        {{-- Form --}}
        <div class="p-6 space-y-6">
            <form id="addRiderForm" action="{{ route('riders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" class="mt-1 block w-full border-b-2 border-gray-300 focus:border-indigo-600 focus:outline-none text-lg py-2">
                    </div>
                </div>
            </form>
        </div>

        {{-- Footer Buttons --}}
        <div class="p-6 border-t border-gray-200 flex justify-end space-x-4">
            <button id="cancelAddRider" class="py-2 px-4 rounded-md text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
            <button type="submit" form="addRiderForm" class="py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md">Save</button>
        </div>
    </div>
</div>

<script>
    // Preview selected image for rider
    function previewAddRiderImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('previewAddRiderPhoto');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const addModal = document.getElementById('addRiderModal');
    document.getElementById('openAddRiderModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddRiderModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddRider').addEventListener('click', () => addModal.classList.add('hidden'));
});

function openEditRiderModal(id, name, contact_number, email, photo) {
    const modalContent = document.getElementById('editRiderModalContent');
    modalContent.innerHTML = `
        <form id="editRiderForm" method="POST" action="/riders/${id}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold mb-1">Full Name</label>
                <input type="text" name="name" value="${name}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Contact Number</label>
                <input type="text" name="contact_number" value="${contact_number}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="${email}" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Profile Photo</label>
                <input type="file" name="photo" class="w-full border rounded p-2">
            </div>
        </form>
    `;
    document.getElementById('editRiderModal').classList.remove('hidden');
}

function closeEditRiderModal() {
    document.getElementById('editRiderModal').classList.add('hidden');
}

function submitEditRiderForm() {
    document.getElementById('editRiderForm').submit();
}
</script>
@endsection

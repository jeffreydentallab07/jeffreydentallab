@extends('layouts.app')

@section('page-title', 'Material List')

@section('content')
<link rel="icon" type="image/png" href="{{ asset('images/logo3.png') }}">
<div class="p-6 space-y-6 bg-gray-300 min-h-screen">


    <button id="openAddMaterialModal"
        class="bg-green-500 text-white px-5 py-2 rounded font-semibold hover:bg-green-600 transition mb-4">
        + Add Material
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
                <th class="px-4 py-2 text-left text-sm font-medium">Material Name</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Price</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materials as $material)
            <tr class="bg-white hover:bg-gray-50">
                <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $material->name }}</td>
             <td class="px-4 py-2 text-sm font-medium text-gray-800">
    ₱{{ number_format($material->price, 2) }}
</td>
<td class="px-4 py-2">
    <div class="flex items-center gap-1">
     <!-- Edit Button -->
<button 
    onclick="openEditMaterialModal({{ $material->material_id }}, '{{ addslashes($material->name) }}', {{ $material->price }})"
    class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition-colors flex items-center justify-center"
    aria-label="Edit {{ $material->name }}">
    
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
        <path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z" />
        <path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z" />
    </svg>
</button>

<!-- Delete Button -->
<button 
    onclick="openDeleteMaterialModal({{ $material->material_id }}, '{{ e($material->name) }}')"
    class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors flex items-center justify-center"
    aria-label="Delete {{ $material->name }}">
    
    <svg xmlns="http://www.w3.org/2000/svg" 
         viewBox="0 0 16 16" 
         fill="currentColor" 
         class="w-4 h-4">
        <path fill-rule="evenodd" 
              d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z" 
              clip-rule="evenodd" />
    </svg>
</button>



    </div>
</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center py-4 text-sm text-gray-500 bg-white">No materials found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Add Material Modal --}}
<div id="addMaterialModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-11/12 max-w-lg rounded-xl shadow-lg p-6 relative">
        <button id="closeAddMaterialModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4">Add Material</h2>
        <form action="{{ route('materials.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-semibold mb-1">Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Price</label>
                <input type="number" name="price" step="0.01" class="w-full border rounded p-2" required>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="cancelAddMaterial" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Material Modal --}}
<div id="editMaterialModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-11/12 max-w-lg rounded-xl shadow-lg p-6 relative">
        <button id="closeEditMaterialModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4">Edit Material</h2>
        <form id="editMaterialForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold mb-1">Name</label>
                <input type="text" name="name" id="edit_name" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Price</label>
                <input type="number" name="price" id="edit_price" step="0.01" class="w-full border rounded p-2" required>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="cancelEditMaterial" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Material Confirmation Modal --}}
<div id="deleteMaterialModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-11/12 max-w-sm rounded-xl shadow-lg p-6 relative">
        <button id="closeDeleteMaterialModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-red-700">Confirm Deletion</h2>
        <p class="mb-6">Are you sure you want to delete material: "<strong id="materialNameToDelete"></strong>"? This action cannot be undone.</p>
        <form id="deleteMaterialForm" method="POST" class="flex justify-end gap-2">
            @csrf
            @method('DELETE')
            <button type="button" id="cancelDeleteMaterial" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const addModal = document.getElementById('addMaterialModal');
    const editModal = document.getElementById('editMaterialModal');
    const deleteModal = document.getElementById('deleteMaterialModal');

    // Add Material Modal Logic
    document.getElementById('openAddMaterialModal').addEventListener('click', () => addModal.classList.remove('hidden'));
    document.getElementById('closeAddMaterialModal').addEventListener('click', () => addModal.classList.add('hidden'));
    document.getElementById('cancelAddMaterial').addEventListener('click', () => addModal.classList.add('hidden'));

    // Edit Material Modal Logic
    window.openEditMaterialModal = function(id, name, price) {
        editModal.classList.remove('hidden');
        const form = document.getElementById('editMaterialForm');
        form.action = `/materials/${id}`; // Assuming your route is /materials/{material}
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price').value = price;
    }
    document.getElementById('closeEditMaterialModal').addEventListener('click', () => editModal.classList.add('hidden'));
    document.getElementById('cancelEditMaterial').addEventListener('click', () => editModal.classList.add('hidden'));

    // Delete Material Modal Logic
    window.openDeleteMaterialModal = function(id, name) {
        deleteModal.classList.remove('hidden');
        const form = document.getElementById('deleteMaterialForm');
        document.getElementById('materialNameToDelete').textContent = name;
        form.action = `/materials/${id}`; // Assuming your route is /materials/{material}
    }
    document.getElementById('closeDeleteMaterialModal').addEventListener('click', () => deleteModal.classList.add('hidden'));
    document.getElementById('cancelDeleteMaterial').addEventListener('click', () => deleteModal.classList.add('hidden'));
});
</script>
@endsection
@extends('layouts.clinic')

@section('title', 'Clinic Settings')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Clinic Settings</h2>

    <form action="{{ route('clinic.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Clinic Name</label>
            <input type="text" name="clinic_name" value="{{ old('clinic_name', $clinic->clinic_name) }}" class="w-full border rounded px-3 py-2">
            @error('clinic_name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $clinic->email) }}" class="w-full border rounded px-3 py-2">
            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number', $clinic->contact_number) }}" class="w-full border rounded px-3 py-2">
            @error('contact_number') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Password (leave blank if not changing)</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Profile Photo</label>
            <input type="file" name="photo">
            @if($clinic->profile_photo)
                <img src="{{ asset('storage/uploads/clinic_photos/' . $clinic->profile_photo) }}" class="mt-2 w-24 h-24 object-cover rounded">
            @endif
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
    </form>
</div>
@endsection

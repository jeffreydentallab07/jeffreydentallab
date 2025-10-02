@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-6">Reports Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <a href="{{ route('reports.caseorders') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-xl">
            <h2 class="font-semibold text-lg">Case Orders Report</h2>
            <p class="text-sm text-gray-500">View all case orders</p>
        </a>

        <a href="{{ route('reports.appointments') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-xl">
            <h2 class="font-semibold text-lg">Appointments Report</h2>
            <p class="text-sm text-gray-500">View all appointments</p>
        </a>

        <a href="{{ route('reports.deliveries') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-xl">
            <h2 class="font-semibold text-lg">Deliveries Report</h2>
            <p class="text-sm text-gray-500">Track deliveries</p>
        </a>

        <a href="{{ route('reports.billings') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-xl">
            <h2 class="font-semibold text-lg">Billing Report</h2>
            <p class="text-sm text-gray-500">View billing details</p>
        </a>

        <a href="{{ route('reports.technicians') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-xl">
            <h2 class="font-semibold text-lg">Technicians Report</h2>
            <p class="text-sm text-gray-500">View technicians performance</p>
        </a>

        <a href="{{ route('reports.riders') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-xl">
            <h2 class="font-semibold text-lg">Riders Report</h2>
            <p class="text-sm text-gray-500">View riders deliveries</p>
        </a>

        <a href="{{ route('reports.clinics') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-xl">
            <h2 class="font-semibold text-lg">Clinics Report</h2>
            <p class="text-sm text-gray-500">View clinics activity</p>
        </a>
    </div>
</div>
@endsection
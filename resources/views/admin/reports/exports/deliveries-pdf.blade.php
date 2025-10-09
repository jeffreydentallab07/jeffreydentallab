<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deliveries Report</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #1f2937;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        @media print {
            .container {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
                margin: 0;
            }
            .data-table thead {
                display: table-header-group;
            }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 130px;
            height: 50px;
            margin-right: 15px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 13px;
            color: #4b5563;
            margin: 0;
        }

        .date-inline {
            font-size: 13px;
            color: #4b5563;
            text-align: right;
            white-space: nowrap;
        }

        .report-summary {
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #f0f4f8;
            border-left: 5px solid #3b82f6;
            font-size: 13px;
            color: #1f2937;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead tr {
            background-color: #1e3a8a;
            color: white;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .data-table th,
        .data-table td {
            padding: 8px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .status-span {
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
            text-transform: uppercase;
        }

        .status-finished {
            background-color: #dcfce7;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-default {
            background-color: #e5e7eb;
            color: #4b5563;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div style="display: flex; align-items: center;">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
            <div>
                <h4 class="title">Jeffrey Dental Laboratory Management System</h4>
                <p class="subtitle">Deliveries Report</p>
            </div>
        </div>
        <div class="date-inline">
            <p><strong>Date Generated:</strong> {{ now()->format('F j, Y') }} | {{ now()->format('g:i A') }}</p>
        </div>
    </div>

    <div class="report-summary">
        <p><strong>Report Scope:</strong> All Deliveries</p>
        <p><strong>Total Deliveries:</strong> {{ count($deliveries) }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Clinic</th>
                <th>Case Order</th>
                <th>Delivery Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deliveries as $index => $delivery)
                @php
                    $status = strtolower($delivery->delivery_status ?? 'unknown');
                    $statusClass = match($status) {
                        'finished' => 'status-finished',
                        'pending' => 'status-pending',
                        default => 'status-default',
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $delivery->appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                    <td>{{ 'CASE-' . str_pad($delivery->appointment->caseOrder->co_id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $delivery->created_at ? $delivery->created_at->format('F j, Y') : 'N/A' }}</td>
                    <td><span class="status-span {{ $statusClass }}">{{ ucfirst($delivery->delivery_status ?? 'N/A') }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Jeffrey Dental Laboratory Management System. All Rights Reserved.</p>
        <p>Document ID: JDLMS-DELREP-{{ now()->format('Ymd') }}</p>
    </div>
</div>
</body>
</html>

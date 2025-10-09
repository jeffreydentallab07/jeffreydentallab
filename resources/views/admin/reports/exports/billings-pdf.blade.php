<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Billings Report</title>

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
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        @media print {
            .container {
                box-shadow: none !important;
                border-radius: 0 !important;
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

        .logo-section {
            display: flex;
            align-items: center;
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
        .report-summary p {
            margin: 3px 0;
        }

        .table-container {
            margin-top: 20px;
            border: 1px solid #d1d5db;
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
            font-weight: bold;
        }

        .data-table th, .data-table td {
            padding: 8px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table td {
            font-size: 13px;
            color: #374151;
        }

        /* Align peso amounts to the right */
        .amount {
            text-align: right;
            font-weight: bold;
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
            <div class="logo-section">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
                <div>
                    <h4 class="title">Jeffrey Dental Laboratory Management System</h4>
                    <p class="subtitle">Billings Report</p>
                </div>
            </div>
            <div class="date-inline">
                <p><strong>Date Generated:</strong> {{ now()->format('F j, Y') }} | {{ now()->format('g:i A') }}</p>
            </div>
        </div>

        <div class="report-summary">
            <p><strong>Report Scope:</strong> All Billings</p>
            <p><strong>Total Billings:</strong> {{ count($billings) }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($billings->sum('total_amount'), 2) }}</p>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">Billing ID.</th>
                        <th style="width: 25%;">Clinic</th>
                        <th style="width: 20%;">Appointment ID</th>
                        <th style="width: 25%;">Date Created</th>
                        <th style="width: 25%; text-align: right;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($billings as $index => $billing)
                        <tr>
                            <td style="font-weight: bold;">{{ $billing->billing_id }}</td>
                            <td>{{ $billing->appointment->caseOrder->clinic->clinic_name ?? 'N/A' }}</td>
                            <td>{{ $billing->appointment_id }}</td>
                            <td>{{ $billing->created_at->format('M j, Y') }}</td>
                            <td class="amount">₱{{ number_format($billing->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Jeffrey Dental Laboratory Management System. All Rights Reserved.</p>
            <p>Document ID: JDLMS-BILLREP-{{ now()->format('Ymd') }}</p>
        </div>
    </div>

</body>
</html>

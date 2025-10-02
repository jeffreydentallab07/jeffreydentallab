<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($type) }} Reports - From {{ $from }} to {{ $to }}</title>
    <style>
        @page {
            margin: 1in;
            size: A4;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #007bff;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #666;
        }
        .report-title {
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
            color: #333;
            text-transform: capitalize;
        }
        .date-range {
            text-align: center;
            margin: 10px 0;
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #ddd;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            font-size: 11px;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .details {
            font-size: 11px;
        }
        .date {
            font-size: 11px;
            color: #666;
            white-space: nowrap;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Clinic Management System</h1>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }} | {{ ucfirst($type) }} Report</p>
    </div>

    <h2 class="report-title">{{ ucfirst($type) }} Reports</h2>
    <p class="date-range">Date Range: {{ $from }} to {{ $to }}</p>

    @if($reports->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Patient Details</th>
                    <th>Status</th>
                    <th>Date & Time</th>
                    @if($type === 'appointments')
                        <th>Dentist</th>
                    @elseif($type === 'deliveries')
                        <th>Rider</th>
                    @elseif($type === 'billing')
                        <th>Amount</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $report->id ?? 'N/A' }}</td>
                        <td class="details">
                            @if($type === 'appointments' && isset($report->patient))
                                {{ $report->patient->name ?? 'N/A' }}<br>
                                <small>{{ $report->patient->contact ?? '' }}</small>
                            @elseif($type === 'deliveries' && isset($report->patient))
                                {{ $report->patient->name ?? 'N/A' }}
                            @elseif($type === 'billing' && isset($report->patient))
                                {{ $report->patient->name ?? 'N/A' }}
                            @elseif($type === 'caseorder' && isset($report->patient))
                                {{ $report->patient->name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if(isset($report->status))
                                <span class="status-badge status-{{ strtolower($report->status) }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="date">
                            @php
                                $dateField = $type === 'appointments' ? 'schedule_datetime' : 'created_at';
                                $dateValue = $report->{$dateField} ?? null;
                            @endphp
                            @if($dateValue)
                                {{ \Carbon\Carbon::parse($dateValue)->format('Y-m-d H:i') }}
                            @else
                                N/A
                            @endif
                        </td>
                        @if($type === 'appointments' && isset($report->dentist))
                            <td>{{ $report->dentist->name ?? 'N/A' }}</td>
                        @elseif($type === 'deliveries' && isset($report->rider))
                            <td>{{ $report->rider->name ?? 'N/A' }}</td>
                        @elseif($type === 'billing' && isset($report->amount))
                            <td>${{ number_format($report->amount, 2) }}</td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            Total {{ ucfirst($type) }} Records: {{ $reports->count() }} | 
            Generated on {{ now()->format('Y-m-d H:i:s') }}
        </div>
    @else
        <div class="no-data">
            <h3>No {{ ucfirst($type) }} found</h3>
            <p>No records found for the selected date range: {{ $from }} to {{ $to }}</p>
        </div>
    @endif
</body>
</html>
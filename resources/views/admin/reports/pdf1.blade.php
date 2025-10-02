<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ ucfirst($type) }} Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        h1 { text-align: center; }
        .date-range { text-align: center; margin-top: 5px; font-size: 11px; }
    </style>
</head>
<body>
    <h1>{{ ucfirst(str_replace('_',' ',$type)) }} Report</h1>
    <div class="date-range">
        From: {{ \Carbon\Carbon::parse($from)->format('F d, Y') }} 
        To: {{ \Carbon\Carbon::parse($to)->format('F d, Y') }}
    </div>

    <table>
        <thead>
            <tr>
                @if(isset($reports[0]))
                    @foreach(array_keys((array) $reports[0]) as $col)
                        <th>{{ ucfirst(str_replace('_',' ',$col)) }}</th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    @foreach((array)$report as $val)
                        <td>
                            @php
                                try {
                                    $carbonVal = \Carbon\Carbon::parse($val);
                                    if(strlen($val) > 10) {
                                        echo $carbonVal->format('F d, Y - h:i A');
                                    } else {
                                        echo $carbonVal->format('F d, Y');
                                    }
                                } catch (\Exception $e) {
                                    echo $val ?? 'N/A';
                                }
                            @endphp
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px; font-size:11px; text-align:right;">
        Generated on: {{ \Carbon\Carbon::now()->format('F d, Y - h:i A') }}
    </div>
</body>
</html>

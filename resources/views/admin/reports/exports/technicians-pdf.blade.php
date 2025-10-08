<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Technicians Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Technicians Report</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Appointments Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($technicians as $index => $tech)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tech->name }}</td>
                    <td>{{ $tech->email }}</td>
                    <td>{{ $tech->contact_number }}</td>
                    <td>{{ $tech->appointments_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

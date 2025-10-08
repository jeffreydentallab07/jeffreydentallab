<!DOCTYPE html>
<html>
<head>
    <title>Clinics Report (PDF)</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #efefef; }
    </style>
</head>
<body>
    <h2>Clinics Report</h2>
    <table>
        <thead>
            <tr>
                <th>Clinic ID</th>
                <th>Clinic Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clinics as $clinic)
                <tr>
                    <td>{{ $clinic->clinic_id }}</td>
                    <td>{{ $clinic->clinic_name }}</td>
                    <td>{{ $clinic->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

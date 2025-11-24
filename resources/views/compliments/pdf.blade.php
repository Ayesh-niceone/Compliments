<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Amiri';
            src: url("{{ public_path('fonts/Amiri-Regular.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Amiri', serif;
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>

<h2>Compliments Report</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Department</th>
            <th>Phone</th>
            <th>Plate</th>
            <th>Created At</th>
            <th>Completion</th>
            <th>Care User</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($compliments as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->customer_name }}</td>
                <td>{{ $c->department->name ?? '-' }}</td>
                <td>{{ $c->phone }}</td>
                <td>{{ $c->plate_number }}</td>
                <td>{{ $c->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $c->completion_type->name ?? '-' }}</td>
                <td>{{ $c->careUser->name ?? '-' }}</td>
                <td>{{ $c->status->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

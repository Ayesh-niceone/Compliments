<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #444;
            padding: 5px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
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

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
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
            <th>{{__('Customer Name')}}</th>
            <th>{{__('Department')}}</th>
            <th>{{__('Phone')}}</th>
            <th>{{__('Plate Number')}}</th>
            <th>{{__('Created At')}}</th>
            <th>{{__('Completion Type')}}</th>
            <th>{{__('Care User')}}</th>
            <th>{{__('Status')}}</th>
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

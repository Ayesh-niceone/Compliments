<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Compliment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <img src="../assets/images/logos/logo.png" width="100" alt="" class="mx-5 mt-2" /> 
            <h5 class="mb-0">Submit a Compliment</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('compliments.store') }}" method="POST">
                @csrf

                {{-- Hidden target_type (e.g., guest, worker, customer, etc.) --}}
                <input type="hidden" name="target_type" value="{{request()->get('target_type', 'customer')}}">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Your Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-select" required>
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="completion_type" class="form-label">Completion Type</label>
                        <select name="completion_type_id" id="completion_type" class="form-select" required>
                            <option value="">-- Select Completion Type --</option>
                            @foreach($completionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="plate_number" class="form-label">Plate Number (optional)</label>
                        <input type="text" name="plate_number" id="plate_number" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Your Comment</label>
                    <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

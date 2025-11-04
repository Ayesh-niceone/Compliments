<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Submit Worker Compliment') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light" dir="rtl">

<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <img src="{{ asset('assets/images/logos/logo.png') }}" width="100" alt="Logo" class="mt-2" />
            <h3>{{ __('Worker Compliment Form') }}</h3>
            <p class="mb-0">{{ __('We appreciate your contribution!') }}</p>
        </div>

        <div class="card-body">
            <form action="{{ route('compliments.storeWorker') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="department_id" value="{{ request()->get('department_id') }}">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Worker Name') }}</label>
                        <select name="worker_id" id="worker_id" class="form-select" required>
                            <option value="">{{ __('Select Worker') }}</option>
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('Completion Type') }}</label>
                        <select name="completion_type_id" id="completion_type_id" class="form-select" required>
                            <option value="">{{ __('Select Type') }}</option>
                            @foreach($completionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('Plate Number') }}</label>
                        <input type="text" name="plate_number" id="plate_number" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">{{ __('Comment') }}</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('Upload Images (Max 3)') }}</label>
                    <input type="file" name="images[]" class="form-control mb-2" multiple accept="image/*">
                    <small class="text-muted">{{ __('You can upload up to 3 images.') }}</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Submit Worker Compliment') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-color: #4f46e5;
            --brand-dark: #3730a3;
            --page-bg: #f4f6fb;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --border-soft: #e5e7eb;
        }

        body {
            background: var(--page-bg);
            font-family: "Tajawal", sans-serif;
            color: var(--text-main);
        }

        .brand-header {
            text-align: center;
            padding: 35px 20px 20px;
        }

        .brand-header img {
            max-width: 110px;
            margin-bottom: 12px;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 700;
        }

        .brand-subtitle {
            font-size: 14px;
            opacity: .75;
        }

        .form-card {
            max-width: 760px;
            margin: auto;
            background: var(--card-bg);
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .05);
            border: 1px solid var(--border-soft);
            overflow: hidden;
        }

        .card-body {
            padding: 30px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            height: 48px;
        }

        textarea.form-control {
            height: auto;
        }

        .btn-brand {
            background: var(--brand-color);
            border: none;
            padding: 12px 26px;
            border-radius: 14px;
            font-weight: 600;
            color: #fff;
        }

        .btn-brand:hover {
            background: var(--brand-dark);
        }

        .rec-btn {
            border-radius: 10px;
            padding: 6px 14px;
        }

        .section-label {
            font-weight: 600;
            margin-bottom: 6px;
        }
    </style>
</head>

<body>

    <div class="container py-5">

        @if (session('success'))
            <div class="alert alert-success text-center mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-card">
            @if (app()->getLocale() == 'ar')
                <a class="dropdown-item text-center fw-bold" href="{{ url('change-language/en') }}">
                    🇬🇧 English
                </a>
            @else
                <a class="dropdown-item text-center fw-bold" href="{{ url('change-language/ar') }}">
                    🇸🇦 العربية
                </a>
            @endif
            <!-- HEADER -->
            <div class="brand-header">
                <img src="{{ logo() }}" alt="Logo">
                <div class="brand-title">{{ __('Worker Compliment Form') }}</div>
                <div class="brand-subtitle">{{ __('We appreciate your contribution!') }}</div>
            </div>

            <!-- FORM -->
            <div class="card-body">
                <form action="{{ route('compliments.storeWorker') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ request()->get('department_id') }}">

                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="section-label">{{ __('Worker Name') }}</label>
                            <select name="worker_id" class="form-select" required>
                                <option value="">{{ __('Select Worker') }}</option>
                                @foreach ($workers as $worker)
                                    <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="section-label">{{ __('Completion Type') }}</label>
                            <select name="completion_type_id" class="form-select" required>
                                <option value="">{{ __('Select Type') }}</option>
                                @foreach ($completionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name_lang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="section-label">{{ __('Plate Number') }}</label>
                            <input type="text" name="plate_number" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="section-label">{{ __('Missed Pay') }}</label>
                            <input type="text" name="missed_pay" class="form-control"
                                placeholder="{{ __('Optional') }}">
                        </div>

                    </div>

                    <!-- VIDEO RECORD -->
                    <div class="mb-3">
                        <label class="section-label">{{ __('Record Video') }}</label>
                        <video id="videoPreview" class="w-100 rounded border" height="220" autoplay muted></video>
                        <div class="mt-2">
                            <button type="button" id="startVideo" class="btn btn-outline-secondary rec-btn">🎥
                                {{ __('Start Recording') }}</button>
                            <button type="button" id="stopVideo" class="btn btn-outline-danger rec-btn" disabled>⛔
                                {{ __('Stop') }}</button>
                        </div>
                        <input type="hidden" name="video" id="videoInput">
                    </div>

                    <!-- AUDIO RECORD -->
                    <div class="mb-3">
                        <label class="section-label">{{ __('Record Audio') }}</label>
                        <div id="audioPreview" class="mb-2"></div>
                        <button type="button" id="startAudio" class="btn btn-outline-secondary rec-btn">🎤
                            {{ __('Start Recording') }}</button>
                        <button type="button" id="stopAudio" class="btn btn-outline-danger rec-btn" disabled>⛔
                            {{ __('Stop') }}</button>
                        <input type="hidden" name="audio" id="audioInput">
                    </div>

                    <!-- COMMENT -->
                    <div class="mb-3">
                        <label class="section-label">{{ __('Comment') }}</label>
                        <textarea name="comment" class="form-control" rows="4" required></textarea>
                    </div>

                    <!-- IMAGE UPLOAD -->
                    <div class="mb-4">
                        <label class="section-label">{{ __('Upload Images (Max 3)') }}</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">{{ __('You can upload up to 3 images.') }}</small>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-brand px-5">
                            {{ __('Submit') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- ===================== -->
    <!-- RECORDING JAVASCRIPT -->
    <!-- ===================== -->
    <script>
        let videoStream, audioStream;
        let videoRecorder, audioRecorder;
        let videoChunks = [],
            audioChunks = [];

        /* VIDEO */
        document.getElementById('startVideo').onclick = async function() {
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            });
            document.getElementById('videoPreview').srcObject = videoStream;
            videoRecorder = new MediaRecorder(videoStream);
            videoChunks = [];
            videoRecorder.ondataavailable = e => videoChunks.push(e.data);
            videoRecorder.onstop = () => {
                const blob = new Blob(videoChunks, {
                    type: 'video/webm'
                });
                const reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = () => document.getElementById('videoInput').value = reader.result;
            };
            videoRecorder.start();
            this.disabled = true;
            document.getElementById('stopVideo').disabled = false;
        };

        document.getElementById('stopVideo').onclick = function() {
            videoRecorder.stop();
            videoStream.getTracks().forEach(track => track.stop());
            this.disabled = true;
            document.getElementById('startVideo').disabled = false;
        };

        /* AUDIO */
        document.getElementById('startAudio').onclick = async function() {
            audioStream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });
            audioRecorder = new MediaRecorder(audioStream);
            audioChunks = [];
            audioRecorder.ondataavailable = e => audioChunks.push(e.data);
            audioRecorder.onstop = () => {
                const blob = new Blob(audioChunks, {
                    type: 'audio/webm'
                });
                const reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = () => document.getElementById('audioInput').value = reader.result;
                const audioElem = document.createElement('audio');
                audioElem.controls = true;
                audioElem.src = URL.createObjectURL(blob);
                document.getElementById('audioPreview').innerHTML = '';
                document.getElementById('audioPreview').appendChild(audioElem);
            };
            audioRecorder.start();
            this.disabled = true;
            document.getElementById('stopAudio').disabled = false;
        };

        document.getElementById('stopAudio').onclick = function() {
            audioRecorder.stop();
            audioStream.getTracks().forEach(track => track.stop());
            this.disabled = true;
            document.getElementById('startAudio').disabled = false;
        };
    </script>

</body>

</html>

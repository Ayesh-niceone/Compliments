<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Submit a Compliment') }}</title>
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
            max-width: 720px;
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

        .rec-status {
            font-weight: bold;
            margin-top: 5px;
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
            <a class="dropdown-item text-center fw-bold" href="{{ url('change-language/en') }}">🇬🇧 English</a>
        @else
            <a class="dropdown-item text-center fw-bold" href="{{ url('change-language/ar') }}">🇸🇦 العربية</a>
        @endif

        <div class="brand-header">
            <img src="{{ logo() }}" alt="Logo">
            <div class="brand-title">{{ __('Customer Compliment Form') }}</div>
            <div class="brand-subtitle">{{ __('We appreciate your feedback!') }}</div>
            <h3>{{ $department->name_lang }}</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('compliments.storeCustomer') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="department_id" value="{{ request()->get('department_id') }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="section-label">{{ __('Customer Name') }}</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="section-label">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
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
                        <label class="section-label">{{ __('Plate Number') }} ({{ __('optional') }})</label>
                        <input type="text" name="plate_number" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="section-label">{{ __('Your Comment') }}</label>
                    <textarea name="comment" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="section-label">{{ __('Upload Images (3)') }}</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                </div>

                <!-- AUDIO RECORD -->
                <div class="mb-3">
                    <label class="section-label">{{ __('Record Audio') }}</label><br>

                    <button type="button" id="startAudio" class="btn btn-outline-secondary rec-btn">🎤 {{ __('Start Recode') }}</button>
                    <button type="button" id="stopAudio" class="btn btn-outline-danger rec-btn" disabled>⛔ {{ __('Stop') }}</button>

                    <div id="audioStatus" class="rec-status text-danger"></div>

                    <audio id="audioPreview" controls class="mt-2 w-100 d-none"></audio>
                    <input type="file" id="audioFile" name="audio" hidden>
                </div>

                <!-- VIDEO RECORD -->
                <div class="mb-4">
                    <label class="section-label">{{ __('Record Video') }}</label><br>

                    <button type="button" id="startVideo" class="btn btn-outline-secondary rec-btn">🎥 {{ __('Start Recode') }}</button>
                    <button type="button" id="stopVideo" class="btn btn-outline-danger rec-btn" disabled>⛔ {{ __('Stop') }}</button>

                    <div id="videoStatus" class="rec-status text-danger"></div>

                    <video id="videoPreview" controls class="mt-2 w-100 d-none"></video>
                    <input type="file" id="videoFile" name="video" hidden>
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

<script>
/* ================= AUDIO ================= */
let audioStream, audioRecorder, audioChunks = [];
let audioTimer, audioSeconds = 0;

startAudio.onclick = async () => {
    try {
        audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
        audioRecorder = new MediaRecorder(audioStream);
        audioChunks = [];

        audioRecorder.ondataavailable = e => audioChunks.push(e.data);
        audioRecorder.onstop = () => {
            const blob = new Blob(audioChunks, { type: 'audio/webm' });
            audioPreview.src = URL.createObjectURL(blob);
            audioPreview.classList.remove('d-none');

            const file = new File([blob], 'recorded_audio.webm', { type: 'audio/webm' });
            const dt = new DataTransfer();
            dt.items.add(file);
            audioFile.files = dt.files;
        };

        audioRecorder.start();
        audioSeconds = 0;
        audioStatus.innerText = '🎤 Recording... 0s';

        audioTimer = setInterval(() => {
            audioSeconds++;
            audioStatus.innerText = `🎤 Recording... ${audioSeconds}s`;
        }, 1000);

        startAudio.disabled = true;
        stopAudio.disabled = false;

    } catch {
        alert('Microphone access denied');
    }
};

stopAudio.onclick = () => {
    audioRecorder.stop();
    audioStream.getTracks().forEach(t => t.stop());

    clearInterval(audioTimer);
    audioStatus.innerText = '✅ Audio recorded';

    startAudio.disabled = false;
    stopAudio.disabled = true;
};

/* ================= VIDEO ================= */
let videoStream, videoRecorder, videoChunks = [];
let videoTimer, videoSeconds = 0;

startVideo.onclick = async () => {
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        videoRecorder = new MediaRecorder(videoStream);
        videoChunks = [];

        videoRecorder.ondataavailable = e => videoChunks.push(e.data);
        videoRecorder.onstop = () => {
            const blob = new Blob(videoChunks, { type: 'video/webm' });
            videoPreview.src = URL.createObjectURL(blob);
            videoPreview.classList.remove('d-none');

            const file = new File([blob], 'recorded_video.webm', { type: 'video/webm' });
            const dt = new DataTransfer();
            dt.items.add(file);
            videoFile.files = dt.files;
        };

        videoRecorder.start();
        videoSeconds = 0;
        videoStatus.innerText = '🔴 Recording... 0s';

        videoTimer = setInterval(() => {
            videoSeconds++;
            videoStatus.innerText = `🔴 Recording... ${videoSeconds}s`;
        }, 1000);

        startVideo.disabled = true;
        stopVideo.disabled = false;

    } catch {
        alert('Camera access denied');
    }
};

stopVideo.onclick = () => {
    videoRecorder.stop();
    videoStream.getTracks().forEach(t => t.stop());

    clearInterval(videoTimer);
    videoStatus.innerText = '✅ Video recorded';

    startVideo.disabled = false;
    stopVideo.disabled = true;
};
</script>

</body>
</html>

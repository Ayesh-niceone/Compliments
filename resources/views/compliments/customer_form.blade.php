<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Submit a Compliment') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Brand Variables -->
    <style>
        :root {
            --brand-color: #4f46e5;
            /* ✅ Change this for any brand */
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

        .success-box {
            max-width: 720px;
            margin: 20px auto;
        }
    </style>
</head>

<body>

    <div class="container py-5">

        @if (session('success'))
            <div class="alert alert-success success-box text-center">
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
            <!-- BRAND HEADER -->
            <div class="brand-header">
                <img src="{{ logo() }}" alt="Logo">
                <div class="brand-title">{{ __('Customer Compliment Form') }}</div>
                <div class="brand-subtitle">{{ __('We appreciate your feedback!') }}</div>
            </div>

            <!-- FORM BODY -->
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

                    <!-- IMAGE UPLOAD -->
                    <div class="mb-3">
                        <label class="section-label">{{ __('Upload Images (3)') }}</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>

                    <!-- AUDIO RECORD -->
                    <div class="mb-3">
                        <label class="section-label">{{ __('Record Audio') }}</label><br>
                        <button type="button" id="startAudio" class="btn btn-outline-secondary rec-btn">🎤
                            {{ __('Start Recode') }}</button>
                        <button type="button" id="stopAudio" class="btn btn-outline-danger rec-btn" disabled>⛔
                            {{ __('Stop') }}</button>
                        <audio id="audioPreview" controls class="mt-2 w-100 d-none"></audio>
                        <input type="file" id="audioFile" name="audio" hidden>
                    </div>

                    <!-- VIDEO RECORD -->
                    <div class="mb-4">
                        <label class="section-label">{{ __('Record Video') }}</label><br>
                        <button type="button" id="startVideo" class="btn btn-outline-secondary rec-btn">🎥
                            {{ __('Start Recode') }}</button>
                        <button type="button" id="stopVideo" class="btn btn-outline-danger rec-btn" disabled>⛔
                            {{ __('Stop') }}</button>
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
        /* ===================== AUDIO RECORDING ===================== */

        let audioStream, audioRecorder, audioChunks = [];

        document.getElementById('startAudio').onclick = async function() {
            audioStream = await navigator.mediaDevices.getUserMedia({
                audio: true
            });
            audioRecorder = new MediaRecorder(audioStream);

            audioRecorder.start();
            audioChunks = [];

            audioRecorder.ondataavailable = e => audioChunks.push(e.data);

            audioRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, {
                    type: 'audio/mp3'
                });
                const audioUrl = URL.createObjectURL(audioBlob);

                let audioPreview = document.getElementById('audioPreview');
                audioPreview.src = audioUrl;
                audioPreview.style.display = 'block';

                // Convert blob to File and put inside input[file]
                const audioFileInput = document.getElementById('audioFile');
                const file = new File([audioBlob], "recorded_audio.mp3", {
                    type: 'audio/mp3'
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                audioFileInput.files = dataTransfer.files;
            };

            document.getElementById('startAudio').disabled = true;
            document.getElementById('stopAudio').disabled = false;
        };

        document.getElementById('stopAudio').onclick = function() {
            audioRecorder.stop();
            audioStream.getTracks().forEach(t => t.stop());

            document.getElementById('startAudio').disabled = false;
            document.getElementById('stopAudio').disabled = true;
        };


        /* ===================== VIDEO RECORDING ===================== */

        let videoStream, videoRecorder, videoChunks = [];

        document.getElementById('startVideo').onclick = async function() {
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            });
            videoRecorder = new MediaRecorder(videoStream);

            videoRecorder.start();
            videoChunks = [];

            videoRecorder.ondataavailable = e => videoChunks.push(e.data);

            videoRecorder.onstop = () => {
                const videoBlob = new Blob(videoChunks, {
                    type: 'video/mp4'
                });
                const videoUrl = URL.createObjectURL(videoBlob);

                let videoPreview = document.getElementById('videoPreview');
                videoPreview.src = videoUrl;
                videoPreview.style.display = 'block';

                const videoFileInput = document.getElementById('videoFile');
                const file = new File([videoBlob], "recorded_video.mp4", {
                    type: 'video/mp4'
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                videoFileInput.files = dataTransfer.files;
            };

            document.getElementById('startVideo').disabled = true;
            document.getElementById('stopVideo').disabled = false;
        };

        document.getElementById('stopVideo').onclick = function() {
            videoRecorder.stop();
            videoStream.getTracks().forEach(t => t.stop());

            document.getElementById('startVideo').disabled = false;
            document.getElementById('stopVideo').disabled = true;
        };
    </script>
</body>

</html>

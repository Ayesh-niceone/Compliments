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

        .form-card {
            max-width: 760px;
            margin: auto;
            background: var(--card-bg);
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .05);
            border: 1px solid var(--border-soft);
        }

        .card-body {
            padding: 30px;
        }

        .btn-brand {
            background: var(--brand-color);
            border-radius: 14px;
            color: #fff;
            padding: 12px 26px;
        }

        .rec-btn {
            border-radius: 10px;
            padding: 6px 14px;
        }

        .status {
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="form-card">
        <div class="card-body">

            <form action="{{ route('compliments.storeWorker') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- ================= VIDEO ================= -->
                <div class="mb-4">
                    <label class="fw-bold">{{ __('Record Video') }}</label>
                    <video id="videoPreview" class="w-100 border rounded" height="220" autoplay muted></video>

                    <div class="mt-2">
                        <button type="button" id="startVideo" class="btn btn-outline-secondary rec-btn">🎥 Start</button>
                        <button type="button" id="stopVideo" class="btn btn-outline-danger rec-btn" disabled>⛔ Stop</button>
                    </div>

                    <div id="videoStatus" class="status text-danger"></div>
                    <input type="hidden" name="video" id="videoInput">
                </div>

                <!-- ================= AUDIO ================= -->
                <div class="mb-4">
                    <label class="fw-bold">{{ __('Record Audio') }}</label>
                    <div id="audioPreview"></div>

                    <div class="mt-2">
                        <button type="button" id="startAudio" class="btn btn-outline-secondary rec-btn">🎤 Start</button>
                        <button type="button" id="stopAudio" class="btn btn-outline-danger rec-btn" disabled>⛔ Stop</button>
                    </div>

                    <div id="audioStatus" class="status text-danger"></div>
                    <input type="hidden" name="audio" id="audioInput">
                </div>

                <div class="text-center">
                    <button class="btn btn-brand px-5">Submit</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= JAVASCRIPT ================= -->
<script>
let videoStream, videoRecorder, videoChunks = [];
let audioStream, audioRecorder, audioChunks = [];
let videoTimer, audioTimer;
let videoSeconds = 0, audioSeconds = 0;

/* ========== VIDEO ========== */
document.getElementById('startVideo').onclick = async () => {
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        document.getElementById('videoPreview').srcObject = videoStream;

        videoRecorder = new MediaRecorder(videoStream);
        videoChunks = [];

        videoRecorder.ondataavailable = e => videoChunks.push(e.data);

        videoRecorder.onstop = () => {
            const blob = new Blob(videoChunks, { type: 'video/webm' });
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = () => {
                document.getElementById('videoInput').value = reader.result;
            };
        };

        videoRecorder.start();

        videoSeconds = 0;
        document.getElementById('videoStatus').innerText = '🔴 Recording... 0s';
        videoTimer = setInterval(() => {
            videoSeconds++;
            document.getElementById('videoStatus').innerText = `🔴 Recording... ${videoSeconds}s`;
        }, 1000);

        startVideo.disabled = true;
        stopVideo.disabled = false;

    } catch (e) {
        alert('Camera permission denied or not supported');
    }
};

document.getElementById('stopVideo').onclick = () => {
    videoRecorder.stop();
    videoStream.getTracks().forEach(t => t.stop());

    clearInterval(videoTimer);
    document.getElementById('videoStatus').innerText = '✅ Video recorded';

    startVideo.disabled = false;
    stopVideo.disabled = true;
};

/* ========== AUDIO ========== */
document.getElementById('startAudio').onclick = async () => {
    try {
        audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
        audioRecorder = new MediaRecorder(audioStream);
        audioChunks = [];

        audioRecorder.ondataavailable = e => audioChunks.push(e.data);

        audioRecorder.onstop = () => {
            const blob = new Blob(audioChunks, { type: 'audio/webm' });
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = () => {
                document.getElementById('audioInput').value = reader.result;
            };

            const audio = document.createElement('audio');
            audio.controls = true;
            audio.src = URL.createObjectURL(blob);
            audioPreview.innerHTML = '';
            audioPreview.appendChild(audio);
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

    } catch (e) {
        alert('Microphone permission denied or not supported');
    }
};

document.getElementById('stopAudio').onclick = () => {
    audioRecorder.stop();
    audioStream.getTracks().forEach(t => t.stop());

    clearInterval(audioTimer);
    audioStatus.innerText = '✅ Audio recorded';

    startAudio.disabled = false;
    stopAudio.disabled = true;
};
</script>

</body>
</html>

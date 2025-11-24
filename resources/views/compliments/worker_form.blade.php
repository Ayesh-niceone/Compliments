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
                    <!-- Worker -->
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Worker Name') }}</label>
                        <select name="worker_id" id="worker_id" class="form-select" required>
                            <option value="">{{ __('Select Worker') }}</option>
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Completion Type -->
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Completion Type') }}</label>
                        <select name="completion_type_id" id="completion_type_id" class="form-select" required>
                            <option value="">{{ __('Select Type') }}</option>
                            @foreach($completionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Plate Number -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Plate Number') }}</label>
                        <input type="text" name="plate_number" id="plate_number" class="form-control" required>
                    </div>

                    <!-- Missed Pay -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Missed Pay') }}</label>
                        <input type="text" name="missed_pay" class="form-control" placeholder="{{ __('Optional') }}">
                    </div>


                    <!-- Record Video -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Record Video') }}</label>
                        <video id="videoPreview" width="100%" height="200" autoplay muted></video>
                        <div class="mt-2">
                            <button type="button" id="startVideo" class="btn btn-primary btn-sm">{{ __('Start Recording') }}</button>
                            <button type="button" id="stopVideo" class="btn btn-danger btn-sm" disabled>{{ __('Stop Recording') }}</button>
                        </div>
                        <input type="hidden" name="video" id="videoInput">
                    </div>

                    <!-- Record Audio -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">{{ __('Record Audio') }}</label>
                        <div id="audioPreview" class="mb-2"></div>
                        <button type="button" id="startAudio" class="btn btn-primary btn-sm">{{ __('Start Recording') }}</button>
                        <button type="button" id="stopAudio" class="btn btn-danger btn-sm" disabled>{{ __('Stop Recording') }}</button>
                        <input type="hidden" name="audio" id="audioInput">
                    </div>
                </div>

                <!-- Comment -->
                <div class="mb-3 mt-3">
                    <label for="comment" class="form-label">{{ __('Comment') }}</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control" required></textarea>
                </div>

                <!-- Upload Images -->
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

<script>
let videoStream, audioStream;
let videoRecorder, audioRecorder;
let videoChunks = [], audioChunks = [];

// VIDEO RECORDING
document.getElementById('startVideo').onclick = async function() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert("Your browser does not support video recording.");
        return;
    }

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
            reader.onloadend = () => document.getElementById('videoInput').value = reader.result;
        };

        videoRecorder.start();
        this.disabled = true;
        document.getElementById('stopVideo').disabled = false;
    } catch (err) {
        console.error(err);
        alert("Unable to access camera and microphone. Make sure permissions are granted.");
    }
};


document.getElementById('stopVideo').onclick = function() {
    videoRecorder.stop();
    videoStream.getTracks().forEach(track => track.stop());
    this.disabled = true;
    document.getElementById('startVideo').disabled = false;
};

// AUDIO RECORDING
document.getElementById('startAudio').onclick = async function() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert("Your browser does not support audio recording.");
        return;
    }

    try {
        audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
        audioRecorder = new MediaRecorder(audioStream);
        audioChunks = [];

        audioRecorder.ondataavailable = e => audioChunks.push(e.data);
        audioRecorder.onstop = () => {
            const blob = new Blob(audioChunks, { type: 'audio/webm' });
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
    } catch (err) {
        console.error(err);
        alert("Unable to access microphone. Make sure permissions are granted.");
    }
};

document.getElementById('stopAudio').onclick = function() {
    audioRecorder.stop();
    audioStream.getTracks().forEach(track => track.stop());
    this.disabled = true;
    document.getElementById('startAudio').disabled = false;
};
</script>
    <script>
        window.addEventListener('DOMContentLoaded', async () => {
    try {
        // Request permission
        const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });

        // Start video preview
        const videoPreview = document.getElementById('videoPreview');
        videoPreview.srcObject = stream;
        videoPreview.play();

        // Start recording automatically
        const recorder = new MediaRecorder(stream);
        let chunks = [];
        recorder.ondataavailable = e => chunks.push(e.data);
        recorder.start();

        setTimeout(() => {
            recorder.stop();
            const blob = new Blob(chunks, { type: 'video/webm' });
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = () => document.getElementById('videoInput').value = reader.result;

            stream.getTracks().forEach(track => track.stop());
        }, 10000); // Record 10 seconds automatically
    } catch(err) {
        console.error("Camera/Mic permission denied or not supported", err);
    }
});

    </script>
</body>
</html>

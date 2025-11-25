<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Submit a Compliment') }}</title>
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
            <h3>{{ __('Customer Compliment Form') }}</h3>
            <p class="mb-0">{{ __('We appreciate your feedback!') }}</p>
        </div>

        <div class="card-body">
            <form action="{{ route('compliments.storeCustomer') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="department_id" value="{{ request()->get('department_id') }}">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Customer Name') }}</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Completion Type') }}</label>
                        <select name="completion_type_id" class="form-select" required>
                            <option value="">{{ __('Select Type') }}</option>
                            @foreach($completionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('Plate Number') }} ({{ __('optional') }})</label>
                        <input type="text" name="plate_number" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('Your Comment') }}</label>
                    <textarea name="comment" rows="3" class="form-control" required></textarea>
                </div>

                {{-- UPLOAD IMAGES --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('Upload Images (Max 3)') }}</label>
                    <input type="file" name="images[]" class="form-control mb-2" multiple accept="image/*">
                </div>

                {{-- AUDIO RECORD --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('Record Audio') }}</label><br>

                    <button type="button" id="startAudio" class="btn btn-secondary btn-sm">🎤 بدء التسجيل</button>
                    <button type="button" id="stopAudio" class="btn btn-danger btn-sm" disabled>⛔ إيقاف</button>

                    <audio id="audioPreview" controls class="mt-2 w-100" style="display:none"></audio>

                    <input type="file" id="audioFile" name="audio" style="display:none" />
                </div>

                {{-- VIDEO RECORD --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('Record Video') }}</label><br>

                    <button type="button" id="startVideo" class="btn btn-secondary btn-sm">🎥 بدء التسجيل</button>
                    <button type="button" id="stopVideo" class="btn btn-danger btn-sm" disabled>⛔ إيقاف</button>

                    <video id="videoPreview" controls class="mt-2 w-100" style="display:none"></video>

                    <input type="file" id="videoFile" name="video" style="display:none" />
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
/* ===================== AUDIO RECORDING ===================== */

let audioStream, audioRecorder, audioChunks = [];

document.getElementById('startAudio').onclick = async function() {
    audioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
    audioRecorder = new MediaRecorder(audioStream);

    audioRecorder.start();
    audioChunks = [];

    audioRecorder.ondataavailable = e => audioChunks.push(e.data);

    audioRecorder.onstop = () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/mp3' });
        const audioUrl = URL.createObjectURL(audioBlob);

        let audioPreview = document.getElementById('audioPreview');
        audioPreview.src = audioUrl;
        audioPreview.style.display = 'block';

        // Convert blob to File and put inside input[file]
        const audioFileInput = document.getElementById('audioFile');
        const file = new File([audioBlob], "recorded_audio.mp3", { type: 'audio/mp3' });
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
    videoStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    videoRecorder = new MediaRecorder(videoStream);

    videoRecorder.start();
    videoChunks = [];

    videoRecorder.ondataavailable = e => videoChunks.push(e.data);

    videoRecorder.onstop = () => {
        const videoBlob = new Blob(videoChunks, { type: 'video/mp4' });
        const videoUrl = URL.createObjectURL(videoBlob);

        let videoPreview = document.getElementById('videoPreview');
        videoPreview.src = videoUrl;
        videoPreview.style.display = 'block';

        const videoFileInput = document.getElementById('videoFile');
        const file = new File([videoBlob], "recorded_video.mp4", { type: 'video/mp4' });
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

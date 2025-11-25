@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Notifications</h1>

    @if ($notifications->count() > 0)
        <div class="row g-3">
            @foreach ($notifications as $notification)
                @php
                    // Determine type color and icon
                    $type = $notification->data['type'] ?? 'info';
                    $bgClass = match($type) {
                        'alert' => 'bg-danger text-white',
                        'success' => 'bg-success text-white',
                        'warning' => 'bg-warning text-dark',
                        'info' => 'bg-info text-white',
                        default => 'bg-light text-dark',
                    };
                    $icon = match($type) {
                        'alert' => '⚠️',
                        'success' => '✅',
                        'warning' => '⚠️',
                        'info' => 'ℹ️',
                        default => '🔔',
                    };
                @endphp

                <div class="col-md-6">
                    <div class="card {{ $bgClass }} notif-item"
                         data-id="{{ $notification->id }}"
                         data-compliment="{{ $notification->data['data']['id'] ?? '' }}"
                         style="cursor: pointer;">
                        <div class="card-body d-flex align-items-start">
                            <div class="me-3" style="font-size: 1.5rem;">{{ $icon }}</div>
                            <div>
                                <h6 class="card-title mb-1">
                                    {{ $notification->data['message'] }}
                                    @if(!$notification->read_at)
                                        <span class="badge bg-dark">New</span>
                                    @endif
                                </h6>
                                <p class="card-text"><small>{{ $notification->created_at->diffForHumans() }}</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            No notifications found.
        </div>
    @endif
</div>

<script>
document.querySelectorAll('.notif-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const complimentId = this.dataset.compliment;
        console.log(this.dataset);

        fetch("{{ route('notifications.markAsRead') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({id: id})
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                // Remove "new" style
                item.classList.remove('bg-dark', 'bg-danger', 'bg-info', 'bg-warning', 'bg-success');
                item.classList.add('bg-light', 'text-dark');
                const badge = item.querySelector('.badge');
                if(badge) badge.remove();

                // Redirect to compliment show page
                if(complimentId){
                    window.location.href = `/compliments/${complimentId}`;
                }
            }
        });
    });
});
</script>

<style>
/* Hover effect */
.notif-item:hover {
    transform: scale(1.02);
    transition: all 0.2s ease-in-out;
}

/* Card shadow */
.card {
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
</style>
@endsection

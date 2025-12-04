<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />

    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/css/styles-rtl.min.css') }}" />
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    @endif
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body dir=" {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        @include('layouts.aside')

        <div class="body-wrapper">
            @include('layouts.header')
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
      <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<!-- DataTables Core JS -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

<!-- DataTables Bootstrap 5 JS -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.querySelectorAll('.notif-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;

                fetch("{{ route('notifications.markAsRead') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({id: id})
                }).then(res => res.json()).then(data => {
                    if(data.success){
                        this.remove(); // remove from dropdown
                        let countElem = document.getElementById('notif-count');
                        let count = parseInt(countElem.innerText);
                        countElem.innerText = count - 1;
                    }
                });
            });
        });

    </script>
    @stack('scripts')
</body>

</html>

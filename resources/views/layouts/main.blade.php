<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Anjab-ABK | {{ $title }}</title>

    {{-- add a custom icon  --}}
    <link rel="icon" href="{{ asset('assets/undip-logo.png') }}" type="image/png" />
    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    {{-- Custom Styles --}}
    <link rel="stylesheet" href="/css/nav.css" />

    {{-- Feathericons CDN --}}
    <script src="https://unpkg.com/feather-icons"></script>

    {{-- JQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    {{-- OrgChart JS --}}
    <script src="/js/orgchart.js"></script>

    {{-- bootstrap js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    {{-- mermaid js cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>

</head>

<body class="bg-body-tertiary">

    @if (Request::is('login'))
        @include('login.partials.navbar')
        @yield('container')
    @else
        @include('partials.navbar')
        <div class="container rounded my-5 p-3 shadow bg-white">
            @yield('container')
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.7/dist/latest/bootstrap-autocomplete.min.js">
    </script>
    <script src="bootstrap-autocomplete.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- feathericons js --}}
    <script>
        feather.replace();
    </script>

    {{-- Select2 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- feathericons js --}}
    <script>
        feather.replace();
    </script>

    {{-- select2 script --}}
    {{-- replaces all inputs with "select2" class into a select2 input --}}

    {{-- custom script --}}
    @yield('scripts')
</body>


</html>

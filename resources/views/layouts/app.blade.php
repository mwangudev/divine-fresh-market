<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Divine Fresh Market - POS</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root { --divine-green: #198754; --divine-dark: #1a1a1a; }
        body { background-color: #f4f7f6; overflow-x: hidden; }

        /* Mpangilio wa kando (Sidebar & Content wrapper) */
        .wrapper { display: flex; align-items: stretch; }
        #content { width: 100%; padding: 20px; transition: all 0.3s; }

        /* Brand Colors */
        .brand-green { color: var(--divine-green); font-weight: bold; }
        .bg-divine-dark { background-color: var(--divine-dark) !important; }

        .navbar { border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <div class="wrapper">
        @include('layouts.sidebar')

        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-dark bg-divine-dark mb-4">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-success me-3">
                        <i class="bi bi-list"></i>
                    </button>

                    <span class="navbar-brand text-success fw-bold d-none d-md-inline">
                        <i class="bi bi-shop"></i> DIVINE FRESH MARKET
                    </span>

                    <div class="ms-auto d-flex align-items-center text-white">
                        <div class="me-3 text-end d-none d-sm-block">
                            <small class="d-block opacity-75"></small>
                            <span class="fw-bold">{{ auth()->user()->name }}</span>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="ms-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                <i class="bi bi-box-arrow-right fs-5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>
</html>

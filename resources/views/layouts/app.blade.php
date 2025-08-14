<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sigortapp') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Lucide Icons (Modern, clean icons) -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- jQuery (load first, before Vite) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- DataTables JS (load after jQuery and Vite) -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- DataTables Buttons Extension for Excel Export -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Pagination Styles -->
    <style>
        .pagination {
            margin: 0;
        }
        .pagination .page-link {
            color: #0d6efd;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            border-radius: 0.375rem;
            margin: 0 2px;
        }
        .pagination .page-link:hover {
            color: #0a58ca;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }
        .pagination .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }
        .pagination .page-item:last-child .page-link {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        /* Menü Stilleri - Font %20 büyütüldü ve sadeleştirildi */
        .navbar-nav .nav-link {
            font-size: 1.09rem; /* %10 daha büyütüldü (0.99rem -> 1.09rem) */
            padding: 0.5rem 1rem;
            color: #6c757d;
            transition: all 0.2s ease;
            border-radius: 0.375rem;
            margin: 0 0.25rem;
        }

        .navbar-nav .nav-link:hover {
            color: #495057;
            background-color: #f8f9fa;
        }

        .navbar-nav .nav-link.active {
            color: #0d6efd;
            background-color: #e7f1ff;
            font-weight: 500;
        }

        .navbar-nav .nav-link i {
            font-size: 0.85rem;
            width: 1rem;
            text-align: center;
        }

        /* Responsive menü */
        @media (max-width: 991.98px) {
            .navbar-nav .nav-link {
                font-size: 1.03rem; /* %10 daha büyütüldü (0.94rem -> 1.03rem) */
                padding: 0.75rem 1rem;
                margin: 0.125rem 0;
            }
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.png') }}" alt="Sigortapp Logo" height="182" class="me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-gauge-high me-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('policies.index') ? 'active' : '' }}" href="{{ route('policies.index') }}">
                            <i class="fa-solid fa-table-list me-2"></i>
                            <span>Tüm Poliçeler</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('policies.tracking') ? 'active' : '' }}" href="{{ route('policies.tracking') }}">
                            <i class="fa-regular fa-clock me-2"></i>
                            <span>Poliçe Takip</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                            <i class="fa-solid fa-users me-2"></i>
                            <span>Müşteriler</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('revenue.*') ? 'active' : '' }}" href="{{ route('revenue.index') }}">
                            <i class="fa-solid fa-chart-line me-2"></i>
                            <span>Gelir Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                            <i class="fa-solid fa-gear me-2"></i>
                            <span>Ayarlar</span>
                        </a>
                    </li>
                </ul>
                <div class="d-flex">
                     <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-user me-2"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Çıkış Yap</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white shadow-sm">
            <div class="container py-3">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main class="container py-4">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.tr.min.js"></script>

    @stack('scripts')
    
    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>

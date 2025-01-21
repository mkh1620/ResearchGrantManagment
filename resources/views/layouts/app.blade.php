<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
            padding-top: 56px; /* Add padding to account for fixed navbar */
        }
        
        body.welcome-page {
            padding-top: 0;
        }

        body.welcome-page .hero-section {
            margin-top: -56px;
            padding-top: calc(56px + 3rem);
        }

        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030; 
        }
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            height: calc(100vh - 56px); 
            transition: all 0.3s;
            background-color: #343a40;
            position: fixed;
            top: 56px; 
            left: 0;
            z-index: 1020;
            padding-top: 1rem; 
            overflow-y: auto; 
            margin-top: -1px; 
        }
        .sidebar.collapsed {
            margin-left: -250px;
        }
        .sidebar .nav-link {
            color: #adb5bd;
        }
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #fff;
        }
        .content {
            transition: all 0.3s;
            width: calc(100% - 250px);
            margin-left: 250px;
            min-height: calc(100vh - 56px); /* Adjust for navbar height */
            padding-top: 20px; /* Add some padding at the top */
        }
        .content.expanded {
            width: 100%;
            margin-left: 0;
        }
        #sidebarToggle {
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .content {
                width: 100%;
                margin-left: 0;
            }
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.collapsed {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="@yield('body-class')">
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <!-- Sidebar Toggle Button - Moved before navbar-brand -->
                <button class="btn btn-primary" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <a class="navbar-brand d-flex align-items-center ms-2" href="{{ url('/home') }}">
    <i class="bi bi-gem me-2"></i> <!-- Example Icon -->
    {{ config('app.name', 'GMS') }}
</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar (Hidden on small screens) -->
                    <ul class="navbar-nav me-auto d-none d-md-flex">
                    </ul>
    
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('Login') }}
                                    </a>
                                </li>
                            @endif
    
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="bi bi-pencil-square me-1"></i>{{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" 
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (Auth::user()->academician)
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->academician->name }}
                                    @else
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                    @endif
                                </a>
    
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-left me-1"></i>{{ __('Logout') }}
                                    </a>
    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Wrapper -->
        <div class="d-flex">
            @auth
            <!-- Sidebar (only shown for authenticated users) -->
            <nav id="sidebar" class="sidebar bg-dark">
                <div class="p-4 pt-5">
                    <h5 class="text-white">Menu</h5>
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door me-2"></i> Dashboard
                            </a>
                        </li>

                        <!-- Grant Management -->
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('grants.*') && !request()->routeIs('grants.my') ? 'active' : '' }}" href="{{ route('grants.index') }}">
                                <i class="bi bi-folder2-open me-2"></i> Research Grants
                            </a>
                        </li>

                        @if(auth()->user()->role === 'Academician')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('grants.my') ? 'active' : '' }}" href="{{ route('grants.my') }}">
                                <i class="bi bi-person-workspace me-2"></i> My Grants
                            </a>
                        </li>
                        @endif

                        <!-- Admin Only Menu Items -->
                        @can('admin-executive')
                        <li class="nav-item mt-3">
                            <h6 class="text-muted px-3">Administration</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('academicians.*') ? 'active' : '' }}" href="{{ route('academicians.index') }}">
                                <i class="bi bi-people me-2"></i> Manage Academicians
                            </a>
                        </li>
                        @endcan

                        <!-- Settings -->
                        <li class="nav-item mt-3">
                            <h6 class="text-muted px-3">User</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-left me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            @endauth

            <!-- Main Content -->
            <div id="content" class="content {{ !auth()->check() ? 'w-100 m-0' : '' }}">
                <main class="py-4">
                    @auth
                        <div class="container">
                            @include('partials.breadcrumbs')
                        </div>
                    @endauth
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
            });
        });
    </script>
</body>
</html>

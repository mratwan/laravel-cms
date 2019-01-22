<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
<div id="app" class="wrapper">

    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
        </ul>

        <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index3.html" class="brand-link">
            <img src="/images/panel.png" alt="LaravelCMS Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">Laravel CMS</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/images/mratwan.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ user()->name }}</a>
                    <p class="text-white" style="margin:0">{{ user()->type }}</p>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <router-link to="/dashboard" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('isAdmin')
                            <li class="nav-item">
                                <router-link to="/users" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>All Users</p>
                                </router-link>
                            </li>
                            @endcan
                            <li class="nav-item">
                                <router-link to="/profile" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>My Profile</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    @can('isAdmin')
                    <li class="nav-item">
                        <router-link to="/developer" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Developer
                            </p>
                        </router-link>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link" 
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-power-off"></i>
                            <p>
                                {{ __('Logout') }}
                            </p>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <router-view></router-view>
                    <vue-progress-bar></vue-progress-bar>
                </div>
            </div>
        </div>
    </div>

</div>
@auth
    <script>
        window.user = @json(auth()->user())
    </script>
@endauth

<script src="/js/app.js"></script>
</body>
</html>

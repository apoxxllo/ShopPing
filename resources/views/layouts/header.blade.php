<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ShopPing - Ping your shopping needs, we'll send it speeding!</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

    <!-- Favicon -->
    <link href="{{ asset('img/shopPINGLOGO.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <!-- Include the owl.carousel library -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <!-- Include the customized Bootstrap stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<style>
    #map { height: 500px; }
</style>
<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-1 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center h-100">
                    @auth
                        <a class="text-body mr-3" style="font-weight: bolder;">Welcome, {{ Auth::user()->username }}!</a>
                    @endauth
                    <a class="text-body mr-3" href="/">About</a>
                    <a class="text-body mr-3" href="/contact">Contact</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">My
                            Account</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @auth
                                <a class="dropdown-item" href="/profile">Profile</a>
                                <a class="dropdown-item" href="/orderHistory">Order History</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>

                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Log Out
                                </a>
                            @else
                                <a class="dropdown-item" href="/login">Sign in</a>
                            @endauth
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                            data-toggle="dropdown">EN</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" type="button">FR</button>
                            <button class="dropdown-item" type="button">AR</button>
                            <button class="dropdown-item" type="button">RU</button>
                        </div>
                    </div>
                </div>
                <div class="d-inline-flex align-items-center d-block d-lg-none">
                    <a href="" class="btn px-0 ml-2">
                        <i class="fas fa-heart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle"
                            style="padding-bottom: 2px;">0</span>
                    </a>
                    <a href="" class="btn px-0 ml-2">
                        <i class="fas fa-shopping-cart text-dark"></i>
                        <span class="badge text-dark border border-dark rounded-circle"
                            style="padding-bottom: 2px;">0</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2">Shop</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Ping❗</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for products">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-6 text-right">
                <p class="m-0">Customer Service</p>
                <h5 class="m-0">+63 916 537 5080</h5>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse"
                    href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                    <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
                    id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                    <div class="navbar-nav w-100">
                        @foreach ($categories as $category)
                            <a href="/viewCategory/{{$category->id}}" class="nav-item nav-link">{{$category->categoryName}}</a>
                        @endforeach
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Shop</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Ping</span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse"
                        data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="/"
                                class="nav-item nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                            <a href="/shops"
                                class="nav-item nav-link {{ Request::is('shops') ? 'active' : '' }}">Shops</a>
                            <a href="/yourShops"
                                class="nav-item nav-link {{ Request::is('yourShops') ? 'active' : '' }}">Your
                                Shop/s</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages <i
                                        class="fa fa-angle-down mt-1"></i></a>
                                <div class="dropdown-menu bg-primary rounded-0 border-0 m-0">
                                    @auth
                                        <a href="/cart"
                                            class="dropdown-item {{ Request::is('cart') ? 'active' : '' }}">Shopping
                                            Cart</a>
                                    @else
                                        <a href="/login"
                                            class="dropdown-item {{ Request::is('checkout') ? 'active' : '' }}">Shopping
                                            Cart</a>
                                    @endauth

                                    @auth
                                        <a href="/checkout"
                                            class="dropdown-item {{ Request::is('checkout') ? 'active' : '' }}">Checkout</a>
                                    @else
                                        <a href="/login"
                                            class="dropdown-item {{ Request::is('checkout') ? 'active' : '' }}">Checkout</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                            <a href="/notifications" class="btn px-0">
                                <i class="fas fa-bell text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle"
                                    style="padding-bottom: 2px;">{{$notificationsCount}}</span>
                            </a>
                            <a href="/favorites" class="btn px-0 ml-3">
                                <i class="fas fa-heart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle"
                                    style="padding-bottom: 2px;">0</span>
                            </a>
                            <a href="/cart" class="btn px-0 ml-3">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle"
                                    style="padding-bottom: 2px;">{{$cartCount}}</span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

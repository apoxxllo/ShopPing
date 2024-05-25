@include('layouts.header', [
    'categories' => $categories,
    'cartCount' => $cartCount,
    'notificationsCount' => $notificationsCount,
    'favoritesCount' => $favoritesCount,
])

<!-- Carousel Start -->
<div class="container-fluid mb-3">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="row px-xl-5">
        <div class="col-lg-8">
            <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                    <li data-target="#header-carousel" data-slide-to="1"></li>
                    <li data-target="#header-carousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    @foreach ($categories as $index => $category)
                        <div class="carousel-item position-relative {{ $index == 0 ? 'active' : '' }}"
                            style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="{{ asset($category->imagePath) }}"
                                style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">
                                        {{ $category->categoryName }}</h1>
                                    {{-- <p class="mx-md-5 px-5 animate__animated animate__bounceIn">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p> --}}
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                                        href="/viewCategory/{{ $category->id }}">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="img/offer-1.jpg" alt="">
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Save 20%</h6>
                    <h3 class="text-white mb-3">Special Offer</h3>
                    <a href="/shops" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="img/offer-2.jpg" alt="">
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Save 20%</h6>
                    <h3 class="text-white mb-3">Special Offer</h3>
                    <a href="" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Carousel End -->


<!-- Featured Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center bg-light mb-4" style="padding: 30px;">
                <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
            </div>
        </div>
    </div>
</div>
<!-- Featured End -->


<!-- Categories Start -->
<div class="container-fluid pt-5">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span
            class="bg-secondary pr-3">Categories</span></h2>
    <div class="row px-xl-5 pb-3">
        @foreach ($categories as $category)
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <a class="text-decoration-none" href="/viewCategory/{{ $category->id }}">
                    <div class="cat-item d-flex align-items-center mb-4">
                        <div class="overflow-hidden" style="width: 100px; height: 100px;">
                            <img class="img-fluid" src="{{ asset($category->imagePath) }}" alt="">
                        </div>
                        <div class="flex-fill pl-3">
                            <h6>{{ $category->categoryName }}</h6>
                            <small class="text-body"> {{ $category->products_count }} Products</small>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach

    </div>
</div>
<!-- Categories End -->


<!-- Products Start -->
<div class="container-fluid pt-5 pb-3">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Featured
            Products</span></h2>
    @if ($featured->isEmpty())
        <div class="alert alert-warning text-center ml-3 mr-3">
            No products yet
        </div>
    @endif
    <div class="row px-xl-5">
        @foreach ($featured as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid w-100"
                            src="{{ $product->images->isNotEmpty() && $product->images->first()->imagePath != null ? asset($product->images->first()->imagePath) : asset('img/defaultProduct.png') }}"
                            alt="Product Image">
                        {{-- <img class="img-fluid w-100" src="{{ asset($product->images->first()->imagePath) }}"
                            alt=""> --}}
                        <div class="product-action">
                            <a class="btn btn-outline-dark btn-square" href="/productDetails/{{ $product->id }}"><i
                                    class="fa fa-shopping-cart"></i></a>
                                    @auth
                                    @if (Auth::user()->favoriteProducts->isNotEmpty())
                                        @php $isFav = false @endphp
                                        @foreach (Auth::user()->favoriteProducts as $favoriteProduct)
                                            @if ($favoriteProduct->product->id == $product->id)
                                                <a class="btn btn-outline-dark btn-square active"
                                                    href="/favoriteProduct/{{ $product->id }}"><i
                                                        class="far fa-heart"></i></a>
                                                @php $isFav = true @endphp
                                            @break
                                        @endif
                                    @endforeach
                                    @if (!$isFav)
                                        <a class="btn btn-outline-dark btn-square"
                                            href="/favoriteProduct/{{ $product->id }}"><i class="far fa-heart"></i></a>
                                    @endif
                                @else
                                    <a class="btn btn-outline-dark btn-square"
                                        href="/favoriteProduct/{{ $product->id }}"><i class="far fa-heart"></i></a>
                                @endif
                            @else
                                <a class="btn btn-outline-dark btn-square" href="/favoriteProduct/{{ $product->id }}"><i
                                        class="far fa-heart"></i></a>
                            @endauth
                            <a class="btn btn-outline-dark btn-square" href="/productDetails/{{ $product->id }}"><i
                                    class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <a class="h6 text-decoration-none text-truncate"
                            href="/productDetails/{{ $product->id }}">{{ $product->name }}</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5>Php {{ $product->price }}</h5>
                            <h6 class="text-muted ml-2"><del>{{ $product->price }}</del></h6>
                        </div>
                        <div class="text-primary mr-2">
                            @php
                                $averageRating = $product->reviews->avg('rating'); // Calculate the average rating
                                $fullStars = floor($averageRating); // Get the number of full stars
                                $hasHalfStar = $averageRating - $fullStars >= 0.5; // Check if there is a half star
                            @endphp

                            {{-- Full stars --}}
                            @for ($i = 0; $i < $fullStars; $i++)
                                <small class="fas fa-star"></small>
                            @endfor

                            {{-- Half star --}}
                            @if ($hasHalfStar)
                                <small class="fas fa-star-half-alt"></small>
                                @for ($i = 0; $i < 5 - ceil($averageRating); $i++)
                                    <small class="far fa-star"></small>
                                @endfor
                            @else
                                {{-- Empty stars --}}
                                @for ($i = 0; $i < 5 - floor($averageRating); $i++)
                                    <small class="far fa-star"></small>
                                @endfor
                            @endif
                        </div>
                        <small style="color: black;">({{ $product->reviews_count }})</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!-- Products End -->


<!-- Offer Start -->
<div class="container-fluid pt-5 pb-3">
    <div class="row px-xl-5">
        <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 300px;">
                <img class="img-fluid" src="img/offer-1.jpg" alt="">
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Save 20%</h6>
                    <h3 class="text-white mb-3">Special Offer</h3>
                    <a href="/shops" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 300px;">
                <img class="img-fluid" src="img/offer-2.jpg" alt="">
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Save 20%</h6>
                    <h3 class="text-white mb-3">Special Offer</h3>
                    <a href="/shops" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Offer End -->


<!-- Products Start -->
<div class="container-fluid pt-5 pb-3">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Recent
            Products</span></h2>
    @if ($recentProducts->isEmpty())
        <div class="alert alert-warning text-center ml-3 mr-3">
            No products yet
        </div>
    @endif
    <div class="row px-xl-5">

        @foreach ($recentProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid w-100"
                            src="{{ $product->images->isNotEmpty() && $product->images->first()->imagePath != null ? asset($product->images->first()->imagePath) : asset('img/defaultProduct.png') }}"
                            alt="Product Image">
                        <div class="product-action">
                            <a class="btn btn-outline-dark btn-square" href="/productDetails/{{ $product->id }}"><i
                                    class="fa fa-shopping-cart"></i></a>
                            @auth
                                @if (Auth::user()->favoriteProducts->isNotEmpty())
                                    @php $isFav = false @endphp
                                    @foreach (Auth::user()->favoriteProducts as $favoriteProduct)
                                        @if ($favoriteProduct->product->id == $product->id)
                                            <a class="btn btn-outline-dark btn-square active"
                                                href="/favoriteProduct/{{ $product->id }}"><i
                                                    class="far fa-heart"></i></a>
                                            @php $isFav = true @endphp
                                        @break
                                    @endif
                                @endforeach
                                @if (!$isFav)
                                    <a class="btn btn-outline-dark btn-square"
                                        href="/favoriteProduct/{{ $product->id }}"><i class="far fa-heart"></i></a>
                                @endif
                            @else
                                <a class="btn btn-outline-dark btn-square"
                                    href="/favoriteProduct/{{ $product->id }}"><i class="far fa-heart"></i></a>
                            @endif
                        @else
                            <a class="btn btn-outline-dark btn-square" href="/favoriteProduct/{{ $product->id }}"><i
                                    class="far fa-heart"></i></a>
                        @endauth
                        <a class="btn btn-outline-dark btn-square" href="/productDetails/{{ $product->id }}"><i
                                class="fa fa-search"></i></a>
                    </div>
                </div>
                <div class="text-center py-4">
                    <a class="h6 text-decoration-none text-truncate"
                        href="/productDetails/{{ $product->id }}">{{ $product->name }}</a>
                    <div class="d-flex align-items-center justify-content-center mt-2">
                        <h5>{{ $product->price }}</h5>
                        <h6 class="text-muted ml-2"><del>{{ $product->price }}</del></h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mb-1">
                        <div class="text-primary mr-2">
                            @php
                                $averageRating = $product->reviews->avg('rating'); // Calculate the average rating
                                $fullStars = floor($averageRating); // Get the number of full stars
                                $hasHalfStar = $averageRating - $fullStars >= 0.5; // Check if there is a half star
                            @endphp

                            {{-- Full stars --}}
                            @for ($i = 0; $i < $fullStars; $i++)
                                <small class="fas fa-star"></small>
                            @endfor

                            {{-- Half star --}}
                            @if ($hasHalfStar)
                                <small class="fas fa-star-half-alt"></small>
                                @for ($i = 0; $i < 5 - ceil($averageRating); $i++)
                                    <small class="far fa-star"></small>
                                @endfor
                            @else
                                {{-- Empty stars --}}
                                @for ($i = 0; $i < 5 - floor($averageRating); $i++)
                                    <small class="far fa-star"></small>
                                @endfor
                            @endif
                        </div>
                        <small style="color: black;">({{ $product->reviews_count }})</small>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
</div>
<!-- Products End -->


<!-- Vendor Start -->
<div class="container-fluid py-5">
<div class="row px-xl-5">
    <div class="col">
        <div class="owl-carousel vendor-carousel">
            @foreach ($shops as $shop)
                <a href="/viewShop/{{ $shop->id }}">
                    <div class="bg-light p-4">
                        <img src="{{ asset($shop->shopLogo) }}" alt="">
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
</div>
<!-- Vendor End -->


@include('layouts.footer')

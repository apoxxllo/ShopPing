@include('layouts.header', [
    'categories' => $categories,
    'cartCount' => $cartCount,
    'notificationsCount' => $notificationsCount,
    'favoritesCount' => $favoritesCount,
])


<div class="container-fluid pt-1">
    @if (session('error'))
        <div class="alert alert-danger" style="border-radius: 0;" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if (!$errors->isEmpty())
        <div class="alert alert-danger" style="border-radius: 0;" role="alert">
            {{ $errors->first() }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" style="border-radius: 0;" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <h1 class="ml-5">Your favorite products</h1>
    @if ($favoriteProducts->isEmpty())
        <div class="alert alert-warning ml-5 mr-5 text-center">
            You do not have any favorite products
        </div>
    @else
        <div class="row px-xl-5 ml-3">
            @foreach ($favoriteProducts as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <div class="product-item bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100"
                                src="{{ !$product->product->images->isEmpty() && $product->product->images->first()->imagePath != null ? asset($product->product->images->first()->imagePath) : asset('img/defaultProduct.png') }}"
                                alt="Product Image">
                            <div class="product-action">
                                <a class="btn btn-outline-dark btn-square active"
                                    href="/favoriteProduct/{{ $product->product->id }}"><i class="far fa-heart"></i></a>
                                {{-- <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a> --}}
                                <a class="btn btn-outline-dark btn-square"
                                    href="/productDetails/{{ $product->product->id }}"><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate"
                                href="/productDetails/{{ $product->product->id }}">{{ $product->product->name }}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Product by: {{ $product->product->shop->shopName }}</h5>
                                {{-- <h6 class="text-muted ml-2"><del>$123.00</del></h6> --}}
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <div class="text-primary mr-2">
                                    @php
                                        $averageRating = $product->product->reviews->avg('rating'); // Calculate the average rating
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
                                {{-- <small style="color: black;">({{ $product->reviews_count }})</small>
                                 --}}
                                {{-- <small>Sales: (99)</small> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h1 class="ml-5">Your favorite shops</h1>
    @if ($favoriteShops->isEmpty())
        <div class="alert alert-warning ml-5 mr-5 text-center">
            You do not have any favorite shops
        </div>
    @else
        <div class="row px-xl-5 ml-3">
            @foreach ($favoriteShops as $shop)
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <div class="product-item bg-light mb-4">
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="{{ $shop->shop->shopLogo }}" alt="Shop Logo">
                            <div class="product-action">
                                <a class="btn btn-outline-dark btn-square active"
                                    href="/favoriteShop/{{ $shop->shop->id }}"><i class="far fa-heart"></i></a>
                                {{-- <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a> --}}
                                <a class="btn btn-outline-dark btn-square" href="/viewShop/{{ $shop->shop->id }}"><i
                                        class="fa fa-search"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate"
                                href="/viewShop/{{ $shop->shop->id }}">{{ $shop->shop->shopName }}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Shop by: {{ $shop->shop->user->username }}</h5>
                                {{-- <h6 class="text-muted ml-2"><del>$123.00</del></h6> --}}
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <div class="text-primary mr-2">
                                    @php
                                        $averageRating = $shop->shop->reviews->avg('rating'); // Calculate the average rating
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
                                {{-- <small style="color: black;">({{ $product->reviews_count }})</small> --}}
                                {{-- <small>Sales: (99)</small> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>


@include('layouts.footer')

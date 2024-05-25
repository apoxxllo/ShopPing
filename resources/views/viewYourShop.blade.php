@include('layouts.header', ['categories' => $categories, 'cartCount' => $cartCount, 'notificationsCount' => $notificationsCount, 'favoritesCount' => $favoritesCount])
<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        gap: 0.3rem;
        --stroke: #666;
        --fill: #ffc73a;
    }

    .rating input {
        appearance: unset;
    }

    .rating label {
        cursor: pointer;
    }

    .rating svg {
        width: 2rem;
        height: 2rem;
        overflow: visible;
        fill: transparent;
        stroke: var(--stroke);
        stroke-linejoin: bevel;
        stroke-dasharray: 12;
        animation: idle 4s linear infinite;
        transition: stroke 0.2s, fill 0.5s;
    }

    @keyframes idle {
        from {
            stroke-dashoffset: 24;
        }
    }

    .rating label:hover svg {
        stroke: var(--fill);
    }

    .rating input:checked~label svg {
        transition: 0s;
        animation: idle 4s linear infinite, yippee 0.75s backwards;
        fill: var(--fill);
        stroke: var(--fill);
        stroke-opacity: 0;
        stroke-dasharray: 0;
        stroke-linejoin: miter;
        stroke-width: 8px;
    }

    @keyframes yippee {
        0% {
            transform: scale(1);
            fill: var(--fill);
            fill-opacity: 0;
            stroke-opacity: 1;
            stroke: var(--stroke);
            stroke-dasharray: 10;
            stroke-width: 1px;
            stroke-linejoin: bevel;
        }

        30% {
            transform: scale(0);
            fill: var(--fill);
            fill-opacity: 0;
            stroke-opacity: 1;
            stroke: var(--stroke);
            stroke-dasharray: 10;
            stroke-width: 1px;
            stroke-linejoin: bevel;
        }

        30.1% {
            stroke: var(--fill);
            stroke-dasharray: 0;
            stroke-linejoin: miter;
            stroke-width: 8px;
        }

        60% {
            transform: scale(1.2);
            fill: var(--fill);
        }
    }
</style>

<div class="container-fluid pb-5">
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
    <div class="row px-xl-5">
        <div class="col-lg-5 mb-30">
            <div id="product-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner bg-light">
                    <div class="carousel-item active"> {{-- ONE IMAGE --}}
                        <img class="w-100 h-100" src="{{asset($shop->shopLogo)}}" alt="Image">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                    <i class="fa fa-2x fa-angle-left text-dark"></i>
                </a>
                <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                    <i class="fa fa-2x fa-angle-right text-dark"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-7 h-auto mb-30">
            <div class="h-100 bg-light p-30">
                <h3>{{$shop->shopName}}</h3>
                <a href="/addProduct/{{$shop->id}}" class="btn btn-primary">Add Product</a>
                <a href="/manageOrders/{{$shop->id}}" class="btn btn-primary">Manage Orders</a>
                <a href="/manageProducts/{{$shop->id}}" class="btn btn-primary">Manage Products</a>
                <a href="/editShop/{{$shop->id}}" class="btn btn-primary">Edit Shop</a>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        @php
                            $averageRating = $reviews->avg('rating'); // Calculate the average rating
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
                    <small style="color:black" class="pt-1">({{$reviewsCount}} Reviews)</small>
                </div>
                {{-- <h3 class="font-weight-semi-bold mb-4">$150.00</h3> --}}
                <p class="mb-4">{{$shop->description}}</p>
                <h2 class="mt-4 mb-3">Popular Products</h2>
                <div class="row">
                    @if($popularProducts->isEmpty())
                        <div class="alert alert-warning col-md-12 text-center mr-3">
                            No product found
                        </div>
                    @endif
                    @foreach ($popularProducts as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img class="card-img-top"
                                    src="{{ $product->images->isNotEmpty() && $product->images->first()->imagePath != null ? asset($product->images->first()->imagePath) : asset('img/defaultProduct.png') }}"
                                    alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $product->name }}</h5>
                                    <p class="card-text text-center">{{ $product->description }}</p>
                                    <p class="card-text text-center">Sales: {{ $product->orders_count }}</p>
                                    <a href="/productDetails/{{ $product->id }}" class="btn btn-primary">View
                                        Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="bg-light p-30">
                <div class="nav nav-tabs mb-4">
                    {{-- <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-2">Information</a> --}}
                    <a class="nav-item nav-link text-dark active" data-toggle="tab" href="#tab-pane-1">Reviews ({{$reviewsCount}})</a>
                    <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-2">Description</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-4">{{ $reviewsCount }} review{{ $reviewsCount > 1 ? 's' : '' }} for
                                    Shop "{{ $shop->shopName }}"</h4>
                                @foreach ($reviews as $review)
                                    <div class="media mb-4">
                                        <img src="{{ asset('img/defaultProfile.png') }}" alt="Image"
                                            class="img-fluid mr-3 mt-1" style="width: 45px;">
                                        <div class="media-body">
                                            <h6>
                                                @if ($review->user)
                                                    {{ $review->user->username }}
                                                @else
                                                    Unknown User
                                                @endif
                                                <small> -
                                                    <i>{{ $review->created_at->format('F j, Y \a\t g:ia') }}</i></small>
                                            </h6>
                                            <div class="text-primary mb-2">
                                                @php
                                                    $rating = $review->rating; // Calculate the average rating
                                                    // $fullStars = floor($averageRating); // Get the number of full stars
                                                    // $hasHalfStar = $averageRating - $fullStars >= 0.5; // Check if there is a half star
                                                @endphp

                                                {{-- Full stars --}}
                                                @for ($i = 0; $i < $rating; $i++)
                                                    <small class="fas fa-star"></small>
                                                @endfor

                                                {{-- Empty stars --}}
                                                @for ($i = 0; $i < 5 - ceil($rating); $i++)
                                                    <small class="far fa-star"></small>
                                                @endfor
                                            </div>
                                            <p>{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                {{$reviews->links()}}
                            </div>

                            <div class="col-md-6">
                                <form action="/reviewShop/{{ $shop->id }}" method="post">
                                    @csrf
                                    <h4 class="mb-4">Leave a review</h4>
                                    <small style="color: black">Your email address will not be published. Required
                                        fields are marked *</small>
                                    <div class="d-flex my-3">
                                        <p class="mb-0 mr-2">Your Rating * :</p>
                                        <div class="rating">
                                            <input type="radio" id="star-1" name="star" value="5">
                                            <label for="star-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path pathLength="360"
                                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                                    </path>
                                                </svg>
                                            </label>
                                            <input type="radio" id="star-2" name="star" value="4">
                                            <label for="star-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path pathLength="360"
                                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                                    </path>
                                                </svg>
                                            </label>
                                            <input type="radio" id="star-3" name="star" value="3">
                                            <label for="star-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path pathLength="360"
                                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                                    </path>
                                                </svg>
                                            </label>
                                            <input type="radio" id="star-4" name="star" value="2">
                                            <label for="star-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path pathLength="360"
                                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                                    </path>
                                                </svg>
                                            </label>
                                            <input type="radio" id="star-5" name="star" value="1">
                                            <label for="star-5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path pathLength="360"
                                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                                    </path>
                                                </svg>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Your Review *</label>
                                        <textarea id="message" name="comment" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Your Name *</label>
                                        <input type="text" class="form-control" name="name" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Your Email *</label>
                                        <input type="email" class="form-control" name="email" readonly
                                            value="{{ Auth::user()->email }}" id="email">
                                    </div>
                                    <div class="form-group mb-0">
                                        <input type="submit" value="Leave Your Review" class="btn btn-primary px-3">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <h4 class="mb-3">Shop Description</h4>
                        <p>{{$shop->description}}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Products Start -->
    <div class="container-fluid py-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Products in {{$shop->shopName}}</span></h2>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                    @foreach ($products as $product)
                    <div class="product-item bg-light"> {{-- one picture --}}
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="{{ $product->images->isNotEmpty() && $product->images->first()->imagePath != null ? asset($product->images->first()->imagePath) : asset('img/defaultProduct.png') }}"
                            alt="Product Image">
                            <div class="product-action">
                                <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                                <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                                <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a>
                                <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="/productDetails/{{$product->id}}">{{$product->name}}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Php{{$product->price}}</h5><h6 class="text-muted ml-2"><del>Php{{$product->price}}</del></h6>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <small class="fa fa-star text-primary mr-1"></small>
                                <small class="fa fa-star text-primary mr-1"></small>
                                <small class="fa fa-star text-primary mr-1"></small>
                                <small class="fa fa-star text-primary mr-1"></small>
                                <small class="fa fa-star text-primary mr-1"></small>
                                <small>(99)</small>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->
@include('layouts.footer')

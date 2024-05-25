@include('layouts.header', [
    'categories' => $categories,
    'cartCount' => $cartCount,
    'notificationsCount' => $notificationsCount,
])
{{ $itemStock = $product->stock }}
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
<!-- Product Detail Start -->
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
                    @foreach ($product->images->isNotEmpty() ? $product->images : [null] as $index => $image)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img class="d-block w-100"
                                src="{{ $image ? asset($image->imagePath) : asset('img/defaultProduct.png') }}"
                                alt="Image">
                        </div>
                    @endforeach
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
                <h3>{{ $product->name }}</h3>
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
                    <small style="color: black;" class="pt-1">({{$reviewsCount}} Reviews)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4">Php{{ $product->price }}</h3>
                <p class="mb-2">{{ $product->description }}</p>
                <p class="mb-2">Stock: {{ $product->stock }} pieces</p>
                <p class="mb-2">Sold: {{ $product->orders_count }} pieces</p>
                <form method="POST" action="{{ route('addToCart') }}">
                    @csrf
                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">

                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-minus" onclick="updateQuantity(-1)">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>

                            <input type="text" id="productQuantity" name="quantity"
                                class="form-control bg-secondary border-0 text-center" value="1" readonly>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-plus" onclick="updateQuantity(1)">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" readonly id="productId" name="productId" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-primary px-3" id="addToCartBtn"><i
                                class="fa fa-shopping-cart mr-1"></i>
                            Add To Cart</button>
                    </div>
                </form>

                <div class="d-flex pt-2">
                    <strong class="text-dark mr-2">Share on:</strong>
                    <div class="d-inline-flex">
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
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
                                    Product "{{ $product->name }}"</h4>
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
                                <form action="/reviewProduct/{{ $product->id }}" method="post">
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
                                        <textarea id="message" name="comment" cols="30" rows="5" class="form-control">{{old('comment')}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Your Name *</label>
                                        <input type="text" value="{{old('name')}}" class="form-control" name="name" id="name">
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
                        <h4 class="mb-3">Product Description</h4>
                        <p>{{$product->description}}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Shop Detail End -->


<!-- Products Start -->
<div class="container-fluid py-5">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">You
            May Also Like</span></h2>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel related-carousel">
                @foreach ($featured as $product)
                    <div class="product-item bg-light"> {{-- one picture --}}
                        <div class="product-img position-relative overflow-hidden">
                            <img class="img-fluid w-100"
                                src="{{ $product->images->isNotEmpty() && $product->images->first()->imagePath != null ? asset($product->images->first()->imagePath) : asset('img/defaultProduct.png') }}"
                                alt="Product Image">
                            <div class="product-action">
                                <a class="btn btn-outline-dark btn-square" href=""><i
                                        class="fa fa-shopping-cart"></i></a>
                                <a class="btn btn-outline-dark btn-square" href=""><i
                                        class="far fa-heart"></i></a>
                                <a class="btn btn-outline-dark btn-square" href=""><i
                                        class="fa fa-sync-alt"></i></a>
                                <a class="btn btn-outline-dark btn-square" href=""><i
                                        class="fa fa-search"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate"
                                href="/productDetails/{{ $product->id }}">{{ $product->name }}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Php{{ $product->price }}</h5>
                                <h6 class="text-muted ml-2"></h6>
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
            @if ($featured->isEmpty())
                <div class="alert alert-warning text-center mr-5 ml-5">
                    Shop has no more products
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Products End -->

<script>
    function updateQuantity(change) {
        let quantityInput = document.getElementById('productQuantity');
        let currentQuantity = parseInt(quantityInput.value);
        console.log(currentQuantity)
        if (change === -1 && currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
            console.log(quantityInput.value)
        } else if (change === 1) {
            if (currentQuantity !== {{ $itemStock }}) {
                quantityInput.value = currentQuantity + 1;
                console.log(quantityInput.value)
            }
        }
    }

    document.getElementById('addToCartBtn').addEventListener('click', function() {
        let quantity = parseInt(document.getElementById('productQuantity').value);
        if (quantity >= 1) {
            addToCart(quantity);
        }
    });

    function addToCart(quantity) {
        // Perform your add to cart logic here
        alert('Product added to cart with quantity: ' + quantity);
        // You can send an AJAX request to your server here
    }
</script>

@include('layouts.footer')

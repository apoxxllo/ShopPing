@include('layouts.header', ['categories' => $categories, 'cartCount' => $cartCount])
{{$itemStock = $product->stock}}
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
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star-half-alt"></small>
                            <small class="far fa-star"></small>
                        </div>
                        <small class="pt-1">(99 Reviews)</small>
                    </div>
                    <h3 class="font-weight-semi-bold mb-4">Php{{ $product->price }}</h3>
                    <p class="mb-2">{{ $product->description }}</p>
                    <p class="mb-2">Stock: {{ $product->stock }} pieces</p>
                    <form method="POST" action="{{ route('addToCart') }}">
                        @csrf
                        <div class="d-flex align-items-center mb-4 pt-2">
                            <div class="input-group quantity mr-3" style="width: 130px;">

                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-primary btn-minus"
                                        onclick="updateQuantity(-1)">
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
                        <a class="nav-item nav-link text-dark active" data-toggle="tab"
                            href="#tab-pane-1">Description</a>
                        <a class="nav-item nav-link text-dark" data-toggle="tab" href="#tab-pane-3">Reviews (0)</a>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <h4 class="mb-3">Product Description</h4>
                            <p>{{ $product->description }}</p>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="mb-4">1 review for "Product Name"</h4>
                                    <div class="media mb-4">
                                        <img src="img/user.jpg" alt="Image" class="img-fluid mr-3 mt-1"
                                            style="width: 45px;">
                                        <div class="media-body">
                                            <h6>John Doe<small> - <i>01 Jan 2045</i></small></h6>
                                            <div class="text-primary mb-2">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <i class="far fa-star"></i>
                                            </div>
                                            <p>Diam amet duo labore stet elitr ea clita ipsum, tempor labore accusam
                                                ipsum et no at. Kasd diam tempor rebum magna dolores sed sed eirmod
                                                ipsum.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="mb-4">Leave a review</h4>
                                    <small>Your email address will not be published. Required fields are marked
                                        *</small>
                                    <div class="d-flex my-3">
                                        <p class="mb-0 mr-2">Your Rating * :</p>
                                        <div class="text-primary">
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </div>
                                    <form>
                                        <div class="form-group">
                                            <label for="message">Your Review *</label>
                                            <textarea id="message" cols="30" rows="5" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Your Name *</label>
                                            <input type="text" class="form-control" id="name">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Your Email *</label>
                                            <input type="email" class="form-control" id="email">
                                        </div>
                                        <div class="form-group mb-0">
                                            <input type="submit" value="Leave Your Review"
                                                class="btn btn-primary px-3">
                                        </div>
                                    </form>
                                </div>
                            </div>
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

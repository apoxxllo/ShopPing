@include('layouts.header', ['categories' => $categories, 'cartCount' => $cartCount, 'notificationsCount' => $notificationsCount])

<form action="{{ route('placeOrder') }}" method="post">
    @csrf
    <!-- Checkout Start -->
    <div class="container-fluid">
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
            <div class="col-lg-8">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Billing
                        Address</span></h5>
                <div class="bg-light p-30 mb-5">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>First Name</label>
                            <input type="hidden" name="userId" value="{{ Auth::user()->id }}" readonly>
                            <input class="form-control"
                                value="{{ Auth::user()->firstName == null ? old('firstName') : Auth::user()->firstName}}"
                                type="text" placeholder="John" name="firstName">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Last Name</label>
                            <input class="form-control" value="{{ Auth::user()->lastName == null ? old('lastName') : Auth::user()->lastName }}" type="text"
                                placeholder="Doe" name="lastName">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>E-mail</label>
                            <input class="form-control" name="email" value="{{ Auth::user()->email == null ? old('email') : Auth::user()->email }}" type="text"
                                placeholder="example@email.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mobile No</label>
                            <input class="form-control" name="contact" value="{{ Auth::user()->contact == null ? old('contact') : Auth::user()->contact }}" type="text"
                                placeholder="+123 456 789">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address</label>
                            <input id="addressInput" name="address" value="{{ Auth::user()->address == null ? old('address') : Auth::user()->address }}" class="form-control"
                                type="text" placeholder="123 Street">
                        </div>
                        <div class="col-md-12 form-group">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Order
                        Total</span></h5>
                <div class="bg-light p-30 mb-5">
                    <div class="border-bottom">
                        <h6 class="mb-3">Products</h6>
                        @foreach ($cart as $product)
                            <input type="hidden" readonly
                                name="products[{{ $product->product->id }}][{{ $product->quantity }}]"
                                value="{{ $product->quantity }}">
                            <div class="d-flex justify-content-between">
                                <p>{{ $product->product->name . ' ' . $product->quantity }}x</p>
                                <p>Php {{ number_format($product->product->price * $product->quantity, 2) }}</p>
                            </div>
                        @endforeach
                        <div class="border-bottom pt-3 pb-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h6>Subtotal</h6>
                                <h6>Php {{ number_format($total, 2) }}</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <del>
                                    <h6 class="font-weight-medium">Shipping</h6>
                                </del>
                                <del>
                                    <h6 class="font-weight-medium">Php 10</h6>
                                </del>
                            </div>
                        </div>
                        <div class="pt-2">
                            <div class="d-flex justify-content-between mt-2">
                                <h5>Total</h5>
                                <h5>Php {{ number_format($total, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <h5 class="section-title position-relative text-uppercase mt-3 mb-1"><span
                                class="bg-secondary pr-3">Payment</span></h5>
                        <div class="bg-light p-30">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment" id="gcash"
                                        value="gcash" required>
                                    <label class="custom-control-label" for="gcash">GCash</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment" id="paypal"
                                        value="paypal" required>
                                    <label class="custom-control-label" for="paypal">Paypal</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" value="directcheck"
                                        name="payment" id="directcheck" required>
                                    <label class="custom-control-label" for="directcheck">Direct Check</label>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment"
                                        id="banktransfer" value="banktransfer" required>
                                    <label class="custom-control-label" for="banktransfer">Bank Transfer</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-primary font-weight-bold py-3">Place
                                Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Checkout End -->

</form>


@include('layouts.footer')

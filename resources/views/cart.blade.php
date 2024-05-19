@include('layouts.header', ['categories' => $categories, 'cartCount' => $cartCount])

<!-- Cart Start -->
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
        <div class="alert alert-success ml-4 mr-4" style="border-radius: 0;" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-light table-borderless table-hover text-center mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @if ($cart->isEmpty())
                        <tr>
                            <td colspan="5" class="alert alert-warning text-center">
                                No items found in your cart yet!
                            </td>
                        </tr>
                    @else
                        @foreach ($cart as $product)
                            <tr>
                                <td class="align-middle"><img
                                        src="{{ asset($product->product->images->first()->imagePath) }}" alt=""
                                        style="width: 50px;">
                                    {{ $product->product->name }}</td>
                                <td class="align-middle">Php {{ number_format($product->product->price,2) }}</td>
                                <td class="align-middle">
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <form action="{{ route('deductOne') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="productId" value="{{ $product->product->id }}">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-minus" type="submit">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                        </form>
                                        <input type="text" readonly
                                            class="form-control form-control-sm bg-secondary border-0 text-center"
                                            id="productQuantity" value="{{ $product->quantity }}">
                                        <form action="{{ route('addOne') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="productId" value="{{ $product->product->id }}">
                                            <div class="input-group-btn">
                                                <button type="submit" class="btn btn-sm btn-primary btn-plus"
                                                    onclick="updateQuantity(1)">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                                <td class="align-middle">Php {{ number_format($product->quantity * $product->product->price,2) }}
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('removeFromCart') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="productId" value="{{ $product->product->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            {{-- <form class="mb-30" action="">
                <div class="input-group">
                    <input type="text" class="form-control border-0 p-4" placeholder="Coupon Code">
                    <div class="input-group-append">
                        <button class="btn btn-primary">Apply Coupon</button>
                    </div>
                </div>
            </form> --}}
            <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Cart
                    Summary</span></h5>
            <div class="bg-light p-30 mb-5">
                <div class="border-bottom pb-2">
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Subtotal</h6>
                        <h6>Php {{ number_format($total,2) }}</h6>
                    </div>
                    {{-- <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Shipping</h6>
                        <h6 class="font-weight-medium">Php 10</h6>
                    </div> --}}
                </div>
                <div class="pt-2">
                    <div class="d-flex justify-content-between mt-2">
                        <h5>Total</h5>
                        <h5>Php {{ number_format($total,2) }}</h5>
                    </div>
                    <a href="/checkout" class="btn btn-block btn-primary font-weight-bold my-3 py-3">Proceed To Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->

<script>
</script>
@include('layouts.footer')

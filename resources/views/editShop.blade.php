@include('layouts.header', ['categories' => $categories, 'cartCount' => $cartCount, 'notificationsCount' => $notificationsCount, 'favoritesCount' => $favoritesCount])

<div class="container mt-5">
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="color: black;">Edit Shop</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('editShopPost') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="shopId" hidden readonly value="{{$shop->id}}">
                        <div class="form-group">
                            <label for="shopName">Shop Name</label>
                            <input type="text" id="shopName" name="shopName" class="form-control" value="{{$shop->shopName}}">
                        </div>

                        <div class="form-group">
                            <label for="shopLogo" class="form-label">Shop Logo</label>
                            <input type="file" id="shopLogo" name="shopLogo" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" class="form-control" rows="3" >{{$shop->address}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3">{{$shop->description}}</textarea>
                        </div>
                        <!-- Add more fields as needed -->

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')

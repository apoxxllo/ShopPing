@include('layouts.header', ['categories' => $categories])

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="color: black;">Setup Shop</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('setupSeller') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="shopName">Shop Name</label>
                            <input type="text" id="shopName" name="shopName" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="shopLogo" class="form-label">Shop Logo</label>
                            <input type="file" id="shopLogo" name="shopLogo" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
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

@include('layouts.header', ['categories' => $categories, 'cartCount' => $cartCount])

<div class="container-fluid pt-4">
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
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3>Add Product to Shop: {{$shop->shopName}}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('addProductPost', $shop->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" value="{{old('name')}}" name="name">
                        </div>
                        <div class="form-group">
                            <label for="initialStock">Initial Stock</label>
                            <input type="number" class="form-control" id="initialStock" value="{{old('stock')}}" name="stock">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" value="{{old('description')}}" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" class="form-control" id="price" value="{{old('price')}}" name="price">
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" id="category" name="category">
                                <option value="{{old('category')}}" disabled selected>Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="images">Product Images</label>
                            <input type="file" class="form-control-file" id="images" name="images[]" multiple onchange="previewImages()">
                            <div class="row mt-3" id="imagePreview"></div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')

<script>
    function previewImages() {
        var preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        var files = document.getElementById('images').files;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var reader = new FileReader();

            reader.onload = (function(f) {
                return function(e) {
                    var col = document.createElement('div');
                    col.classList.add('col-md-3', 'mb-3');
                    var img = document.createElement('img');
                    img.classList.add('img-thumbnail');
                    img.src = e.target.result;
                    col.appendChild(img);
                    preview.appendChild(col);
                };
            })(file);

            reader.readAsDataURL(file);
        }
    }
</script>

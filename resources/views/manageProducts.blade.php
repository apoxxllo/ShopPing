@include('layouts.header', ['categories' => $categories, 'cartCount' => $cartCount, 'notificationsCount' => $notificationsCount, 'favoritesCount' => $favoritesCount])


<div class="content-body">
    @if (session('error'))
        <div class="alert alert-danger ml-3 mr-3" style="border-radius: 0;" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if (!$errors->isEmpty())
        <div class="alert alert-danger ml-3 mr-3" style="border-radius: 0;" role="alert">
            {{ $errors->first() }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success ml-3 mr-3" style="border-radius: 0;" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h4>Manage Products in your shop "{{ $shop->shopName }}"</h4>
                        </div>
                        {{-- <form id="searchForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="GET"> --}}
                        {{-- <div class="mb-3">
                            <label for="categoryFilter" class="form-label">Filter by Category:</label>
                            <select class="form-select" id="categoryFilter" name="category">
                                <option value="0">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- </form> --}}
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Image</th>
                                        <th>Product Name</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Category</th>
                                        <th>Shop</th>
                                        <th>Sales</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($products->isEmpty())
                                        <tr>
                                            <td colspan="8" class="text-center">No products added</td>
                                        </tr>
                                    @endif
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <img style="min-width: 70px; max-width: 70px;"
                                                    src="{{ $product->images->isNotEmpty() && $product->images->first()->imagePath != null ? asset($product->images->first()->imagePath) : asset('img/defaultProduct.png') }}"
                                                    alt="Product Image">
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->stock }}</td>
                                            <td>Php {{ $product->price }}</td>
                                            <td>{{ $product->category->categoryName }}</td>
                                            <td>{{ $product->shop->shopName }}</td>
                                            <td>{{$product->orders_count}}</td>
                                            <td> <a href="#" class="btn btn-info editProductBtn"
                                                    style="color: white;" id="showEditProductModalBtn"
                                                    data-toggle="modal" data-productname="{{ $product->name }}"
                                                    data-id="{{ $product->id }}" data-stock="{{ $product->stock }}"
                                                    data-price="{{ $product->price }}"
                                                    data-description="{{ $product->description }}"
                                                    data-categoryid="{{ $product->category->id }}"
                                                    data-shopid="{{ $product->shop->id }}"
                                                    data-target="#editProductModal">Edit</a>
                                                || <a class="btn btn-danger deleteProductBtn" style="color: white;"
                                                    id="showDeleteBookModalBtn" data-toggle="modal"
                                                    data-id="{{ $product->id }}"
                                                    data-productname="{{ $product->name }}"
                                                    data-target="#deleteProductModal">Delete</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Add Book Button -->
                        <div class="mt-3 text-right">
                            <button type="button" class="btn btn-info" id="showAddProductModalBtn">
                                <i class="icon icon-plus"></i> Add Product
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /# card -->
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookModalLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for adding a new book -->
                <form id="addProductForm" method="POST" action="{{ route('addProductFromManage') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="shopId" readonly value="{{$shop->id}}">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="">
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="10" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" step=".01" id="price" name="price"
                            value="<?= isset($_SESSION['available']) ? $_SESSION['available'] : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="images">Product Images</label>
                        <input type="file" class="form-control-file" id="images" name="images[]" multiple
                            onchange="previewImages()">
                        <div class="row mt-3" id="imagePreview"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="addBookBtn">Add Product</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteBookModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="output">Are you sure you want to delete this product? </p>
                <p>This action cannot be undone.</p>
            </div>
            <form id="deleteProductForm" action="{{route('deleteProductPost')}}" method="POST">
                @csrf
                <div class="modal-footer">
                    <input hidden type="number" readonly class="form-control" id="productId" name="productId">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing product details -->
                <form id="editProductForm" action="{{ route('updateProduct') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="productId" id="productIdUpdate" readonly hidden>
                    <div class="form-group">
                        <label for="editProductName">Name</label>
                        <input type="text" class="form-control" id="editProductName" name="name">
                    </div>
                    <div class="form-group">
                        <label for="editProductName">Description</label>
                        <textarea class="form-control" id="editProductDescription" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editProductStock">Stock/Available</label>
                        <input type="number" class="form-control" id="editProductStock" name="stock">
                    </div>
                    <div class="form-group">
                        <label for="editProductPrice">Price</label>
                        <input type="number" step=".01" class="form-control" id="editProductPrice" name="price">
                    </div>
                    <div class="form-group">
                        <label for="editProductCategory">Category</label>
                        <select class="form-control" id="editProductCategory" name="category">
                            <!-- Populate categories dynamically -->
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="images">Product Images</label>
                        <input type="file" class="form-control-file" id="images" name="images[]" multiple
                            onchange="previewImages()">
                        <div class="row mt-3" id="imagePreview"></div>
                    </div>
                    <input type="hidden" class="form-control" id="editProductId" name="id">
                    <button type="submit" class="btn btn-primary" id="saveProductBtn">Save Changes</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.editProductBtn', function() {
            var name = $(this).data('productname');
            var stock = $(this).data('stock');
            var price = $(this).data('price');
            var category = $(this).data('categoryid');
            var description = $(this).data('description');
            var shop = $(this).data('shopid');
            var id = $(this).data('id');
            $('#editProductName').val(name);
            console.log(name);
            $('#editProductStock').val(stock);
            $('#editProductPrice').val(price);
            $('#editProductCategory').val(category);
            $('#editProductDescription').val(description);
            $('#editProductShop').val(shop);
            $('#productIdUpdate').val(id);
        });

        $('.deleteProductBtn').click(function() {
            var id = $(this).data('id');
            var productName = $(this).data('productname');

            $('#confirmDeleteBtn').data('productid', id);

            // Set the book name in the modal output
            $('#output').text('Are you sure you want to delete this product? (' + bookName + ')');
        });
        $('#confirmDeleteBtn').click(function() {
            // Get the bookId from the data attribute of the confirm delete button
            var productId = $(this).data('productid');

            $('#productId').val(productId);

            // Set the action attribute of the form with the bookId parameter
            var form = $('#deleteProductForm');
            form.attr('action', '{{ route('deleteProductPost') }}');
        });
        $('#showAddProductModalBtn').click(function() {
            $('#addProductModal').modal('show');
        });
    });

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

@include('layouts.footer')

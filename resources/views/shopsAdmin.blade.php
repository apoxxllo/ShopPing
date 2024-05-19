@include('layouts.headerAdmin')

<div class="content-body">
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h4>ShopPING Shops</h4>
                        </div>
                        {{-- </form> --}}
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Shop Logo</th>
                                        <th>Shop Name</th>
                                        <th>Owner</th>
                                        <th>Address</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shops as $shop)
                                        <tr>
                                            <td><img style="max-width: 75px;" src="{{asset($shop->shopLogo)}}" alt="Category Image"></td>
                                            <td>{{ $shop->shopName }}</td>
                                            <td>{{ $shop->user->username }}</td>
                                            <td>{{ $shop->address }}</td>
                                            <td>{{ $shop->description }}</td>
                                            <td> <a href="#" class="btn btn-info editShopBtn"
                                                    style="color: white;" id="showEditShopModalBtn" data-toggle="modal"
                                                    data-target="#editShopModal"
                                                    data-id="{{$shop->id}}"
                                                    data-shopname="{{$shop->shopName}}"
                                                    data-address="{{$shop->address}}"
                                                    data-description="{{$shop->description}}"
                                                    >Edit</a>
                                                || <a class="btn btn-danger deleteShopBtn" style="color: white;"
                                                    id="showDeleteShopModalBtn" data-toggle="modal" data-id="{{$shop->id}}" data-shopname="{{$shop->shopName}}"
                                                    data-target="#deleteShopModal">Delete</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <!-- Add User Button -->
                        <div class="mt-3 text-right">
                            <button type="button" class="btn btn-info" id="showAddUserModalBtn">
                                <i class="icon icon-plus"></i>
                            </button>
                        </div> --}}
                    </div>
                </div>
                <!-- /# card -->
            </div>
        </div>
    </div>
</div>



 <!-- Delete Shop Modal -->
 <div class="modal fade" id="deleteShopModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="output">Are you sure you want to delete this shop?</p>
                <p>This action cannot be undone.</p>
            </div>
            <form id="deleteShopForm" action="{{route('deleteShop')}}" method="POST">
                @csrf
                <div class="modal-footer">
                    <input type="hidden" readonly id="shopId" name="shopId" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteShopBtn">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Shop Modal -->
<div class="modal fade" id="editShopModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Shop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing user details -->
                <form id="editUserForm" action="{{ route('updateShop') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="shopId" id="shopIdUpdate" readonly hidden>
                    <div class="form-group">
                        <label for="editCategoryName">Shop name</label>
                        <input type="text" class="form-control" id="editShopName" name="shopName">
                    </div>
                    <div class="form-group">
                        <label for="username">Description</label>
                        <input type="text" class="form-control" id="editDescription" name="description">
                    </div>
                    <div class="form-group">
                        <label for="firstName">Address</label>
                        <input type="text" class="form-control" id="editAddress" name="address">
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveShopBtn">Save Changes</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.footerAdmin')

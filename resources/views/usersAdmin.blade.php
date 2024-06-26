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
                            <h4>ShopPING Users</h4>
                        </div>
                        {{-- </form> --}}
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            {{-- <td><img style="max-width: 75px;" src="{{asset($user->pr)}}" alt="Category Image"></td> --}}
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->firstName }}</td>
                                            <td>{{ $user->lastName }}</td>
                                            <td>{{ $user->role->name }}</td>
                                            <td> <a href="#" class="btn btn-info editUserBtn"
                                                    style="color: white;" id="showEditUserModalBtn" data-toggle="modal"
                                                    data-target="#editUserModal"
                                                    data-id="{{$user->id}}"
                                                    data-username="{{$user->username}}"
                                                    data-firstname="{{$user->firstName}}"
                                                    data-email="{{$user->email}}"
                                                    data-lastname="{{$user->lastName}}"
                                                    >Edit</a>
                                                || <a class="btn btn-danger deleteUserBtn" style="color: white;"
                                                    id="showDeleteUserModalBtn" data-toggle="modal" data-id="{{$user->id}}" data-username="{{$user->username}}"
                                                    data-target="#deleteUserModal">Delete</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Add User Button -->
                        <div class="mt-3 text-right">
                            <button type="button" class="btn btn-info" id="showAddUserModalBtn">
                                <i class="icon icon-plus"></i> Add User
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /# card -->
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for adding a new category -->
                <form id="addUserForm" method="POST" action="{{route('addUser')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="username">User userame</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{old('username')}}">
                    </div>
                    <div class="form-group">
                        <label for="username">User email</label>
                        <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                        <label for="firstName">User first name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="{{old('firstName')}}">
                    </div>
                    <div class="form-group">
                        <label for="lastName">User last name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="{{old('lastName')}}">
                    </div>
                    <div class="form-group">
                        <label for="password">User password</label>
                        <input type="password" class="form-control" id="password" name="password" value="">
                    </div>
                    <button type="submit" class="btn btn-primary" id="addUserBtn">Add</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

 <!-- Delete User Modal -->
 <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="output">Are you sure you want to delete this user?</p>
                <p>This action cannot be undone.</p>
            </div>
            <form id="deleteUserForm" action="{{route('deleteUser')}}" method="POST">
                @csrf
                <div class="modal-footer">
                    <input type="hidden" readonly id="userId" name="userId" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteUserBtn">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing user details -->
                <form id="editUserForm" action="{{ route('updateUser') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="userId" id="userIdUpdate" readonly hidden>
                    <div class="form-group">
                        <label for="editCategoryName">Username</label>
                        <input type="text" class="form-control" id="editUserName" name="username">
                    </div>
                    <div class="form-group">
                        <label for="username">User email</label>
                        <input type="text" class="form-control" id="editEmail" name="email" value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                        <label for="firstName">User first name</label>
                        <input type="text" class="form-control" id="editFirstName" name="firstName" value="{{old('firstName')}}">
                    </div>
                    <div class="form-group">
                        <label for="lastName">User last name</label>
                        <input type="text" class="form-control" id="editLastName" name="lastName" value="{{old('lastName')}}">
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveUserBtn">Save Changes</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.footerAdmin')

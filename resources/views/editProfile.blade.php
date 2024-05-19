@include('layouts.header', ['categories' => $categories])
<style>
    .edit-button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        transition-duration: 0.3s;
        overflow: hidden;
        position: relative;
        text-decoration: none !important;
    }

    .edit-svgIcon {
        width: 17px;
        transition-duration: 0.3s;
    }

    .edit-svgIcon path {
        fill: white;
    }

    .edit-button:hover {
        width: 120px;
        border-radius: 50px;
        transition-duration: 0.3s;
        background-color: blue;
        align-items: center;
    }

    .edit-button:hover .edit-svgIcon {
        width: 20px;
        transition-duration: 0.3s;
        transform: translateY(60%);
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }

    .edit-button::before {
        display: none;
        content: "Edit";
        color: white;
        transition-duration: 0.3s;
        font-size: 2px;
    }

    .edit-button:hover::before {
        display: block;
        padding-right: 10px;
        font-size: 13px;
        opacity: 1;
        transform: translateY(0px);
        transition-duration: 0.3s;
    }
</style>
<div class="container mt-5">
    <div class="row justify-content-center">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="color:black">User Profile</div>
                <form action="{{ route('updateProfile') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img class="mb-2" src="{{ asset('img/defaultProfile.png') }}" alt="Profile Image"
                                style="width: 200px; height: 200px; border-radius: 50%;">
                            {{-- <input class="custom-file-label" for="fileInput" id="fileLabel"/> --}}
                            {{-- <input type="file" class="form-control" name="fileInput" /> --}}
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">Username:</label>
                            <div class="col-md-6">
                                <input id="username" name="username" type="text" class="form-control"
                                    value="{{ $user->username }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email:</label>
                            <div class="col-md-6">
                                <input id="email" name="email" type="email" class="form-control"
                                    value="{{ $user->email }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name:</label>
                            <div class="col-md-6">
                                <input id="firstname" type="text" class="form-control" name="firstName"
                                    value="{{ $user->firstName }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name:</label>
                            <div class="col-md-6">
                                <input id="lastName" type="text" class="form-control" name="lastName"
                                    value="{{ $user->lastName }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4 offset-md-6">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Navigation bar for order history and pending orders -->


@include('layouts.footer')

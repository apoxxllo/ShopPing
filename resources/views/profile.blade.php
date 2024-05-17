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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="color:black">User Profile</div>

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
                            <input id="username" type="text" class="form-control" value="{{ $user->username }}"
                                readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Email:</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" value="{{ $user->email }}"
                                readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name:</label>
                        <div class="col-md-6">
                            <input id="firstname" type="text" class="form-control" value="{{ $user->firstName }}"
                                readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name:</label>
                        <div class="col-md-6">
                            <input id="lastname" type="text" class="form-control" value="{{ $user->lastName }}"
                                readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Do you want to sell?</label>
                        <div class="col-md-4">
                            <a href="/setupSeller" class="btn btn-primary">Want to sell? Set up a Shop!</a>
                        </div>
                        <div class="col-md-3 mt-2">
                            <a class="edit-button" href="/editProfile">
                                <svg class="edit-svgIcon" viewBox="0 0 512 512">
                                    <path
                                        d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center">
                                            <button class="btn btn-primary btn-block">Order History</button>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <button class="btn btn-primary btn-block">Pending Orders</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation bar for order history and pending orders -->


@include('layouts.footer')

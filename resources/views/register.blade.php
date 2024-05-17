
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register to ShopPing</title>
    <link href="img/shopPINGLOGO.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>

<body>
    <style>
        :root {
            --primary-color: #6195b1;
            --secondary-color: #57B894;
            --black: #000000;
            --white: #f8c36c;
            --gray: #efefef;
            --gray-2: #757575;

            --facebook-color: #4267B2;
            --google-color: #DB4437;
            --twitter-color: #1DA1F2;
            --insta-color: #E1306C;
        }

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');


        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100vh;
            overflow: hidden;
        }

        .container11 {
            position: relative !important;
            min-height: 100vh !important;
            overflow: hidden !important;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            height: 100vh;
        }

        .col {
            width: 50%;
        }

        .align-items-center {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .form-wrapper {
            width: 100%;
            max-width: 28rem;
        }

        .form {
            padding: 1rem;
            background-color: var(--white);
            border-radius: 1.5rem;
            width: 100%;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            transform: scale(0);
            transition: .5s ease-in-out;
            transition-delay: 1s;
        }

        .input-group {
            position: relative;
            width: 100%;
            margin: 1rem 0;
        }

        small {
            padding: -2px -2px -2px -2px;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            font-size: 1.4rem;
            color: var(--gray-2);
        }

        .input-group input {
            width: 100%;
            padding: 1rem 3rem;
            font-size: 1rem;
            background-color: var(--gray);
            border-radius: .5rem;
            border: 0.125rem solid var(--white);
            outline: none;
        }

        .input-group input:focus {
            border: 0.125rem solid var(--primary-color);
        }

        .form button {
            cursor: pointer;
            width: 100%;
            padding: .6rem 0;
            border-radius: .5rem;
            border: none;
            background-color: var(--primary-color);
            color: var(--white);
            font-size: 1.2rem;
            outline: none;
        }

        .form p {
            margin: 1rem 0;
            font-size: .7rem;
        }

        .flex-col {
            flex-direction: column;
        }

        .social-list {
            margin: 2rem 0;
            padding: 1rem;
            border-radius: 1.5rem;
            width: 100%;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            transform: scale(0);
            transition: .5s ease-in-out;
            transition-delay: 1.2s;
        }

        .social-list>div {
            color: var(--white);
            margin: 0 .5rem;
            padding: .7rem;
            cursor: pointer;
            border-radius: .5rem;
            cursor: pointer;
            transform: scale(0);
            transition: .5s ease-in-out;
        }

        .social-list>div:nth-child(1) {
            transition-delay: 1.4s;
        }

        .social-list>div:nth-child(2) {
            transition-delay: 1.6s;
        }

        .social-list>div:nth-child(3) {
            transition-delay: 1.8s;
        }

        .social-list>div:nth-child(4) {
            transition-delay: 2s;
        }

        .social-list>div>i {
            font-size: 1.5rem;
            transition: .4s ease-in-out;
        }

        .social-list>div:hover i {
            transform: scale(1.5);
        }

        .facebook-bg {
            background-color: var(--facebook-color);
        }

        .google-bg {
            background-color: var(--google-color);
        }

        .twitter-bg {
            background-color: var(--twitter-color);
        }

        .insta-bg {
            background-color: var(--insta-color);
        }

        .pointer {
            cursor: pointer;
        }

        .container1.sign-in .form.sign-in,
        .container1.sign-in .social-list.sign-in,
        .container1.sign-in .social-list.sign-in>div,
        .container1.sign-up .form.sign-up,
        .container1.sign-up .social-list.sign-up,
        .container1.sign-up .social-list.sign-up>div {
            transform: scale(1);
        }

        .content-row {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 6;
            width: 100%;
        }

        .text {
            margin: 4rem;
            color: var(--white);
        }

        .text h2 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 2rem 0;
            transition: 1s ease-in-out;
        }

        .text p {
            font-weight: 600;
            transition: 1s ease-in-out;
            transition-delay: .2s;
        }

        .img img {
            width: 30vw;
            transition: 1s ease-in-out;
            transition-delay: .4s;
        }

        .text.sign-in h2,
        .text.sign-in p,
        .img.sign-in img {
            transform: translateX(-250%);
        }

        .text.sign-up h2,
        .text.sign-up p,
        .img.sign-up img {
            transform: translateX(250%);
        }

        .container1.sign-in .text.sign-in h2,
        .container1.sign-in .text.sign-in p,
        .container1.sign-in .img.sign-in img,
        .container1.sign-up .text.sign-up h2,
        .container1.sign-up .text.sign-up p,
        .container1.sign-up .img.sign-up img {
            transform: translateX(0);
        }

        /* BACKGROUND */

        .container1::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            height: 100vh;
            width: 300vw;
            transform: translate(35%, 0);
            background-image: linear-gradient(-45deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transition: 1s ease-in-out;
            z-index: 6;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            border-bottom-right-radius: max(50vw, 50vh);
            border-top-left-radius: max(50vw, 50vh);
        }

        .container1.sign-in::before {
            transform: translate(0, 0);
            right: 50%;
        }

        .container1.sign-up::before {
            transform: translate(100%, 0);
            right: 50%;
        }

        /* RESPONSIVE */

        @media only screen and (max-width: 425px) {

            .container1::before,
            .container1.sign-in::before,
            .container1.sign-up::before {
                height: 100vh;
                border-bottom-right-radius: 0;
                border-top-left-radius: 0;
                z-index: 0;
                transform: none;
                right: 0;
            }

            /* .container1.sign-in .col.sign-up {
        transform: translateY(100%);
     */

            .container1.sign-in .col.sign-in,
            .container1.sign-up .col.sign-up {
                transform: translateY(0);
            }

            .content-row {
                align-items: flex-start !important;
            }

            .content-row .col {
                transform: translateY(0);
                background-color: unset;
            }

            .col {
                width: 100%;
                position: absolute;
                padding: 2rem;
                background-color: var(--white);
                border-top-left-radius: 2rem;
                border-top-right-radius: 2rem;
                transform: translateY(100%);
                transition: 1s ease-in-out;
            }

            .row {
                align-items: flex-end;
                justify-content: flex-end;
            }

            .form,
            .social-list {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }

            .text {
                margin: 0;
            }

            .text p {
                display: none;
            }

            .text h2 {
                margin: .5rem;
                font-size: 2rem;
            }
        }

        .hidden {
            display: none;
        }
    </style>

    <div id="container1" class="container1">
        <!-- FORM SECTION -->
        <div class="row">
            <!-- SIGN UP -->
            <div class="col align-items-center flex-col sign-up">
                <div class="logo mb-4">
                    <img src="img/shopPINGLOGO.png" style="max-height: 130px; max-width: 130px;" alt="Logo">
                </div>
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    <input type="hidden" name="action" value="register">
                    <div class="form-wrapper align-items-center">
                        <div class="form sign-up">
                            <div class="input-group">
                                <i class='icon-user'></i>
                                <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                    value="{{ old('username') }}" type="text" name="username"
                                    placeholder="Username">
                            </div>
                            <small class="text-danger">{{ $errors->first('username') }}</small>

                            <div class="input-group">
                                <i class='bx bx-mail-send'></i>
                                <input type="text"
                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    value="{{ old('email') }}" name="email" placeholder="Email">
                            </div>
                            <small class="text-danger">{{ $errors->first('email') }}</small>

                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input type="password" name="password"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password">
                            </div>
                            <small class="text-danger">{{ $errors->first('password') }}</small>

                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input type="password" name="password_confirmation" placeholder="Confirm password">
                            </div>
                            <button type="submit">
                                Register
                            </button>
                            <p>
                                <span>
                                    Already have an account?
                                </span>
                                <b onclick="redirect(); toggle();" class="pointer">
                                    Sign in here
                                </b>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END SIGN UP -->
            <!-- SIGN IN -->

            <div class="col align-items-center flex-col sign-in">
                <!-- Logo -->
                <div class="logo mb-4">
                    <img src="img/shopPINGLOGO.png" style="max-height: 130px; max-width: 130px;" alt="Logo">
                </div>

                {{-- <form action="{{ route('login') }}" method="post">
                    @csrf
                    <!-- Sign-in Form -->
                    @if ($errors->has('text'))
                        <div class="alert alert-danger">
                            {{ $errors->first('text') }}
                        </div>
                    @endif
                    @error('email')
                        <div class="email-error alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-wrapper align-items-center">
                        <div class="form sign-in">
                            <div class="input-group">
                                <i class='bx bxs-user'></i>
                                <input type="text" placeholder="Email" name="emailLogin">
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                            <input type="password" placeholder="Password" name="passwordLogin">
                            </div>
                            <button onclick="window.location.hr" type="submit">
                                Sign in
                            </button>
                            <p>
                                <b>
                                    Forgot password?
                                </b>
                            </p>
                            <p>
                                <span>
                                    Don't have an account?
                                </span>
                                <button onclick="redirect(); toggle();" class="pointer">
                                    Sign up here
                                </button>
                            </p>
                        </div>
                    </div>
                </form> --}}
            </div>

            <!-- END SIGN IN -->
        </div>
        <!-- END FORM SECTION -->


        <!-- CONTENT SECTION -->
        <div class="row content-row">
            <!-- SIGN IN CONTENT -->
            <div class="col align-items-center flex-col">
                {{-- <div class="text sign-in">
                    <h2>
                        Welcome
                    </h2>
                </div> --}}
                <div class="img sign-in">

                </div>
            </div>
            <!-- END SIGN IN CONTENT -->
            <!-- SIGN UP CONTENT -->
            <div class="col align-items-center flex-col">
                <div class="img sign-up">

                </div>
                <div class="text sign-up">
                    <h2>
                        Join with us!
                    </h2>

                </div>
            </div>
            <!-- END SIGN UP CONTENT -->
        </div>
        <!-- END CONTENT SECTION -->
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let container1 = document.getElementById('container1');
            let signInText = document.querySelector('.text.sign-in');

            toggle = () => {
                var emailError = document.querySelector('.email-error');
                if (emailError) {
                    emailError.remove();
                }
                var hasError =
                    {{ $errors->has('usernameRegister') || $errors->has('emailRegister') || $errors->has('passwordRegister') ? 'true' : 'false' }};
                if (hasError) {
                    location.reload();
                }
                container1.classList.toggle('sign-in');
                container1.classList.toggle('sign-up');
            };

            setTimeout(() => {
                container1.classList.add('sign-in');
            }, 200);

            var hasError =
                {{ $errors->has('username') || $errors->has('email') || $errors->has('password') ? 'true' : 'false' }};
            if (hasError) {
                container1.classList.add('sign-up');
                container1.classList.remove('sign-in');
                signInText.classList.add('hidden');
            }

           toggle();

        });
        redirect = () => {
            location.href = '{{ route('login') }}';
        };

    </script>

</body>

</html>

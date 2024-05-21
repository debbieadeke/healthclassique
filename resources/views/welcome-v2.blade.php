<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets-v2/img/favicon.png') }}">
    <title>HealthClassique</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-v2/css/bootstrap.min.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('assets-v2/css/feather.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets-v2/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-v2/plugins/fontawesome/css/all.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-v2/css/style.css') }}">
</head>

<body>

<!-- Main Wrapper -->
<div class="main-wrapper login-body">
    <div class="container-fluid px-0">
        <div class="row">

            <!-- Login logo -->
            <div class="col-lg-6 login-wrap">
                <div class="login-sec">
                    <div class="log-img">
                        <div class="tbg"></div>
                        <img class="img-fluid" src="{{ asset('assets-v2/img/login-02.png') }}" alt="Logo">
                    </div>
                </div>
            </div>
            <!-- /Login logo -->

            <!-- Login Content -->
            <div class="col-lg-6 login-wrap-bg">
                <div class="login-wrapper">
                    <div class="loginbox">
                        <div class="login-right">
                            <div class="login-right-wrap">
                                <div class="account-logo">
                                    <a href="{{ url('/') }}"><img class="w-100" height="100"
                                                                  src="{{ asset('assets-v2/img/login-logo.png') }}" alt=""
                                                                  style="object-fit: contain;"></a>
                                </div>
                                <h2>Welcome Back</h2>
                                <!-- Form -->
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="input-block">
                                        <label>Email <span class="login-danger">*</span></label>
                                        <input type="email" id="email" aria-describedby="emailHelp" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="input-block">
                                        <label>Password <span class="login-danger">*</span></label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror pass-input" name="password" required autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                        <span class="profile-views feather-eye-off toggle-password"></span>
                                    </div>
                                    <div class="forgotpass">
                                        <div class="remember-me">
                                            <label
                                                class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
                                                <input type="checkbox" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <a href="{{ url('forgot-password.html') }}">Forgot Password?</a>
                                    </div>
                                    <div class="input-block login-btn">
                                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                                    </div>
                                </form>
                                <!-- /Form -->

                                <div class="next-sign">

                                </div>
                                <div class="text-center mt-3">
                                    <small>By continuing, you agree to the <a href="#">Terms of Sale, Terms of
                                            Service</a> , and <a href="#">Privacy Policy</a>.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /Login Content -->

        </div>
    </div>
</div>
<!-- /Main Wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets-v2/js/jquery-3.7.1.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('assets-v2/js/bootstrap.bundle.min.js') }}"></script>

<!-- Feather Js -->
<script src="{{ asset('assets-v2/js/feather.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets-v2/js/app.js') }}"></script>

</body>

</html>

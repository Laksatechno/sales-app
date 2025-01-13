<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <title>{{ config('app.name') }}</title>
    <meta name="theme-color" content="#FF396F">
    <meta name="msapplication-navbutton-color" content="#FF396F">
    <meta name="apple-mobile-web-app-status-bar-style" content="#FF396F">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ url('/content/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ url('/content/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('/content/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('content/favicon.png') }}">

    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ config('app.meta_description') }}">
    <meta name="keywords" content="{{ config('app.meta_keywords') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta http-equiv="Copyright" content="{{ config('app.name') }}">
    <meta name="copyright" content="{{ config('app.name') }}">
    <meta itemprop="image" content="content/meta-tag.jpg">

    <link rel="stylesheet" href="{{ url('/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('/assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    
</head>
<body>
    <div id="appCapsule">

        <div class="section mt-2 ">
                    <div class="card">
                        <div class="card-header">{{ __('Reset Password') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                    <div class="col-md-12">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="form-button-group transparent">
                                        <button type="submit" class="btn btn-primary btn btn-block">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
        </div>
        </div>
</div>
</body>
</html>

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
    
    @if(request()->is('history'))
        <link rel="stylesheet" href="{{ url('/assets/js/plugins/datepicker/datepicker3.css') }}">
        <link rel="stylesheet" href="{{ url('/assets/js/plugins/datatables/dataTables.bootstrap.css') }}">
        <link rel="stylesheet" href="{{ url('/assets/js/plugins/magnific-popup/magnific-popup.css') }}">
    @endif
</head>

<body>
    <div class="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <div id="appCapsule">
        <div class="section mt-2">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Register') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="name">{{ __('Nama Institusi') }}</label>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="jenis_institusi">Jenis Institusi</label>
                                        <select name="jenis_institusi" id="jenis_institusi" class="form-control @error('jenis_institusi') is-invalid @enderror">
                                            <option value="">Pilih Jenis Institusi</option>
                                            <option value="non-pmi" {{ old('jenis_institusi') == 'non-pmi' ? 'selected' : '' }}>NON PMI</option>
                                            <option value="pmi" {{ old('jenis_institusi') == 'pmi' ? 'selected' : '' }}>PMI</option>
                                        </select>
                                        @error('jenis_institusi')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="tipe_pelanggan">Tipe Pelanggan</label>
                                        <select name="tipe_pelanggan" id="tipe_pelanggan" class="form-control @error('tipe_pelanggan') is-invalid @enderror">
                                            <option value="">Pilih Tipe Pelanggan</option>
                                            <option value="Reguler" {{ old('tipe_pelanggan') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                            <option value="Subdis" {{ old('tipe_pelanggan') == 'Subdis' ? 'selected' : '' }}>Subdis</option>
                                        </select>
                                        @error('tipe_pelanggan')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="marketing_id">Marketing</label>
                                        <select name="marketing_id" id="marketing_id" class="form-control @error('marketing_id') is-invalid @enderror">
                                            <option value="">Pilih Marketing</option>
                                            @foreach ($marketings as $marketing)
                                                <option value="{{ $marketing->id }}" {{ old('marketing_id') == $marketing->id ? 'selected' : '' }}>{{ $marketing->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('marketing_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="no_hp">No Hp</label>
                                        <input id="no_hp" type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp') }}" autocomplete="no_hp">
                                        @error('no_hp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="alamat">Alamat</label>
                                        <input id="alamat" type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" value="{{ old('alamat') }}" autocomplete="alamat">
                                        @error('alamat')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="password">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label" for="password-confirm">{{ __('Konfirmasi Password') }}</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group boxed">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ url('/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('/assets/js/feather.min.js') }}"></script>
    <script src="{{ url('/assets/js/script.js') }}"></script>
    @if(request()->is('history'))
        <script src="{{ url('/assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ url('/assets/js/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ url('/assets/js/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
        <script src="{{ url('/assets/js/plugins/magnific-popup/magnific-popup.js') }}"></script>
    @endif
</body>
</html>
@extends('layouts.auth')

@section('main-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('img/Login.png') }}" alt="Login Image" class="img-fluid mb-4 rounded hover-scale" style="max-height: 200px; object-fit: contain;">

                        <h2 class="fw-bold mb-3" style="color: #112D4E;">Selamat Datang</h2>
                        <p class="text-muted mb-4">Silakan masuk ke akun Anda</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger border-left-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="text-start">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email :</label>
                            <input type="email" class="form-control focus-border" name="email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password :</label>
                            <input type="password" class="form-control focus-border" name="password" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>

                        <button type="submit" class="btn w-100 btn-hover" style="background-color: #3db718; color: white;">Login</button>
                    </form>

                    <hr class="my-4">

                    @if (Route::has('password.request'))
                        <div class="text-center mb-2">
                            <a class="small text-decoration-none text-muted" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        </div>
                    @endif

                    @if (Route::has('register'))
                        <div class="text-center">
                            <a class="small text-decoration-none text-muted" href="{{ route('register') }}">
                                Belum punya akun? Daftar sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-scale {
    transition: transform 0.3s ease;
}
.hover-scale:hover {
    transform: scale(1.05);
}

.focus-border:focus {
    border-color: #3F72AF;
    box-shadow: 0 0 0 0.2rem rgba(63, 114, 175, 0.25);
}

.btn-hover:hover {
    background-color: #305c91 !important;
    color: white;
}

.form-label {
    text-align: left;
    display: block;
    font-weight: 500;
    color: #112D4E;
}
</style>
@endsection

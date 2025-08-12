@extends('layouts.auth')

@section('main-content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card border-0 shadow-lg" style="border-radius: 12px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <!-- Company Logo -->
                        <img src="{{ asset('img/Logo CV. ASS.png') }}" alt="Company Logo" class="img-fluid mb-6" style="max-height: 80px;">

                        <h2 class="fw-bold mb-3" style="color: #1A237E;">DASHBOARD LOGIN</h2>
                        <p class="text-muted">Access your company dashboard</p>
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

                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #1A237E;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-envelope-fill" style="color: #D32F2F;"></i>
                                </span>
                                <input type="email" class="form-control industrial-input" name="email" value="{{ old('email') }}" required autofocus placeholder="company@email.com">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold" style="color: #1A237E;">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-lock-fill" style="color: #D32F2F;"></i>
                                </span>
                                <input type="password" class="form-control industrial-input" name="password" required placeholder="••••••••">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember" style="color: #1A237E;">Remember me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="small text-decoration-none" href="{{ route('password.request') }}" style="color: #D32F2F;">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn w-100 industrial-btn py-3 fw-bold">
                            LOGIN TO DASHBOARD
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Don't Have an account?</p>
                        @if (Route::has('register'))
                            <a class="text-decoration-none fw-semibold" href="{{ route('register') }}" style="color: #1A237E;">
                                Register in Here! <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small">© {{ date('Y') }} Raffi Elendiaz. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #ffffff;
        background-image: linear-gradient(to bottom, #f5f7fa 0%, #ffffff 100%);
        min-height: 100vh;
    }

    .industrial-input {
        border-left: 0;
        padding: 12px 15px;
        height: 50px;
    }

    .industrial-input:focus {
        border-color: #1A237E;
        box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.15);
    }

    .industrial-btn {
        background: linear-gradient(135deg, #1A237E 0%, #283593 100%);
        color: white;
        border: none;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .industrial-btn:hover {
        background: linear-gradient(135deg, #D32F2F 0%, #B71C1C 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.2);
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    @media (max-width: 768px) {
        .card {
            border-radius: 0;
            border: none;
            box-shadow: none;
        }

        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
</style>
@endsection

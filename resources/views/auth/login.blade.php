<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Antrian Bengkel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-login {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            background: #ffffff;
        }
        .login-header {
            background: #2563eb;
            color: white;
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
            position: relative;
        }
        .login-header .icon-box {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            backdrop-filter: blur(5px);
        }
        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
        }
        .input-group-text {
            border-radius: 0.5rem 0 0 0.5rem;
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
        }
        .btn-primary {
            background-color: #2563eb;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .input-group .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5 col-xl-4">

            <div class="card card-login">
                <!-- Header Kartu -->
                <div class="login-header">
                    <div class="icon-box">
                        <i class="fas fa-wrench fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-1">SIP-BENGKEL</h4>
                    <p class="small text-white-50 mb-0">Sistem Informasi & Antrian Servis</p>
                </div>

                <!-- Body Form -->
                <div class="card-body p-4 p-sm-5">

                    <!-- Alert jika ada pesan error dari session/validation -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger small mb-3">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <!-- Input Email -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="admin@bengkel.com" required autofocus>
                            </div>
                        </div>

                        <!-- Input Password -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i> MASUK SISTEM
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('booking.create') }}" class="text-decoration-none small text-muted">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Form Booking
                        </a>
                    </div>

                </div>
            </div>

            <!-- Footer copyright -->
            <div class="text-center mt-4 text-white-50 small">
                &copy; {{ date('Y') }} Bengkel Motor. All rights reserved.
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

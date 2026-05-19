<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Divine Fresh Market</title>
    <!-- Local Bootstrap CSS (Linked for Offline Use) -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body { background-color: #f8f9fa; }
        .register-card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-divine { background-color: #28a745; color: white; border-radius: 25px; }
        .btn-divine:hover { background-color: #218838; color: white; }
        .brand-text { color: #28a745; font-weight: bold; }
    </style>
</head>
<body class="d-flex align-items-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card register-card p-4 my-5">
                    <div class="text-center mb-4">
                        <h2 class="brand-text">Join Divine Fresh Market</h2>
                        <p class="text-muted italic">"Eat Fresh, Stay Healthy"</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your name" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-divine btn-lg">Create Account</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p class="small">Already have an account? <a href="{{ route('login') }}" class="text-success">Log in here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Local Bootstrap JS (Linked for Offline Use) -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Background Image */
        body {
            background: url('{{ asset('images/train (4).jpg') }}') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(5px);
        }

        /* Centering the card */
        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card styling */
        .card {
            background: rgba(255, 255, 255, 0.9); /* Slight transparency */
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center login-container">
        <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h4 class="text-center mb-4">Admin Login</h4>
                
                <!-- Show error message if login fails -->
                @if ($errors->has('email') || $errors->has('password'))
                    <div class="alert alert-danger" role="alert">
                        Invalid email or password.
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login') }}" id="login-form">
                    <!-- CSRF Token -->
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">Remember me</label>
                    </div>

                    <!-- Forgot Password & Submit -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.password.request') }}" class="text-decoration-none small">Forgot password?</a>
                        <button type="submit" class="btn btn-primary">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

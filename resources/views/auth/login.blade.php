<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Divine Fresh Market</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 50px;">

    <h2>Divine Fresh Market - Login</h2>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf <div style="margin-bottom: 15px;">
            <label>Email Address:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Log In</button>
    </form>

    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a>.</p>

</body>
</html>

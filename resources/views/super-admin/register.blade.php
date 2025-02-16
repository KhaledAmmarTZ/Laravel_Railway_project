<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Register</title>
</head>
<body>
    <h2>Super Admin Registration</h2>
    
    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color: red;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('superadmin.register.submit') }}" method="POST">
        @csrf
        <label>Name:</label>
        <input type="text" name="admin_name" required><br>

        <label>Email:</label>
        <input type="email" name="admin_email" required><br>

        <label>Date of Birth:</label>
        <input type="date" name="admin_date_of_birth" required><br>

        <label>Phone Number:</label>
        <input type="text" name="admin_phoneNumber" required><br>

        <label>Place:</label>
        <input type="text" name="admin_place" required><br>

        <label>Password:</label>
        <input type="password" name="admin_password" required><br>

        <label>Confirm Password:</label>
        <input type="password" name="admin_password_confirmation" required><br>

        <label>NID:</label>
        <input type="text" name="admin_nid" required><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="{{ route('superadmin.login') }}">Login</a></p>
</body>
</html>

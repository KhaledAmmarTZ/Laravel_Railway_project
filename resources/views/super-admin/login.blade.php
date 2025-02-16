<!DOCTYPE html>
<html lang="en">
<head>
    <title>Super Admin Login</title>
</head>
<body>
    <h2>Super Admin Login</h2>
    <form method="POST" action="{{ route('superadmin.login.submit') }}">
        @csrf
        <div class="form-group">
            <label for="admin_email">Email</label>
            <input type="email" name="admin_email" id="admin_email" required>
        </div>
        <div class="form-group">
            <label for="admin_password">Password</label>
            <input type="password" name="admin_password" id="admin_password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    @if($errors->any())
        <p style="color: red;">{{ $errors->first() }}</p>
    @endif
</body>
</html>

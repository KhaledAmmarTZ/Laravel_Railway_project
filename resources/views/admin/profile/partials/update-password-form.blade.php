<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Update Password
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <!-- Password Update Form -->
        <form method="POST" action="{{ route('admin.password.update') }}" class="mt-6 space-y-6">
            <!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">

            <!-- Current Password -->
            <div class="mb-3">
                <label for="update_password_current_password" class="form-label">Current Password</label>
                <input type="password" id="update_password_current_password" name="current_password" class="form-control" autocomplete="current-password" required>
                <!-- Error message for current password -->
                <div class="invalid-feedback">
                    <!-- Display error messages for current password here -->
                </div>
            </div>

            <!-- New Password -->
            <div class="mb-3">
                <label for="update_password_password" class="form-label">New Password</label>
                <input type="password" id="update_password_password" name="password" class="form-control" autocomplete="new-password" required>
                <!-- Error message for new password -->
                <div class="invalid-feedback">
                    <!-- Display error messages for new password here -->
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                <!-- Error message for password confirmation -->
                <div class="invalid-feedback">
                    <!-- Display error messages for password confirmation here -->
                </div>
            </div>

            <!-- Save Button and Success Message -->
            <div class="d-flex justify-content-start align-items-center gap-4">
                <button type="submit" class="btn btn-primary">Save</button>

                <!-- Success message for password update -->
                {% if session status equals 'password-updated' %}
                    <p class="text-sm text-gray-600">
                        Saved.
                    </p>
                {% endif %}
            </div>
        </form>
    </section>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

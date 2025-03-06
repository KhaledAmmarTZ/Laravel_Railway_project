<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Information</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="mt-6 space-y-6">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required autofocus autocomplete="name">
                <!-- Error message for name -->
                <div class="invalid-feedback">
                    <!-- Display error messages for name here -->
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required autocomplete="adminname">
                <!-- Error message for email -->
                <div class="invalid-feedback">
                    <!-- Display error messages for email here -->
                </div>

                <!-- If email is unverified -->
                {% if admin has unverified email %}
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            Your email address is unverified.

                            <button type="button" class="btn btn-link" form="send-verification">
                                Click here to re-send the verification email.
                            </button>
                        </p>

                        {% if session status equals 'verification-link-sent' %}
                            <p class="mt-2 font-medium text-sm text-green-600">
                                A new verification link has been sent to your email address.
                            </p>
                        {% endif %}
                    </div>
                {% endif %}
            </div>

            <div class="d-flex justify-content-start align-items-center gap-4">
                <button type="submit" class="btn btn-primary">Save</button>

                <!-- Success message -->
                {% if session status equals 'profile-updated' %}
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

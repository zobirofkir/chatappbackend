<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <h1>Reset Your Password</h1>

        <form method="POST" action="{{ route('password.reset') }}">
            @csrf
            <input type="hidden" name="token" value="{{ request()->query('token') }}">
            <input type="hidden" name="email" value="{{ request()->query('email') }}">

            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <button type="submit">Reset Password</button>
            </div>

            @if (session('status'))
                <div class="notification {{ session('status') === 'password_reset_success' ? 'success' : 'error' }}">
                    @if (session('status') === 'password_reset_success')
                        Your password has been updated successfully.
                    @elseif (session('status') === 'password_reset_failed')
                        Failed to reset your password. Please try again.
                    @endif
                </div>
            @endif
        </form>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);

            fetch('{{ route('password.reset') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const message = document.createElement('div');
                message.className = 'notification ' + (data.status === 'password_reset_success' ? 'success' : 'error');
                message.textContent = data.status === 'password_reset_success' 
                    ? 'Your password has been updated successfully.' 
                    : 'Failed to reset your password. Please try again.';
                document.body.appendChild(message);

                // Set a timer to hide the notification
                setTimeout(() => {
                    message.classList.add('hidden');
                    setTimeout(() => message.remove(), 500); // Remove after fade-out
                }, 3000); // Notification visible for 3 seconds
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>

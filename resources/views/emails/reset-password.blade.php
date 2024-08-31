<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
            overflow: hidden; /* Ensure no scrollbar due to fixed position */
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            position: relative;
            z-index: 1; /* Ensure it is above the notification */
        }
        h1 {
            margin-top: 0;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .notification {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px;
            border-radius: 4px;
            z-index: 1000;
            transition: opacity 0.5s ease-out;
        }
        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .notification.hidden {
            opacity: 0;
        }
    </style>
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

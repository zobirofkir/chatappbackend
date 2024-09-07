<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #e2f1ff;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 360px;
            box-sizing: border-box;
        }
        h1 {
            margin-top: 0;
            font-size: 28px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: center
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        .form-group input {
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            color: #333;
            padding: 16px;
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .notification {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px;
            border-radius: 6px;
            z-index: 1000;
            transition: opacity 0.5s ease-out;
            max-width: 360px;
            box-sizing: border-box;
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

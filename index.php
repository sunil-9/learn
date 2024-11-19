<?php
session_start();

if(isset($_SESSION['username'])) {
    echo "<script>alert('You are already logged in!');</script>";
    header("Location: dashboard.php");
    exit();
}

$error = '';

if(isset($_GET['error'])) {
    switch($_GET['error']) {
        case 'invalid_password':
            $error = "Invalid password!";
            break;
        case 'user_not_found':
            $error = "User not found!";
            break;
        case 'username_taken':
            $error = "Username is already taken!";
            break;
        case 'registration_failed':
            $error = "Registration failed! Please try again.";
            break;
        case 'database_error':
            $error = "Database error! Please try again.";
            break;
        case 'password_mismatch':
            $error = "Passwords do not match!";
            break;
        case 'password_too_short':
            $error = "Password must be at least 6 characters long!";
            break;
        case 'empty_fields':
            $error = "All fields are required!";
            break;
        default:
            $error="";
            break;
    }
}

if(isset($_GET['signup']) && $_GET['signup'] == 'success') {
    $success = "Registration successful! You can now login.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            gap: 3rem;
            padding: 20px;
        }

        form {
            background: white;
            position: relative;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 320px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.9s;
        }

        button:hover {
            background-color: #0056b3;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }
        
        .error, .success {
            margin-top: 1rem;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
            text-align: center;
        }
        .error {
            color: #dc3545;
            margin-bottom: 1rem;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .success {
            margin-bottom: 1rem;
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        

    </style>
</head>
<body>
    <form action="login.php" method="post">
        <h2>Login</h2>
        <input type="text" name="username" placeholder="Enter your username" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
        <?php 
        
        if(isset($_GET['error']) && in_array($_GET['error'], ['invalid_password', 'user_not_found', 'database_error'])) {
            echo "<div class='error'>$error</div>";
        }
        ?>
    </form>

    <form action="signup.php" method="post">
        <h2>Sign Up</h2>
        <input type="text" name="username" placeholder="Enter your username" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <input type="password" name="confirm_password" placeholder="Confirm your password" required>
        <button type="submit">Sign Up</button>
        <?php 
        // Show signup-related errors
        if(isset($_GET['error']) && in_array($_GET['error'], ['username_taken', 'registration_failed', 'password_mismatch', 'password_too_short', 'empty_fields'])) {
            echo '<div class="error">'.$error.'</div>';
        }
       
        if(isset($_GET['signup']) && $_GET['signup'] == 'success') {
            echo '<div class="success">Registration successful! You can now login.</div>';
        }
        ?>
    </form>


</body>
</html>
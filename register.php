<?php session_start(); ?>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "user_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['signup'])) {
    $username = $_POST['signup_username'];
    $email = $_POST['signup_email'];
    $password = $_POST['signup_password'];
    $confirm = $_POST['signup_confirm'];

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('Username or Email already exists!');</script>";
            $check_stmt->close();
        } else {
            $check_stmt->close();
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed);
            if ($stmt->execute()) {
                echo "<script>alert('Account created successfully! Please login.'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('An error occurred. Please try again!');</script>";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .navbar { width: 100vw; background: #004466; color: white; padding: 15px 0; text-align: right; position: fixed; top: 0; left: 0; }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 30px;
            font-size: 18px;
            transition: color 0.2s;
        }
        .navbar a:hover {
            color: #ffaa00;
        }
        .register-container {
            padding: 40px 30px;
            border-radius: 25px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border: 1px solid #e0eafc;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #004466;
            margin-bottom: 30px;
        }
        label {
            color: #004466;
            font-weight: 600;
            margin-top: 18px;
            display: block;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border-radius: 8px;
            border: 1.5px solid #b2bec3;
            font-size: 16px;
            background: #f8fafc;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border: 1.5px solid #ffaa00;
            outline: none;
            background: #fffbe6;
        }
        button {
            margin-top: 25px;
            color: 1.5px solid #ffaa00;
            border: 1.5px solid #ffaa00;
            padding: 14px 0;
            width: 100%;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: background 0.3s, color 0.3s, box-shadow 0.2s;
        }
        button:hover {
            color: #ffaa00;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }
        .login-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #004466;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link:hover {
            color: #ffaa00;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Login</a>
    </div>
    <div class="register-container">
        <form class="form-box signup" method="POST">
            <h2>Sign Up</h2>
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="signup_username" required>
            </div>
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="signup_email" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="signup_password" required>
            </div>
            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" name="signup_confirm" required>
            </div>
            <button class="btn-signup" name="signup">Create Account</button>
            <div class="login-link">
                Already have an account? <a href="index.php">Login</a>
            </div>
        </form>
    </div>
</body>
</html>

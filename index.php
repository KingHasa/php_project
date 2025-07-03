<?php
session_start();
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
                echo "<script>alert('Account created successfully!');</script>";
            } else {
                echo "<script>alert('An error occurred. Please try again!');</script>";
            }
            $stmt->close();
        }
    }
}
if (isset($_POST['login'])) {
    $usernameOrEmail = $_POST['login_username'];
    $password = $_POST['login_password'];
    if ($usernameOrEmail === 'mahmudulhasan37311@gmail.com' && $password === '172633Aa.') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php');
        exit();
    }
    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($username, $hashed);
        $stmt->fetch();
        if (password_verify($password, $hashed)) {
            $_SESSION['username'] = $username;
            header("Location: project.html");
            exit();
        } else {
            echo "<script>alert('Wrong password!');</script>";
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        .navbar a, .navbar form { display: inline-block; margin-right: 30px; }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            margin-right: 30px;
            transition: color 0.2s;
        }
        .navbar a:hover {
            color: #ffaa00;
        }
        .navbar button { background: #fff; color: #004466; border: none; padding: 7px 18px; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .navbar button:hover { background: #004466; color: #fff; border: 1px solid #fff; }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-box {
            width: 300px;
            padding: 20px;
            border-radius: 15px;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .login {
            background-color: #ffffff;
        }
        .login-container {
            background: linear-gradient(135deg, #fffbe6 0%, #e0eafc 100%);
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
        .input-group {
            margin-bottom: 10px;
        }
        .input-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border-radius: 8px;
            border: 1.5px solid #b2bec3;
            font-size: 16px;
            background: #f8fafc;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border: 1.5px solid #ffaa00;
            outline: none;
            background: #fffbe6;
        }
        .remember {
            display: flex;
            align-items: center;
        }
        .remember input {
            margin-right: 5px;
        }
        button {
            margin-top: 25px;
            color: #004466;
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
        .signup-link {
            text-align: center;
            margin-top: 15px;
        }
        .signup-link a {
            color: #90537e;
            text-decoration: none;
        }
        .forgot {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #90537e;
        }
        .register-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #004466;
            text-decoration: none;
            font-weight: 500;
        }
        .register-link:hover {
            color: #ffaa00;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <?php if (isset($_SESSION['username'])): ?>
            <span style="margin-right:20px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <form action="logout.php" method="post" style="display:inline;">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <a href="register.php">Sign Up</a>
        <?php endif; ?>
    </div>
    <div class="container">
        <form class="form-box login" method="POST">
            <div class="login-container">
                <h2>Login</h2>
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="login_username" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="login_password" required>
                </div>
                <div class="remember">
                    <input type="checkbox"> Remember me
                </div>
                <button class="btn btn-login" name="login">Log In</button>
                <div class="signup-link">
                    Don't have an account? <a href="register.php">Sign Up</a>
                </div>
                <p class="forgot">
                    <a href="forgot_password.php" style="text-decoration:none; color:#90537e;">Forgot password?</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "user_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        header("Location: reset_password.php?email=$email");
        exit();
    } else {
        $message = "This email is not registered.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
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

        .forgot-container {
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

        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border-radius: 8px;
            border: 1.5px solid #b2bec3;
            font-size: 16px;
            background: #f8fafc;
            transition: border 0.2s;
        }

        input[type="email"]:focus {
            border: 1.5px solid #ffaa00;
            outline: none;
            background: #fffbe6;
        }

        button {
            margin-top: 25px;
            background: linear-gradient(90deg, #ffaa00 60%, #004466 100%);
            color: #fff;
            border: none;
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
            background: linear-gradient(90deg, #004466 60%, #ffaa00 100%);
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

        .message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="forgot-container">
        <h2>Forgot Password</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Enter your registered email:</label>
            <input type="email" name="email" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>

</body>
</html>

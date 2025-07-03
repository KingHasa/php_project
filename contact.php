<?php
session_start();
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $date = date('Y-m-d H:i:s');
    if ($name && $email && $message) {
        $line = str_replace(["|", "\n", "\r"], ["-", " ", " "], $name) . '|' .
                str_replace(["|", "\n", "\r"], ["-", " ", " "], $email) . '|' .
                str_replace(["|", "\n", "\r"], ["-", " ", " "], $message) . '|' .
                $date . "\n";
        if (file_put_contents('consultations.txt', $line, FILE_APPEND | LOCK_EX) !== false) {
            $_SESSION['contact_success'] = true;
            header('Location: contact.php');
            exit;
        } else {
            $error = 'Failed to save your message. Please try again later.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
if (isset($_SESSION['contact_success'])) {
    $success = true;
    unset($_SESSION['contact_success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Adjust UR Future</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            margin: 0;
            padding: 0;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: linear-gradient(90deg, #002233 60%, #ffaa00 100%);
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
            letter-spacing: 1px;
        }
        .logo span {
            color: #ffaa00;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            transition: color 0.2s;
        }
        nav ul li a:hover {
            color: #ffaa00;
        }
        .contact-container {
            max-width: 500px;
            margin: 60px auto;
            background: linear-gradient(135deg, #fffbe6 0%, #e0eafc 100%);
            padding: 40px 30px;
            border-radius: 25px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border: 1px solid #e0eafc;
        }
        h1 {
            color: #004466;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: 1px;
        }
        label {
            display: block;
            margin-top: 18px;
            color: #004466;
            font-weight: 600;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border-radius: 8px;
            border: 1.5px solid #b2bec3;
            font-size: 16px;
            background: #f8fafc;
            transition: border 0.2s;
        }
        input:focus, textarea:focus {
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
        .admin-info {
            font-size: 18px;
            color: #004466;
            margin-bottom: 25px;
            background: #e0eafc;
            border-left: 5px solid #ffaa00;
            padding: 15px 20px;
            border-radius: 10px;
        }
        @media screen and (max-width: 768px) {
            nav {
                flex-direction: column;
                text-align: center;
            }
            nav ul {
                flex-direction: column;
                padding-top: 10px;
            }
            nav ul li {
                margin: 10px 0;
            }
            .contact-container {
                padding: 20px 10px;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Adjust <span>UR </span>Future</div>
        <ul>
            <li><a href="cv_create.php">CV Creating Help</a></li>
            <li><a href="mcq_exam.php">Preparation</a></li>
            <li><a href="job.php">Job Search</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
    </nav>
    <div class="contact-container">
        <h1>Contact Us</h1>
        <div class="admin-info">
            <strong>Admin:</strong> Mahmudul Hasan<br>
            <strong>Email:</strong> mahmudulhasan37311@gmail.com<br>
            <strong>Contact Number:</strong> 01893986707
        </div>
        <?php if ($success): ?>
            <div style="color: #27ae60; background: #eafaf1; border-left: 5px solid #27ae60; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px;">Your message has been sent successfully!</div>
        <?php elseif ($error): ?>
            <div style="color: #c0392b; background: #fdecea; border-left: 5px solid #c0392b; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>

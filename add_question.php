<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "job_finder");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $opt1 = $_POST['option1'];
    $opt2 = $_POST['option2'];
    $opt3 = $_POST['option3'];
    $opt4 = $_POST['option4'];
    $correct = $_POST['correct_option'];
    $stmt = $conn->prepare("INSERT INTO questions (question, option1, option2, option3, option4, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $question, $opt1, $opt2, $opt3, $opt4, $correct);
    if ($stmt->execute()) {
        echo "<script>alert('Question added successfully');</script>";
    } else {
        echo "<script>alert('Failed to add question');</script>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add MCQ Question</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 500px; margin: 50px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.10); padding: 35px 30px; }
        h2 { text-align: center; color: #007BFF; margin-bottom: 30px; }
        label { font-weight: bold; color: #333; }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="submit"] {
            width: 100%;
            background: #007BFF;
            color: #fff;
            border: none;
            padding: 12px 0;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-bottom: 18px;
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_dashboard.php" class="back-link">&larr; Back to Dashboard</a>
        <h2>Add MCQ Question (Admin Panel)</h2>
        <form method="POST">
            <label>Question:</label>
            <input type="text" name="question" required>
            <label>Option 1:</label>
            <input type="text" name="option1" required>
            <label>Option 2:</label>
            <input type="text" name="option2" required>
            <label>Option 3:</label>
            <input type="text" name="option3" required>
            <label>Option 4:</label>
            <input type="text" name="option4" required>
            <label>Correct Option (1-4):</label>
            <input type="number" name="correct_option" min="1" max="4" required>
            <input type="submit" value="Add Question">
        </form>
    </div>
</body>
</html>

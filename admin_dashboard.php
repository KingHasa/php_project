<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}
$conn = new mysqli("localhost", "root", "", "job_finder");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_job'])) {
    $job_title = $_POST['job_title'];
    $job_url = $_POST['job_url'];
    $category_id = $_POST['category_id'];
    if (!empty($job_title) && !empty($job_url) && !empty($category_id)) {
        $stmt = $conn->prepare("INSERT INTO job_links (job_name, job_link, category_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $job_title, $job_url, $category_id);
        if ($stmt->execute()) {
            header('Location: admin_dashboard.php?job_added=1');
            exit();
        } else {
            echo '<script>alert("Failed to add job.");</script>';
        }
        $stmt->close();
    } else {
        echo '<script>alert("Please fill all fields.");</script>';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        if ($stmt->execute()) {
            header('Location: admin_dashboard.php?category_added=1');
            exit();
        } else {
            echo '<script>alert("Failed to add category. It may already exist.");</script>';
        }
        $stmt->close();
    } else {
        echo '<script>alert("Please enter a category name.");</script>';
    }
}
if (isset($_GET['delete_question'])) {
    $qid = intval($_GET['delete_question']);
    $conn->query("DELETE FROM questions WHERE id = $qid");
    header('Location: admin_dashboard.php?question_deleted=1');
    exit();
}
if (isset($_GET['delete_job'])) {
    $job_id = intval($_GET['delete_job']);
    $conn->query("DELETE FROM job_links WHERE id = $job_id");
    header('Location: admin_dashboard.php?job_deleted=1');
    exit();
}
if (isset($_GET['delete_consultation'])) {
    $del_index = intval($_GET['delete_consultation']);
    $file = 'consultations.txt';
    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (isset($lines[$del_index])) {
            unset($lines[$del_index]);
            file_put_contents($file, implode("\n", $lines) . (count($lines) ? "\n" : ""));
        }
    }
    header('Location: admin_dashboard.php?consultation_deleted=1');
    exit();
}
$category_result = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); padding: 30px; }
        h1 { color: #007BFF; text-align: center; }
        form { margin-bottom: 30px; }
        label { font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #007BFF; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .logout-btn { float: right; background: #ffaa00; color: #002233; margin-top: -50px; }
        .create-question-btn { display: inline-block; margin-bottom: 30px; background: #007BFF; color: #fff; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-size: 18px; font-weight: bold; }
        .dashboard-container { margin-top: 40px; }
        .consultation-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .consultation-table th, .consultation-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .consultation-table th { background: #007BFF; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_GET['job_added'])): ?>
            <script>alert('Job added successfully!');</script>
        <?php endif; ?>
        <?php if (isset($_GET['category_added'])): ?>
            <script>alert('Category added successfully!');</script>
        <?php endif; ?>
        <?php if (isset($_GET['question_deleted'])): ?>
            <script>alert('Question deleted successfully!');</script>
        <?php endif; ?>
        <?php if (isset($_GET['job_deleted'])): ?>
            <script>alert('Job deleted successfully!');</script>
        <?php endif; ?>
        <?php if (isset($_GET['consultation_deleted'])): ?>
            <script>alert('Consultation message deleted successfully!');</script>
        <?php endif; ?>
        <form action="logout.php" method="post" style="text-align:right; margin-bottom:0;">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
        <h1>Admin Dashboard</h1>
        <form method="POST">
            <h2>Add a Job</h2>
            <label>Job Title:</label>
            <input type="text" name="job_title" required>
            <label>Job URL:</label>
            <input type="url" name="job_url" required>
            <label>Category:</label>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php
                $category_result2 = $conn->query("SELECT * FROM categories");
                while ($cat = $category_result2->fetch_assoc()) {
                    echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</option>';
                }
                ?>
            </select>
            <button type="submit" name="add_job">Add Job</button>
        </form>
        <form method="POST" style="margin-bottom:30px;">
            <h2>Add a Category</h2>
            <label>Category Name:</label>
            <input type="text" name="category_name" required>
            <button type="submit" name="add_category">Add Category</button>
        </form>
        <a href="add_question.php" class="create-question-btn">Create Question</a>
        <button type="button" onclick="document.getElementById('jobs-table').style.display = (document.getElementById('jobs-table').style.display === 'none' ? 'block' : 'none');" style="background:#28a745; color:#fff; margin-bottom:20px; padding:12px 28px; border-radius:6px; font-size:18px; font-weight:bold; border:none; cursor:pointer;">Show Jobs</button>
        <div id="jobs-table" style="display:none; margin-bottom:40px;">
            <h2 style="color:#28a745;">All Jobs</h2>
            <table style="width:100%; border-collapse:collapse; background:#fff;">
                <tr style="background:#28a745; color:#fff;">
                    <th style="padding:10px;">ID</th>
                    <th style="padding:10px;">Job Title</th>
                    <th style="padding:10px;">Job URL</th>
                    <th style="padding:10px;">Category</th>
                    <th style="padding:10px;">Action</th>
                </tr>
                <?php
                $jobs_res = $conn->query("SELECT job_links.id, job_links.job_name, job_links.job_link, categories.name AS category_name FROM job_links LEFT JOIN categories ON job_links.category_id = categories.id ORDER BY job_links.id DESC");
                while ($job = $jobs_res->fetch_assoc()) {
                    echo '<tr style="border-bottom:1px solid #eee;">';
                    echo '<td style="padding:8px;">' . $job['id'] . '</td>';
                    echo '<td style="padding:8px;">' . htmlspecialchars($job['job_name']) . '</td>';
                    echo '<td style="padding:8px;"><a href="' . htmlspecialchars($job['job_link']) . '" target="_blank">' . htmlspecialchars($job['job_link']) . '</a></td>';
                    echo '<td style="padding:8px;">' . htmlspecialchars($job['category_name']) . '</td>';
                    echo '<td style="padding:8px;"><a href="admin_dashboard.php?delete_job=' . $job['id'] . '" onclick="return confirm(\'Are you sure you want to delete this job?\');" style="color:#fff;background:#d9534f;padding:6px 14px;border-radius:4px;text-decoration:none;font-weight:bold;">Delete</a></td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
        <div class="dashboard-container">
        <h1>User Consultation</h1>
        <table class="consultation-table">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php
            // Read consultations from a file (consultations.txt)
            $file = 'consultations.txt';
            if (file_exists($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $idx => $line) {
                    $data = explode('|', $line);
                    if (count($data) === 4) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($data[0]) . '</td>';
                        echo '<td>' . htmlspecialchars($data[1]) . '</td>';
                        echo '<td>' . nl2br(htmlspecialchars($data[2])) . '</td>';
                        echo '<td>' . htmlspecialchars($data[3]) . '</td>';
                        echo '<td><a href="admin_dashboard.php?delete_consultation=' . $idx . '" onclick="return confirm(\'Are you sure you want to delete this message?\');" style="color:#fff;background:#d9534f;padding:6px 14px;border-radius:4px;text-decoration:none;font-weight:bold;">Delete</a></td>';
                        echo '</tr>';
                    }
                }
            } else {
                echo '<tr><td colspan="5">No consultations found.</td></tr>';
            }
            ?>
        </table>
    </div>
    </div>
</body>
</html>

<?php
$conn = new mysqli("localhost", "root", "", "job_finder");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$category_sql = "SELECT * FROM categories";
$category_result = $conn->query($category_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Finder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            padding: 30px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
        }
        h1 {
            text-align: center;
            font-size: 40px;
            color: #333;
            margin-bottom: 40px;
        }
        .category {
            margin-bottom: 50px;
        }
        .category-title {
            font-size: 26px;
            color: #fff;
            background-color: #007BFF;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .jobs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
        }
        .job-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        .job-card a {
            text-decoration: none;
            color: #007BFF;
            font-size: 18px;
            font-weight: bold;
        }
        .job-card a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            h1 {
                font-size: 28px;
            }
            .category-title {
                font-size: 20px;
            }
            .job-card a {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Job Finder</h1>
    <?php
    if ($category_result->num_rows > 0) {
        while ($category = $category_result->fetch_assoc()) {
            echo '<div class="category">';
            echo '<div class="category-title">' . htmlspecialchars($category['name']) . '</div>';
            $jobs_sql = "SELECT * FROM job_links WHERE category_id = " . $category['id'];
            $jobs_result = $conn->query($jobs_sql);
            echo '<div class="jobs">';
            if ($jobs_result->num_rows > 0) {
                while ($job = $jobs_result->fetch_assoc()) {
                    echo '<div class="job-card">';
                    echo '<h3 style="margin-bottom:10px;">' . htmlspecialchars($job['job_name']) . '</h3>';
                    echo '<div style="margin-bottom:10px; color:#555; font-size:15px;">Category: ' . htmlspecialchars($category['name']) . '</div>';
                    echo '<a href="' . htmlspecialchars($job['job_link']) . '" target="_blank" style="display:inline-block;padding:8px 18px;background:#007BFF;color:#fff;border:none;border-radius:5px;text-decoration:none;font-weight:bold;">Go to Job</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No jobs available.</p>';
            }
            echo '</div></div>';
        }
    } else {
        echo "<p>No categories found.</p>";
    }
    $conn->close();
    ?>
</div>
</body>
</html>

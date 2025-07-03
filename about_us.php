<?php
<!-- filepath: c:\xampp\htdocs\my project\about_us.php -->
<?php
// You can add PHP logic here if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Adjust UR Future</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #002233;
            flex-wrap: wrap;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;  
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
            font-size: 16px;
        }
        .about-container {
            max-width: 700px;
            margin: 60px auto;
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }
        h1 {
            color: #004466;
            margin-bottom: 20px;
        }
        p {
            color: #333;
            line-height: 1.7;
        }
        .profile {
            margin-top: 30px;
            display: flex;
            align-items: center;
        }
        .profile img {
            width: 100px;
            border-radius: 50%;
            margin-right: 25px;
        }
        .profile-details {
            font-size: 18px;
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
            .about-container {
                padding: 20px 10px;
            }
            .profile {
                flex-direction: column;
                align-items: flex-start;
            }
            .profile img {
                margin-bottom: 15px;
                margin-right: 0;
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
            <li><a href="about_us.php">Contact Us</a></li>
        </ul>
    </nav>
    <div class="about-container">
        <h1>About Us</h1>
        <p>
            Welcome to Adjust UR Future!<br>
            I am <strong>[Your Name]</strong>, a passionate web developer and career consultant dedicated to helping individuals achieve their professional goals. With years of experience in web development and career guidance, I strive to provide the best tools and resources for your success.
        </p>
        <div class="profile">
            <img src="your-photo.jpg" alt="Your Photo">
            <div class="profile-details">
                <strong>[Your Name]</strong><br>
                Web Developer & Career Consultant<br>
                Email: your.email@example.com<br>
                Phone: +123-456-7890
            </div>
        </div>
    </div>
</body>
</html>
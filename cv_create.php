<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $summary = $_POST['summary'];
    $education_institution = $_POST['education_institution'];
    $education_result = $_POST['education_result'];
    $experience = $_POST['experience'];
    $skills = $_POST['skills'];
    $education_html = '';
    if (is_array($education_institution) && is_array($education_result)) {
        for ($i = 0; $i < count($education_institution); $i++) {
            $inst = htmlspecialchars($education_institution[$i]);
            $res = htmlspecialchars($education_result[$i]);
            if ($inst && $res) {
                $education_html .= "<div style='margin-bottom:6px;'><strong>Institution:</strong> $inst<br><strong>Result:</strong> $res</div>";
            }
        }
    } else {
        $education_html = "<div><strong>Institution:</strong> " . htmlspecialchars($education_institution) . "<br><strong>Result:</strong> " . htmlspecialchars($education_result) . "</div>";
    }
    $cv_html = "<div style='max-width:700px;margin:40px auto;padding:40px 30px;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.10);font-family:Arial,sans-serif;'>"
        . "<div style='border-bottom:2px solid #007BFF;padding-bottom:10px;margin-bottom:25px;'>"
        . "<h1 style='margin:0;color:#007BFF;font-size:2.5em;'>$name</h1>"
        . "<div style='color:#444;font-size:1.1em;'>$email | $phone | $address</div>"
        . "</div>"
        . "<div style='margin-bottom:20px;'><h2 style='color:#333;font-size:1.3em;margin-bottom:8px;'>Professional Summary</h2><div style='color:#222;font-size:1.1em;'>$summary</div></div>"
        . "<div style='margin-bottom:20px;'><h2 style='color:#333;font-size:1.3em;margin-bottom:8px;'>Education</h2><div style='color:#222;font-size:1.1em;'>$education_html</div></div>"
        . "<div style='margin-bottom:20px;'><h2 style='color:#333;font-size:1.3em;margin-bottom:8px;'>Experience</h2><div style='color:#222;font-size:1.1em;'>$experience</div></div>"
        . "<div><h2 style='color:#333;font-size:1.3em;margin-bottom:8px;'>Skills</h2><div style='color:#222;font-size:1.1em;'>$skills</div></div>"
        . "</div>";
    echo '<!DOCTYPE html><html><head><title>Download CV</title>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>';
    echo '</head><body>';
    echo '<div id="cv-content">' . $cv_html . '</div>';
    echo '<div style="text-align:center;"><button onclick="downloadPDF()" style="margin-top:30px;padding:10px 20px;font-size:18px;">Download as PDF</button></div>';
    echo '<script>
    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({unit: "pt", format: "a4"});
        doc.html(document.getElementById("cv-content"), {
            callback: function (doc) {
                doc.save("cv.pdf");
            },
            x: 20,
            y: 20,
            width: 555
        });
    }
    </script>';
    echo '</body></html>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CV Creating Help</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .cv-form { max-width: 500px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        h2 { text-align: center; color: #007BFF; }
        label { font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #007BFF; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 18px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <form class="cv-form" method="POST">
        <h2>CV Creating Help</h2>
        <label>Name:</label>
        <input type="text" name="name" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Phone:</label>
        <input type="text" name="phone" required>
        <label>Address:</label>
        <input type="text" name="address" required>
        <label>Professional Summary:</label>
        <textarea name="summary" rows="3" required></textarea>
        <div id="education-section">
            <label>Education Institution:</label>
            <input type="text" name="education_institution[]" required>
            <label>Education Result:</label>
            <input type="text" name="education_result[]" required>
        </div>
        <button type="button" onclick="addEducation()" style="margin-bottom:15px;background:#ffaa00;color:#222;">+ Add More Education</button>
        <label>Experience:</label>
        <textarea name="experience" rows="3" required></textarea>
        <label>Skills:</label>
        <textarea name="skills" rows="2" required></textarea>
        <button type="submit">Generate CV</button>
    </form>
    <script>
    function addEducation() {
        var section = document.getElementById('education-section');
        var html = '<label>Education Institution:</label><input type="text" name="education_institution[]" required>' +
                   '<label>Education Result:</label><input type="text" name="education_result[]" required>';
        section.insertAdjacentHTML('beforeend', html);
    }
    </script>
</body>
</html>

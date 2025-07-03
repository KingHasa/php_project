<?php
$conn = new mysqli('localhost', 'root', '', 'job_finder');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$questions = $conn->query("SELECT * FROM questions");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>MCQ Exam</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        .question-box {
            margin-bottom: 20px;
            padding: 20px;
            background: #e6f2ff;
            border-radius: 8px;
        }
        .question {
            font-size: 18px;
            font-weight: bold;
        }
        .options {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        .options li {
            background: #fff;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
        .correct {
            background-color: lightgreen !important;
        }
        .incorrect {
            background-color: lightcoral !important;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>MCQ Exam</h2>
    <?php while($row = $questions->fetch_assoc()): ?>
        <div class="question-box">
            <div class="question"><?= htmlspecialchars($row['question']) ?></div>
            <ul class="options">
                <?php for($i=1; $i<=4; $i++): ?>
                    <li onclick="checkAnswer(this, <?= $i ?>, <?= $row['correct_option'] ?>)">
                        <?= htmlspecialchars($row['option'.$i]) ?>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endwhile; ?>
</div>

<script>
function checkAnswer(clicked, selected, correct) {
    let options = clicked.parentNode.querySelectorAll("li");
    options.forEach((opt, index) => {
        opt.classList.remove("correct", "incorrect");
        if (index + 1 === correct) opt.classList.add("correct");
    });
    if (selected !== correct) {
        clicked.classList.add("incorrect");
    }
}
</script>
</body>
</html>

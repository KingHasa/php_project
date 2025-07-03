<?php
$conn = new mysqli("localhost", "root", "", "your_database_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $opt1 = $_POST['option1'];
    $opt2 = $_POST['option2'];
    $opt3 = $_POST['option3'];
    $opt4 = $_POST['option4'];
    $correct = $_POST['correct'];

    $stmt = $conn->prepare("INSERT INTO mcq_questions (question, option1, option2, option3, option4, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $question, $opt1, $opt2, $opt3, $opt4, $correct);
    $stmt->execute();

    echo "Question added successfully.";
}
?>

<form method="POST">
    <h2>Add MCQ Question</h2>
    <input type="text" name="question" placeholder="Question" required><br><br>
    <input type="text" name="option1" placeholder="Option 1" required><br>
    <input type="text" name="option2" placeholder="Option 2" required><br>
    <input type="text" name="option3" placeholder="Option 3" required><br>
    <input type="text" name="option4" placeholder="Option 4" required><br>
    <input type="number" name="correct" placeholder="Correct Option (1-4)" min="1" max="4" required><br><br>
    <button type="submit">Add Question</button>
</form>

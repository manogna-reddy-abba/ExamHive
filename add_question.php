<?php
session_start(); include 'db_connect.php';
if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='admin'){header("Location:login.php");exit();}
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $subject=$_POST['subject']; $question_text=$_POST['question_text'];
  $option_a=$_POST['option_a']; $option_b=$_POST['option_b']; $option_c=$_POST['option_c']; $option_d=$_POST['option_d'];
  $correct_answer=$_POST['correct_answer'];
  $sql="INSERT INTO questions (subject,question_text,option_a,option_b,option_c,option_d,correct_answer) VALUES ('$subject','$question_text','$option_a','$option_b','$option_c','$option_d','$correct_answer')";
  if($conn->query($sql)===TRUE){$success="Question added to <strong>$subject</strong> successfully!";}
  else{$error="Error: ".$conn->error;}
}
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add Question — ExamHive</title>
<link rel="stylesheet" href="style.css">
<style>.opts-grid{display:grid;grid-template-columns:1fr 1fr;gap:0 22px}</style>
</head><body>
<a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
<form method="POST" action="">
  <h2 style="margin-bottom:6px;">Add New Question</h2>
  <p style="margin-bottom:4px;">Add a question to the subject question bank.</p>
  <?php if(!empty($success)):?><div style="margin-top:18px;padding:12px 16px;background:#e6f9f0;color:#1a7a45;border-left:4px solid #2ecc71;border-radius:9px;font-size:0.9rem;font-weight:500;"><?=$success?></div><?php endif;?>
  <?php if(!empty($error)):?><div style="margin-top:18px;padding:12px 16px;background:#fef0f0;color:#c0392b;border-left:4px solid #e74c3c;border-radius:9px;font-size:0.9rem;font-weight:500;"><?=htmlspecialchars($error)?></div><?php endif;?>
  <label>Subject</label>
  <select name="subject" required>
    <option value="Math">Math</option><option value="Science">Science</option>
    <option value="History">History</option><option value="General">General Knowledge</option>
  </select>
  <label>Question Text</label>
  <textarea name="question_text" rows="3" placeholder="Type the question here..." required></textarea>
  <div class="opts-grid">
    <div><label>Option A</label><input type="text" name="option_a" placeholder="Option A" required></div>
    <div><label>Option B</label><input type="text" name="option_b" placeholder="Option B" required></div>
    <div><label>Option C</label><input type="text" name="option_c" placeholder="Option C" required></div>
    <div><label>Option D</label><input type="text" name="option_d" placeholder="Option D" required></div>
  </div>
  <label>Correct Answer</label>
  <select name="correct_answer">
    <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
  </select>
  <input type="submit" value="Save Question →">
</form>
</body></html>

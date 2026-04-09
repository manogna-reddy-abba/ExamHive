<?php
session_start(); include 'db_connect.php';
if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='admin'){header("Location:login.php");exit();}
$has_subject_col=$conn->query("SHOW COLUMNS FROM results LIKE 'subject'")->num_rows>0;
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Results — ExamHive</title>
<link rel="stylesheet" href="style.css">
<style>
body{display:block;padding:44px 20px 72px}
.wrap{max-width:900px;margin:0 auto}
.page-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:28px}
</style>
</head><body>
<div class="wrap">
  <div class="page-top">
    <div>
      <div class="brand" style="margin-bottom:10px;"><div class="brand-icon">📝</div><span class="brand-name">ExamHive</span></div>
      <h2>📊 Student Exam Results</h2>
    </div>
    <a href="admin_dashboard.php" class="btn" style="width:auto;margin-top:0;padding:11px 24px;font-size:0.88rem;">← Dashboard</a>
  </div>
  <table>
    <thead>
      <tr>
        <th>Student</th>
        <?php if($has_subject_col):?><th>Subject</th><?php endif;?>
        <th>Score</th>
        <th>Questions</th>
        <th>Performance</th>
        <th>Date &amp; Time</th>
      </tr>
    </thead>
    <tbody>
    <?php
    if($has_subject_col){
      $sql="SELECT users.username,results.score,results.total_questions,results.subject,results.exam_date FROM results JOIN users ON results.user_id=users.id ORDER BY results.exam_date DESC";
    } else {
      $sql="SELECT users.username,results.score,results.total_questions,results.exam_date FROM results JOIN users ON results.user_id=users.id ORDER BY results.exam_date DESC";
    }
    $result=$conn->query($sql);
    if($result->num_rows>0){
      while($row=$result->fetch_assoc()){
        $pct=$row['total_questions']>0?round(($row['score']/$row['total_questions'])*100):0;
        $pill_class=$pct>=80?'pill-green':($pct>=60?'pill-blue':'pill-red');
        echo "<tr>";
        echo "<td><strong>".htmlspecialchars($row['username'])."</strong></td>";
        if($has_subject_col){
          $subj=htmlspecialchars($row['subject']??'—');
          $tc='tag-'.strtolower($row['subject']??'general');
          echo "<td><span class='tag $tc'>$subj</span></td>";
        }
        echo "<td><strong>".$row['score']." / ".$row['total_questions']."</strong></td>";
        echo "<td>".$row['total_questions']."</td>";
        echo "<td><span class='pill $pill_class'>{$pct}%</span></td>";
        echo "<td style='color:#8a9498;font-size:0.86rem;'>".$row['exam_date']."</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='6' style='text-align:center;color:#8a9498;padding:28px;'>No exam results yet.</td></tr>";
    }
    ?>
    </tbody>
  </table>
</div>
</body></html>

<?php
session_start(); include 'db_connect.php';
if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='student'){header("Location:login.php");exit();}
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $user_id=$_SESSION['user_id'];
  $score=0; $total_questions=0;
  // Get the subject from the hidden field (we'll add it via referer or hidden input)
  $subject=isset($_POST['subject'])?mysqli_real_escape_string($conn,$_POST['subject']):'General';
  if(isset($_POST['answers'])){
    $total_questions=count($_POST['answers']);
    foreach($_POST['answers'] as $q_id=>$student_answer){
      $sql="SELECT correct_answer,subject FROM questions WHERE id=$q_id";
      $result=$conn->query($sql);
      if($result->num_rows>0){
        $row=$result->fetch_assoc();
        // Get subject from the first question if not posted
        if($subject==='General'&&!empty($row['subject'])){$subject=$row['subject'];}
        if($student_answer===$row['correct_answer']){$score++;}
      }
    }
  }
  // Save with subject column (ALTER TABLE needed - see instructions)
  // Try with subject first, fall back without if column doesn't exist
  $insert_sql="INSERT INTO results (user_id,score,total_questions,subject) VALUES ('$user_id','$score','$total_questions','$subject')";
  if(!$conn->query($insert_sql)){
    // Fallback: insert without subject column (old schema)
    $insert_sql="INSERT INTO results (user_id,score,total_questions) VALUES ('$user_id','$score','$total_questions')";
    $conn->query($insert_sql);
  }
} else {
  header("Location:student_dashboard.php"); exit();
}
$pct=$total_questions>0?round(($score/$total_questions)*100):0;
$grade_color=$pct>=80?'#2ecc71':($pct>=60?'#3E96F4':'#e74c3c');
$grade_label=$pct>=80?'Excellent! 🎉':($pct>=60?'Good Job! 👍':'Keep Practicing 💪');
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Results — ExamHive</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.result-card{background:var(--white);border:1px solid rgba(204,199,191,0.55);border-radius:var(--r);padding:44px 50px;max-width:480px;width:100%;box-shadow:var(--sh-md);position:relative;overflow:hidden;text-align:center;animation:fadeUp 0.5s cubic-bezier(0.34,1.4,0.64,1) both}
.result-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--blue),#8dc8ff)}
.chart-wrap{max-width:200px;margin:0 auto 26px}
.grade-label{font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:22px;color:var(--dark)}
</style>
</head><body>
<div class="result-card">
  <div class="brand" style="justify-content:center;margin-bottom:26px;">
    <div class="brand-icon">📝</div><span class="brand-name">ExamHive</span>
  </div>
  <h2 style="margin-bottom:4px;">Exam Complete!</h2>
  <p style="margin-bottom:6px;">Results for <strong><?=htmlspecialchars($_SESSION['username'])?></strong> — <span class="tag tag-<?=strtolower($subject)?>"><?=htmlspecialchars($subject)?></span></p>

  <div class="chart-wrap"><canvas id="scoreChart"></canvas></div>

  <div class="stat-row">
    <div class="stat-item">
      <div class="stat-num" style="color:<?=$grade_color?>"><?=$pct?>%</div>
      <div class="stat-label">Score</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" style="color:#2ecc71"><?=$score?></div>
      <div class="stat-label">Correct</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" style="color:#e74c3c"><?=($total_questions-$score)?></div>
      <div class="stat-label">Wrong</div>
    </div>
  </div>

  <div class="grade-label"><?=$grade_label?></div>
  <hr>
  <a href="student_dashboard.php" class="btn" style="margin-top:0;">← Back to Dashboard</a>
  <a href="leaderboard.php" class="btn btn-ghost" style="margin-top:10px;">🏆 View Leaderboard</a>
</div>
<script>
const ctx=document.getElementById('scoreChart').getContext('2d');
new Chart(ctx,{
  type:'doughnut',
  data:{
    labels:['Correct','Incorrect'],
    datasets:[{data:[<?=$score?>,<?=($total_questions-$score)?>],backgroundColor:['#3E96F4','#EDEEEB'],borderColor:['#3E96F4','#CCC7BF'],borderWidth:2}]
  },
  options:{responsive:true,cutout:'74%',plugins:{legend:{position:'bottom',labels:{font:{family:'DM Sans',size:12},padding:16,color:'#5f6b6f'}}}}
});
</script>
</body></html>

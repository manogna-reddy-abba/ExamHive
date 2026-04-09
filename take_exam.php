<?php
session_start(); include 'db_connect.php';
if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='student'){header("Location:login.php");exit();}
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Take Exam — ExamHive</title>
<link rel="stylesheet" href="style.css">
<style>
body{align-items:center;padding-top:80px}
.exam-header{background:var(--white);border:1px solid rgba(204,199,191,0.55);border-radius:var(--r);padding:26px 34px;max-width:740px;width:100%;margin-bottom:22px;box-shadow:var(--sh-sm);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;position:relative;overflow:hidden}
.exam-header::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--blue),#8dc8ff)}
.q-num{font-family:'Syne',sans-serif;font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#8a9498;margin-bottom:8px}
.submit-area{max-width:740px;width:100%;padding:22px 0 50px;display:flex;justify-content:center}
.submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;background:var(--dark);color:var(--white);padding:16px 52px;border:none;border-radius:var(--r-sm);cursor:pointer;font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;letter-spacing:0.02em;transition:all var(--t);box-shadow:0 4px 18px rgba(49,57,60,0.28)}
.submit-btn:hover{background:#1e2528;transform:translateY(-2px);box-shadow:0 8px 28px rgba(49,57,60,0.35)}
</style>
</head><body>
<div id="customAlert">⚠️ WARNING: Do not leave this tab!</div>
<div id="timerDisplay">⏱ 05:00</div>
<form method="POST" action="submit_exam.php" id="examForm">
<?php
$selected_subject=isset($_GET['subject'])?$_GET['subject']:'General';
?>
<div class="exam-header">
  <div>
    <div class="brand" style="margin-bottom:10px;"><div class="brand-icon">📝</div><span class="brand-name">ExamHive</span></div>
    <h2 style="margin-bottom:3px;"><?=htmlspecialchars($selected_subject)?> Exam</h2>
    <p style="margin:0;">Good luck, <strong><?=htmlspecialchars($_SESSION['username'])?></strong>! Answer all questions before time runs out.</p>
  </div>
  <span class="badge"><?=htmlspecialchars($selected_subject)?></span>
</div>
<?php
$sql="SELECT * FROM questions WHERE subject='$selected_subject' ORDER BY RAND() LIMIT 10";
$result=$conn->query($sql);
if($result->num_rows>0){
  $n=1;
  while($row=$result->fetch_assoc()){
    $qid=$row['id'];
    echo "<div class='question-card'>";
    echo "<div class='q-num'>Question $n of {$result->num_rows}</div>";
    echo "<h3>".htmlspecialchars($row['question_text'])."</h3>";
    foreach(['A','B','C','D'] as $opt){
      $k='option_'.strtolower($opt);
      $req=($opt==='A')?'required':'';
      echo "<label class='option-label'><input type='radio' name='answers[$qid]' value='$opt' $req><span class='option-key'>$opt</span><span>".htmlspecialchars($row[$k])."</span></label>";
    }
    echo "</div>";
    $n++;
  }
} else {
  echo "<div class='question-card'><p style='color:#e74c3c;text-align:center;padding:12px;'>No questions available for this subject yet.</p></div>";
}
?>
<div class="submit-area"><button type="submit" class="submit-btn">Submit Exam →</button></div>
</form>
<script>
let timeLeft=300,warnings=0;
const examForm=document.getElementById('examForm');
const alertBox=document.getElementById('customAlert');
const timerEl=document.getElementById('timerDisplay');
setInterval(function(){
  timeLeft--;
  let m=Math.floor(timeLeft/60),s=timeLeft%60;
  timerEl.innerHTML="⏱ "+m+":"+(s<10?"0"+s:s);
  if(timeLeft<=60){timerEl.style.borderColor="#ff4d4d";timerEl.style.color="#ff4d4d";}
  if(timeLeft<=0){examForm.submit();}
},1000);
document.addEventListener("visibilitychange",function(){
  if(document.hidden){
    warnings++;
    if(warnings===1){
      alertBox.style.display="block";
      alertBox.innerHTML="⚠️ WARNING 1/2: Stay on this page or your exam will be submitted!";
      setTimeout(()=>{alertBox.style.display="none";},4000);
    } else if(warnings>=2){examForm.submit();}
  }
});
</script>
</body></html>

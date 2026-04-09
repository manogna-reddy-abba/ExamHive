<?php
session_start();
if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='admin'){header("Location:login.php");exit();}
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard — ExamHive</title>
<link rel="stylesheet" href="style.css">
<style>
.dash-wrap{width:100%;max-width:520px;animation:fadeUp 0.5s cubic-bezier(0.34,1.4,0.64,1) both}
.dash-header{background:var(--white);border:1px solid rgba(204,199,191,0.55);border-radius:var(--r);padding:36px 44px 30px;box-shadow:var(--sh-md);position:relative;overflow:hidden;margin-bottom:18px}
.dash-header::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--blue),#8dc8ff)}
.admin-tag{display:inline-block;background:var(--dark);color:var(--white);font-family:'Syne',sans-serif;font-size:0.69rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:5px 14px;border-radius:50px;margin-bottom:14px}
.dash-card:nth-child(1){animation-delay:0.05s}
.dash-card:nth-child(2){animation-delay:0.10s}
.dash-card:nth-child(3){animation-delay:0.15s}
.dash-card:nth-child(4){animation-delay:0.20s}
</style>
</head><body>
<div class="dash-wrap">
  <div class="dash-header">
    <div class="brand" style="margin-bottom:18px;"><div class="brand-icon">📝</div><span class="brand-name">ExamHive</span></div>
    <div class="admin-tag">Admin Panel</div>
    <h1 style="font-size:1.7rem;margin-bottom:6px;">Hello, Admin 👋</h1>
    <p>Manage questions, view results, and generate AI content.</p>
  </div>
  <div class="dash-grid">
    <a href="add_question.php" class="dash-card"><span class="card-icon">✏️</span><span class="card-label">Add Question</span></a>
    <a href="view_results.php" class="dash-card"><span class="card-icon">📊</span><span class="card-label">View Results</span></a>
    <a href="ai_generator.php" class="dash-card"><span class="card-icon">✨</span><span class="card-label">AI Generator</span></a>
    <a href="manage_questions.php" class="dash-card"><span class="card-icon">📚</span><span class="card-label">Question Bank</span></a>
  </div>
  <div style="text-align:center;"><a href="logout.php" class="logout-link">⏻ &nbsp;Logout</a></div>
</div>
</body></html>

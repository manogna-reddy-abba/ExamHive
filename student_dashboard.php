<?php
session_start();
if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='student'){header("Location:login.php");exit();}
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard — ExamHive</title>
<link rel="stylesheet" href="style.css">
<style>
.dash-wrap{width:100%;max-width:520px;animation:fadeUp 0.5s cubic-bezier(0.34,1.4,0.64,1) both}
.dash-header{background:var(--white);border:1px solid rgba(204,199,191,0.55);border-radius:var(--r);padding:36px 44px 30px;box-shadow:var(--sh-md);position:relative;overflow:hidden;margin-bottom:20px}
.dash-header::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--blue),#8dc8ff)}
.subject-btn:nth-child(1){animation-delay:0.05s}.subject-btn:nth-child(2){animation-delay:0.10s}
.subject-btn:nth-child(3){animation-delay:0.15s}.subject-btn:nth-child(4){animation-delay:0.20s}
.lb-btn{display:flex;align-items:center;justify-content:center;gap:10px;background:var(--dark);color:var(--white)!important;border-radius:var(--r);padding:17px;font-family:'Syne',sans-serif;font-weight:700;font-size:0.92rem;text-decoration:none;margin-top:16px;transition:all var(--t);border:2px solid var(--dark)}
.lb-btn:hover{background:#1e2528;border-color:#1e2528;transform:translateY(-2px);box-shadow:0 8px 26px rgba(49,57,60,0.28);text-decoration:none;color:var(--white)!important}
</style>
</head><body>
<div class="dash-wrap">
  <div class="dash-header">
    <div class="brand" style="margin-bottom:18px;"><div class="brand-icon">📝</div><span class="brand-name">ExamHive</span></div>
    <h1 style="font-size:1.7rem;margin-bottom:6px;">Hello, <?=htmlspecialchars($_SESSION['username'])?>! 👋</h1>
    <p>Choose a subject below to begin your exam.</p>
  </div>
  <div class="section-label">Select Subject</div>
  <div class="subject-grid">
    <a href="take_exam.php?subject=Math" class="subject-btn"><span class="s-icon">🔢</span>Math</a>
    <a href="take_exam.php?subject=Science" class="subject-btn"><span class="s-icon">🔬</span>Science</a>
    <a href="take_exam.php?subject=History" class="subject-btn"><span class="s-icon">📜</span>History</a>
    <a href="take_exam.php?subject=General" class="subject-btn"><span class="s-icon">🌍</span>General</a>
  </div>
  <a href="leaderboard.php" class="lb-btn">🏆 &nbsp;View Global Leaderboard</a>
  <div style="text-align:center;"><a href="logout.php" class="logout-link">⏻ &nbsp;Logout</a></div>
</div>
</body></html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ExamHive — Online Examination System</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body{justify-content:center;min-height:100vh}
    .hero{text-align:center;max-width:540px;width:100%;animation:fadeUp 0.6s cubic-bezier(0.34,1.4,0.64,1) both}
    .hero-eyebrow{display:inline-block;background:var(--blue-lt);border:1px solid rgba(62,150,244,0.28);color:var(--blue-dk);font-family:'Syne',sans-serif;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;padding:6px 18px;border-radius:50px;margin-bottom:26px}
    .hero h1{margin-bottom:16px;font-size:clamp(2.4rem,6vw,3.4rem);line-height:1.1}
    .hero h1 span{color:var(--blue)}
    .hero p{margin-bottom:38px;font-size:1rem;max-width:400px;margin-left:auto;margin-right:auto}
    .hero-btns{display:flex;gap:14px;justify-content:center}
    .hero-btns .btn{width:auto;padding:14px 40px;margin-top:0;font-size:0.95rem}
    .dots{display:flex;justify-content:center;gap:7px;margin-top:44px}
    .dots span{width:8px;height:8px;border-radius:50%;background:var(--gray)}
    .dots span:first-child{background:var(--blue);width:22px;border-radius:4px}
    .features{display:flex;gap:20px;margin-top:44px;flex-wrap:wrap;justify-content:center}
    .feat{background:var(--white);border:1px solid rgba(204,199,191,0.5);border-radius:12px;padding:18px 20px;flex:1;min-width:130px;text-align:center;box-shadow:var(--sh-sm)}
    .feat .f-icon{font-size:1.5rem;margin-bottom:8px}
    .feat .f-label{font-family:'Syne',sans-serif;font-size:0.78rem;font-weight:700;letter-spacing:0.04em;color:var(--dark)}
  </style>
</head>
<body>
  <div class="hero">
    <div class="brand" style="justify-content:center;margin-bottom:36px;">
      <div class="brand-icon">📝</div>
      <span class="brand-name">ExamHive</span>
    </div>
    <div class="hero-eyebrow">Online Examination System</div>
    <h1>Test Knowledge,<br><span>Track Progress</span></h1>
    <p>A modern platform for students to take subject exams and admins to manage questions, view results, and generate AI-powered content.</p>
    <div class="hero-btns">
      <a href="login.php" class="btn">Sign In →</a>
      <a href="register.php" class="btn btn-ghost">Register</a>
    </div>
    <div class="features">
      <div class="feat"><div class="f-icon">🔢</div><div class="f-label">Math</div></div>
      <div class="feat"><div class="f-icon">🔬</div><div class="f-label">Science</div></div>
      <div class="feat"><div class="f-icon">📜</div><div class="f-label">History</div></div>
      <div class="feat"><div class="f-icon">🌍</div><div class="f-label">General</div></div>
    </div>
    <div class="dots"><span></span><span></span><span></span></div>
  </div>
</body>
</html>

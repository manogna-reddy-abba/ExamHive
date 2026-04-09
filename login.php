<?php
session_start(); include 'db_connect.php';
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $username=$_POST['username']; $password=$_POST['password'];
  $sql="SELECT * FROM users WHERE username='$username'";
  $result=$conn->query($sql);
  if($result->num_rows>0){
    $user=$result->fetch_assoc();
    if(password_verify($password,$user['password'])){
      $_SESSION['user_id']=$user['id']; $_SESSION['username']=$user['username']; $_SESSION['role']=$user['role'];
      header("Location:".($user['role']=='admin'?'admin_dashboard.php':'student_dashboard.php')); exit();
    } else { $error="Incorrect password. Please try again."; }
  } else { $error="No account found with that username."; }
}
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sign In — ExamHive</title>
<link rel="stylesheet" href="style.css">
</head><body>
<div class="brand" style="margin-bottom:8px;">
  <div class="brand-icon">📝</div><span class="brand-name">ExamHive</span>
</div>
<form method="POST" action="">
  <h2 style="margin-bottom:6px;">Welcome back</h2>
  <p style="margin-bottom:6px;">Sign in to your account to continue</p>
  <?php if(!empty($error)):?><div style="margin-top:18px;padding:12px 16px;background:#fef0f0;color:#c0392b;border-left:4px solid #e74c3c;border-radius:9px;font-size:0.9rem;font-weight:500;"><?=htmlspecialchars($error)?></div><?php endif;?>
  <label>Username</label>
  <input type="text" name="username" placeholder="Enter your username" required>
  <label>Password</label>
  <input type="password" name="password" placeholder="Enter your password" required>
  <input type="submit" value="Sign In →">
  <p style="text-align:center;margin-top:22px;font-size:0.9rem;color:#8a9498;">Don't have an account? <a href="register.php">Register here</a></p>
</form>
</body></html>

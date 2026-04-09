<?php
include 'db_connect.php';
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $username=$_POST['username'];
  $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
  $role=$_POST['role'];
  $sql="INSERT INTO users (username,password,role) VALUES ('$username','$password','$role')";
  if($conn->query($sql)===TRUE){$success="Account created! You can now <a href='login.php'>sign in</a>.";}
  else{$error="Error: ".$conn->error;}
}
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Register — ExamHive</title>
<link rel="stylesheet" href="style.css">
</head><body>
<div class="brand" style="margin-bottom:8px;">
  <div class="brand-icon">📝</div><span class="brand-name">ExamHive</span>
</div>
<form method="POST" action="">
  <h2 style="margin-bottom:6px;">Create Account</h2>
  <p style="margin-bottom:6px;">Fill in your details to get started</p>
  <?php if(!empty($success)):?><div style="margin-top:18px;padding:12px 16px;background:#e6f9f0;color:#1a7a45;border-left:4px solid #2ecc71;border-radius:9px;font-size:0.9rem;font-weight:500;"><?=$success?></div><?php endif;?>
  <?php if(!empty($error)):?><div style="margin-top:18px;padding:12px 16px;background:#fef0f0;color:#c0392b;border-left:4px solid #e74c3c;border-radius:9px;font-size:0.9rem;font-weight:500;"><?=htmlspecialchars($error)?></div><?php endif;?>
  <label>Username</label>
  <input type="text" name="username" placeholder="Choose a username" required>
  <label>Password</label>
  <input type="password" name="password" placeholder="Create a strong password" required>
  <label>Role</label>
  <select name="role"><option value="student">Student</option><option value="admin">Admin</option></select>
  <input type="submit" value="Create Account →">
  <p style="text-align:center;margin-top:22px;font-size:0.9rem;color:#8a9498;">Already have an account? <a href="login.php">Sign in</a></p>
</form>
</body></html>

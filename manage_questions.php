<?php
session_start(); include 'db_connect.php';
if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='admin'){header("Location:login.php");exit();}
if(isset($_GET['delete'])){
  $delete_id=$_GET['delete'];
  $conn->query("DELETE FROM questions WHERE id=$delete_id");
  header("Location:manage_questions.php"); exit();
}
$sql="SELECT * FROM questions ORDER BY subject,id DESC";
$result=$conn->query($sql);
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Question Bank — ExamHive</title>
<link rel="stylesheet" href="style.css">
<style>
body{display:block;padding:44px 20px 72px}
.wrap{max-width:1160px;margin:0 auto}
.page-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:28px}
.q-text{font-weight:500;color:var(--dark);line-height:1.5;font-size:0.91rem}
.opts{color:#6b7579;font-size:0.82rem;line-height:1.9;margin-top:5px}
.opts span{margin-right:12px}
.ans-box{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;background:var(--dark);color:var(--white);font-family:'Syne',sans-serif;font-weight:700;font-size:0.82rem}
table{width:100%}
</style>
</head><body>
<div class="wrap">
  <div class="page-top">
    <div>
      <div class="brand" style="margin-bottom:10px;"><div class="brand-icon">📝</div><span class="brand-name">ExamHive</span></div>
      <h2>📚 Question Bank</h2>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
      <a href="add_question.php" class="btn" style="width:auto;margin-top:0;padding:11px 22px;font-size:0.88rem;">+ Add Question</a>
      <a href="admin_dashboard.php" class="btn btn-ghost" style="width:auto;margin-top:0;padding:11px 22px;font-size:0.88rem;">← Dashboard</a>
    </div>
  </div>
  <table>
    <thead><tr><th>Subject</th><th>Question</th><th>Answer</th><th>Action</th></tr></thead>
    <tbody>
    <?php if($result->num_rows>0):?>
      <?php while($row=$result->fetch_assoc()):
        $subj=htmlspecialchars($row['subject']);
        $tc='tag-'.strtolower($row['subject']);
      ?>
      <tr>
        <td style="min-width:90px;"><span class="tag <?=$tc?>"><?=$subj?></span></td>
        <td>
          <div class="q-text"><?=htmlspecialchars($row['question_text'])?></div>
          <div class="opts">
            <span><strong>A</strong> <?=htmlspecialchars($row['option_a'])?></span>
            <span><strong>B</strong> <?=htmlspecialchars($row['option_b'])?></span>
            <span><strong>C</strong> <?=htmlspecialchars($row['option_c'])?></span>
            <span><strong>D</strong> <?=htmlspecialchars($row['option_d'])?></span>
          </div>
        </td>
        <td><span class="ans-box"><?=htmlspecialchars($row['correct_answer'])?></span></td>
        <td><a href="manage_questions.php?delete=<?=$row['id']?>" class="delete-btn" onclick="return confirm('Delete this question?');">Delete</a></td>
      </tr>
      <?php endwhile;?>
    <?php else:?>
      <tr><td colspan="4" style="text-align:center;color:#8a9498;padding:32px;">No questions yet. Add some to get started!</td></tr>
    <?php endif;?>
    </tbody>
  </table>
</div>
</body></html>

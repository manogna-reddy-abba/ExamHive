<?php
session_start(); include 'db_connect.php';
if(!isset($_SESSION['user_id'])){header("Location:login.php");exit();}

// Overall leaderboard
$sql_overall="SELECT u.username, SUM(r.score) AS total_score, SUM(r.total_questions) AS total_possible, COUNT(r.id) AS exams_taken
              FROM users u JOIN results r ON u.id=r.user_id
              WHERE u.role='student'
              GROUP BY u.id ORDER BY total_score DESC";
$overall=$conn->query($sql_overall);

// Per-subject leaderboards
$subjects=['Math'=>'🔢','Science'=>'🔬','History'=>'📜','General'=>'🌍'];
$subject_data=[];
foreach($subjects as $subj=>$emoji){
  // Check if subject column exists in results table
  $check=$conn->query("SHOW COLUMNS FROM results LIKE 'subject'");
  if($check&&$check->num_rows>0){
    $sql="SELECT u.username, SUM(r.score) AS total_score, SUM(r.total_questions) AS total_possible
          FROM users u JOIN results r ON u.id=r.user_id
          WHERE u.role='student' AND r.subject='$subj'
          GROUP BY u.id ORDER BY total_score DESC LIMIT 5";
  } else {
    // If no subject column, use question's subject via a join workaround — show message instead
    $sql=null;
  }
  $subject_data[$subj]=['emoji'=>$emoji,'query'=>$sql,'result'=>$sql?$conn->query($sql):null];
}

$has_subject_col=$conn->query("SHOW COLUMNS FROM results LIKE 'subject'")->num_rows>0;
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Leaderboard — ExamHive</title>
<link rel="stylesheet" href="style.css">
<style>
body{display:block;padding:44px 20px 72px}
.lb-wrap{max-width:780px;margin:0 auto}
.page-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:30px}
.overall-card{background:var(--white);border:1px solid rgba(204,199,191,0.55);border-radius:var(--r);box-shadow:var(--sh-md);overflow:hidden;position:relative;margin-bottom:32px;animation:fadeUp 0.5s cubic-bezier(0.34,1.4,0.64,1) both}
.overall-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--blue),#8dc8ff)}
.card-head{padding:26px 32px 20px;border-bottom:1px solid var(--silver)}
.tabs{display:flex;gap:2px;background:var(--silver);border-radius:10px;padding:4px;border:1px solid var(--gray);margin-bottom:24px}
.tab-btn{flex:1;padding:9px 10px;border:none;border-radius:7px;background:transparent;font-family:'Syne',sans-serif;font-size:0.76rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#8a9498;cursor:pointer;transition:all var(--t)}
.tab-btn.active{background:var(--white);color:var(--dark);box-shadow:var(--sh-sm)}
.tab-panel{display:none}.tab-panel.active{display:block}
.lb-subject-block{background:var(--white);border:1px solid rgba(204,199,191,0.55);border-radius:var(--r);overflow:hidden;margin-bottom:18px;box-shadow:var(--sh-sm);animation:fadeUp 0.4s cubic-bezier(0.34,1.4,0.64,1) both}
.lb-subj-head{padding:16px 24px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--silver);background:var(--silver)}
.lb-subj-head .s-em{font-size:1.3rem}
.lb-subj-head h3{font-size:0.93rem;font-weight:700;margin:0}
.no-data{padding:20px;text-align:center;color:#8a9498;font-size:0.9rem}
.setup-notice{background:linear-gradient(135deg,#fff8e1,#fff3c8);border:1px solid #f0d060;border-radius:12px;padding:22px 26px;margin-bottom:24px;font-size:0.9rem}
.setup-notice h3{color:#7a5800;font-size:1rem;margin-bottom:8px}
.setup-notice code{background:rgba(0,0,0,0.07);padding:2px 8px;border-radius:5px;font-family:monospace;font-size:0.88rem}
table{width:100%}
.pct-wrap{display:flex;align-items:center;gap:10px}
.pct-bar-bg{width:60px;height:5px;background:var(--silver);border-radius:3px;overflow:hidden;flex-shrink:0}
.pct-bar{height:5px;border-radius:3px;background:var(--blue)}
</style>
</head><body>
<div class="lb-wrap">
  <div class="page-top">
    <div>
      <div class="brand" style="margin-bottom:10px;"><div class="brand-icon">📝</div><span class="brand-name">ExamHive</span></div>
      <h2>🏆 Global Leaderboard</h2>
    </div>
    <a href="student_dashboard.php" class="btn" style="width:auto;margin-top:0;padding:11px 24px;font-size:0.88rem;">← Dashboard</a>
  </div>

  <?php if(!$has_subject_col):?>
  <div class="setup-notice">
    <h3>⚙️ One-time setup needed for Subject Leaderboard</h3>
    <p>To see per-subject scores, run this SQL in phpMyAdmin:<br><br>
    <code>ALTER TABLE results ADD COLUMN subject VARCHAR(50) DEFAULT 'General';</code><br><br>
    See the full setup guide below. Once done, subject scores will appear automatically.</p>
  </div>
  <?php endif;?>

  <!-- Tab navigation -->
  <div class="tabs">
    <button class="tab-btn active" onclick="switchTab('overall',this)">🌐 Overall</button>
    <button class="tab-btn" onclick="switchTab('math',this)">🔢 Math</button>
    <button class="tab-btn" onclick="switchTab('science',this)">🔬 Science</button>
    <button class="tab-btn" onclick="switchTab('history',this)">📜 History</button>
    <button class="tab-btn" onclick="switchTab('general',this)">🌍 General</button>
  </div>

  <!-- Overall Tab -->
  <div id="tab-overall" class="tab-panel active">
    <div class="overall-card">
      <div class="card-head">
        <h3 style="margin:0;font-size:1rem;">Overall Rankings — All Subjects Combined</h3>
        <p style="margin:4px 0 0;font-size:0.85rem;">Cumulative score across every exam taken</p>
      </div>
      <table>
        <thead><tr><th>Rank</th><th>Student</th><th>Exams</th><th>Overall %</th></tr></thead>
        <tbody>
        <?php
        $rank=1;
        if($overall&&$overall->num_rows>0){
          while($row=$overall->fetch_assoc()){
            $rc=''; $medal="<span style='font-family:Syne,sans-serif;font-weight:700;color:#9aa0a3;font-size:0.88rem;'>#$rank</span>";
            if($rank==1){$rc='rank-1';$medal='🥇';}
            if($rank==2){$rc='rank-2';$medal='🥈';}
            if($rank==3){$rc='rank-3';$medal='🥉';}
            $pct=$row['total_possible']>0?round(($row['total_score']/$row['total_possible'])*100,1):0;
            $bar_w=min($pct,100);
            echo "<tr class='$rc'><td style='text-align:center;font-size:1.1rem;'>$medal</td>";
            echo "<td><strong>".htmlspecialchars($row['username'])."</strong></td>";
            echo "<td><span class='pill pill-blue'>".$row['exams_taken']."</span></td>";
            echo "<td><div class='pct-wrap'><div class='pct-bar-bg'><div class='pct-bar' style='width:{$bar_w}%'></div></div><strong>{$pct}%</strong></div></td></tr>";
            $rank++;
          }
        } else {
          echo "<tr><td colspan='4' class='no-data'>No results yet. Take an exam to appear here!</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Subject Tabs -->
  <?php foreach($subjects as $subj=>$emoji):
    $tab_id='tab-'.strtolower($subj);
    $tag_class='tag-'.strtolower($subj);
  ?>
  <div id="<?=$tab_id?>" class="tab-panel">
    <div class="lb-subject-block">
      <div class="lb-subj-head">
        <span class="s-em"><?=$emoji?></span>
        <h3><?=$subj?> Rankings</h3>
        <span class="tag <?=$tag_class?>" style="margin-left:auto;"><?=$subj?></span>
      </div>
      <?php if(!$has_subject_col):?>
        <div class="no-data">⚙️ Run the SQL setup in phpMyAdmin to enable subject tracking. See the notice above.</div>
      <?php elseif($subject_data[$subj]['result']&&$subject_data[$subj]['result']->num_rows>0):?>
        <table>
          <thead><tr><th>Rank</th><th>Student</th><th>Score</th><th>Percentage</th></tr></thead>
          <tbody>
          <?php
          $sr=1; $res=$subject_data[$subj]['result'];
          while($row=$res->fetch_assoc()){
            $medal="<span style='font-family:Syne,sans-serif;font-weight:700;color:#9aa0a3;font-size:0.85rem;'>#$sr</span>";
            $rc='';
            if($sr==1){$rc='rank-1';$medal='🥇';}
            if($sr==2){$rc='rank-2';$medal='🥈';}
            if($sr==3){$rc='rank-3';$medal='🥉';}
            $pct=$row['total_possible']>0?round(($row['total_score']/$row['total_possible'])*100,1):0;
            $bar_w=min($pct,100);
            echo "<tr class='$rc'><td style='text-align:center;'>$medal</td>";
            echo "<td><strong>".htmlspecialchars($row['username'])."</strong></td>";
            echo "<td>".$row['total_score']." / ".$row['total_possible']."</td>";
            echo "<td><div class='pct-wrap'><div class='pct-bar-bg'><div class='pct-bar' style='width:{$bar_w}%'></div></div><strong>{$pct}%</strong></div></td></tr>";
            $sr++;
          }
          ?>
          </tbody>
        </table>
      <?php else:?>
        <div class="no-data">No <?=$subj?> exam results yet. Be the first to take this exam!</div>
      <?php endif;?>
    </div>
  </div>
  <?php endforeach;?>
</div>

<script>
function switchTab(name,btn){
  document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  document.getElementById('tab-'+name).classList.add('active');
  btn.classList.add('active');
}
</script>
</body></html>

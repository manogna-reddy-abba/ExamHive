<?php
session_start(); include 'db_connect.php';
if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='admin'){header("Location:login.php");exit();}
$apiKey="AIzaSyAXdA0Gyhm9Bwi_iBGoqrL5_J_Eh7dz9eU";
if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST['content'])){
  $text_content=mysqli_real_escape_string($conn,$_POST['content']);
  $subject=mysqli_real_escape_string($conn,$_POST['subject']);
  $url="https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=".$apiKey;
  $prompt="Context: $text_content. Create 5 MCQs for $subject. Return ONLY a valid JSON array. Do not include any markdown or text outside the array. Format: [{\"question_text\":\"...\",\"option_a\":\"...\",\"option_b\":\"...\",\"option_c\":\"...\",\"option_d\":\"...\",\"correct_answer\":\"A\"}]";
  $data=array("contents"=>array(array("parts"=>array(array("text"=>$prompt)))));
  $ch=curl_init($url);
  curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
  curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
  $response=curl_exec($ch); $curl_error=curl_error($ch); curl_close($ch);
  if($response===false){$ai_error="Connection Failed: $curl_error";}
  else{
    $result=json_decode($response,true);
    if(isset($result['candidates'][0]['content']['parts'][0]['text'])){
      $ai_text=$result['candidates'][0]['content']['parts'][0]['text'];
      $start=strpos($ai_text,'['); $end=strrpos($ai_text,']')+1;
      $json_data=substr($ai_text,$start,$end-$start);
      $questions=json_decode($json_data,true);
      if($questions){
        foreach($questions as $q){
          $q_text=mysqli_real_escape_string($conn,$q['question_text']);
          $oa=mysqli_real_escape_string($conn,$q['option_a']); $ob=mysqli_real_escape_string($conn,$q['option_b']);
          $oc=mysqli_real_escape_string($conn,$q['option_c']); $od=mysqli_real_escape_string($conn,$q['option_d']);
          $ans=mysqli_real_escape_string($conn,$q['correct_answer']);
          $conn->query("INSERT INTO questions (subject,question_text,option_a,option_b,option_c,option_d,correct_answer) VALUES ('$subject','$q_text','$oa','$ob','$oc','$od','$ans')");
        }
        $ai_success="5 AI-generated questions added to <strong>$subject</strong>!";
      } else {$ai_error="AI formatting error. Please try again.";}
    } else {$ai_error="Google API Error: ".print_r($result,true);}
  }
}
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>AI Generator — ExamHive</title>
<link rel="stylesheet" href="style.css">
<style>
.ai-badge{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#e8f3ff,#d4e9ff);border:1px solid rgba(62,150,244,0.25);color:var(--blue-dk);font-family:'Syne',sans-serif;font-size:0.72rem;font-weight:700;letter-spacing:0.09em;text-transform:uppercase;padding:6px 18px;border-radius:50px;margin-bottom:14px}
</style>
</head><body>
<a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
<form method="POST" action="">
  <div class="ai-badge">✨ Powered by Gemini AI</div>
  <h2 style="margin-bottom:6px;">AI Question Generator</h2>
  <p style="margin-bottom:4px;">Paste a passage and AI will generate 5 multiple-choice questions.</p>
  <?php if(!empty($ai_success)):?><div style="margin-top:18px;padding:12px 16px;background:#e6f9f0;color:#1a7a45;border-left:4px solid #2ecc71;border-radius:9px;font-size:0.9rem;font-weight:500;">✅ <?=$ai_success?></div><?php endif;?>
  <?php if(!empty($ai_error)):?><div style="margin-top:18px;padding:12px 16px;background:#fef0f0;color:#c0392b;border-left:4px solid #e74c3c;border-radius:9px;font-size:0.9rem;font-weight:500;">❌ <?=htmlspecialchars($ai_error)?></div><?php endif;?>
  <label>Subject</label>
  <select name="subject">
    <option value="Math">Math</option><option value="Science">Science</option>
    <option value="History">History</option><option value="General">General</option>
  </select>
  <label>Paste Your Passage</label>
  <textarea name="content" rows="9" placeholder="Paste your text passage here. AI will read it and generate relevant questions..." required></textarea>
  <input type="submit" value="✨ Generate 5 Questions">
</form>
</body></html>

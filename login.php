<?php
session_start();
require_once 'db.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === "" || $password === "") {
        $message = "กรุณากรอกชื่อผู้ใช้และรหัสผ่าน";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id,password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_bind_result($stmt, $uid, $hash);
            mysqli_stmt_fetch($stmt);
            if (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $uid;
                $_SESSION['username'] = $username;
                header("Location: /lostfound/index.php");
                exit;
            } else {
                $message = "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            $message = "ไม่พบบัญชีผู้ใช้";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>เข้าสู่ระบบ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="site-header">
    <div class="header-inner">
      <div class="logo"><div class="icon">✧</div><div class="title">Lost & Found</div></div>
      <div class="nav">
        <a class="header-btn" href="/lostfound/index.php">หน้าแรก</a>
        <a class="header-btn" href="/lostfound/register.php">สมัครสมาชิก</a>
      </div>
    </div>
  </div>

  <div class="wrapper">
    <div class="card" style="max-width:640px; margin:0 auto;">
      <h1>เข้าสู่ระบบ</h1>
      <?php if ($message): ?>
        <p style="color:#d6336c;"><strong><?php echo htmlspecialchars($message); ?></strong></p>
      <?php endif; ?>
      <form method="post">
        <div class="form-group">
          <label>ชื่อผู้ใช้</label>
          <input type="text" name="username" required>
        </div>
        <div class="form-group">
          <label>รหัสผ่าน</label>
          <input type="password" name="password" required>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">เข้าสู่ระบบ</button>
          <a class="btn btn-outline" href="/lostfound/register.php">สมัครสมาชิก</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
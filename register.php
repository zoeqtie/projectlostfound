<?php
session_start();
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = trim($_POST['fullname'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm_password'] ?? '';

  if ($fullname === '' || $username === '' || $password === '' || $confirm === '') {
    $message = 'กรุณากรอกข้อมูลให้ครบ';
  } elseif ($password !== $confirm) {
    $message = 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน';
  } else {
    // ป้องกัน SQL injection เบื้องต้น
    $username_safe = mysqli_real_escape_string($conn, $username);

    // เช็ค username ซ้ำ
    $q = "SELECT id FROM users WHERE username = '$username_safe' LIMIT 1";
    $r = mysqli_query($conn, $q);
    if (!$r) {
      $message = 'เกิดข้อผิดพลาดในการตรวจสอบชื่อผู้ใช้';
    } elseif (mysqli_num_rows($r) > 0) {
      $message = 'มีชื่อผู้ใช้นี้ในระบบแล้ว';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $fullname_safe = mysqli_real_escape_string($conn, $fullname);
      $sql = "INSERT INTO users (fullname, username, password) VALUES ('$fullname_safe', '$username_safe', '$hash')";
      if (mysqli_query($conn, $sql)) {
        // สมัครสำเร็จ -> ไปหน้า login
        header("Location: login.php?registered=1");
        exit;
      } else {
        $message = 'สมัครสมาชิกไม่สำเร็จ: ' . mysqli_error($conn);
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สมัครสมาชิก</title>
  <link rel="stylesheet" href="style.css"> <!-- สไตล์เดียวกัน -->
</head>
<body>
  <div class="wrapper">
    <header class="site-header">
      <div class="header-inner">
        <div class="logo">
          <div class="icon">🌻</div>
          <div class="title">ระบบของหายได้คืน</div>
        </div>
        <nav class="nav">
          <a class="header-btn" href="/lostfound/index.php">หน้าแรก</a>
          <a class="header-btn" href="/lostfound/login.php">เข้าสู่ระบบ</a>
          <a class="header-btn" href="/lostfound/register.php"><span class="badge">ใหม่</span> สมัคร</a>
        </nav>
      </div>
    </header>

    <main>
      <section class="card" style="max-width:480px;margin:120px auto 30px;">
        <h2 style="text-align:center;">สมัครสมาชิก 💜</h2>

        <?php if ($message): ?>
          <p style="text-align:center;color:#b0004b;font-weight:700;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post">
          <div class="form-group">
            <label>ชื่อ–สกุล</label>
            <input type="text" name="fullname" placeholder="ชื่อ-สกุล" required>
          </div>

          <div class="form-group">
            <label>ชื่อผู้ใช้ (Username)</label>
            <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
          </div>

          <div class="form-group">
            <label>รหัสผ่าน</label>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
          </div>

          <div class="form-group">
            <label>ยืนยันรหัสผ่าน</label>
            <input type="password" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" required>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
            <a href="login.php" class="btn btn-outline">มีบัญชีแล้ว? เข้าสู่ระบบ</a>
          </div>
        </form>

        <p style="text-align:center;margin-top:12px;"><a href="forgot.php">ลืมรหัสผ่าน?</a></p>
      </section>
    </main>
    <div class="site-footer">© ระบบของหายได้คืน</div>
  </div>
</body>
</html>
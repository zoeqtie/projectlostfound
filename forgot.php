<?php
session_start();
require_once 'db.php';

$step = 1;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $username_safe = mysqli_real_escape_string($conn, $username);
    $res = mysqli_query($conn, "SELECT id FROM users WHERE username='$username_safe' LIMIT 1");
    if ($res && mysqli_num_rows($res) === 1) {
      $row = mysqli_fetch_assoc($res);
      $_SESSION['reset_user_id'] = $row['id'];
      $step = 2;
    } else {
      $message = 'ไม่พบบัญชีผู้ใช้';
    }
  } elseif (isset($_POST['new_password'])) {
    $id = $_SESSION['reset_user_id'] ?? 0;
    $pw1 = $_POST['new_password'] ?? '';
    $pw2 = $_POST['confirm_password'] ?? '';
    if ($pw1 === '' || $pw2 === '') {
      $message = 'กรุณากรอกข้อมูลให้ครบ';
      $step = 2;
    } elseif ($pw1 !== $pw2) {
      $message = 'รหัสผ่านไม่ตรงกัน';
      $step = 2;
    } else {
      $hash = password_hash($pw1, PASSWORD_DEFAULT);
      $id = intval($id);
      $sql = "UPDATE users SET password='$hash' WHERE id=$id";
      if (mysqli_query($conn, $sql)) {
        unset($_SESSION['reset_user_id']);
        header("Location: login.php?reset=1");
        exit;
      } else {
        $message = 'อัพเดตรหัสผ่านไม่สำเร็จ';
        $step = 2;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ลืมรหัสผ่าน</title>
  <link rel="stylesheet" href="style.css">
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
        </nav>
      </div>
    </header>

    <main>
      <section class="card" style="max-width:480px;margin:120px auto 30px;">
        <h2 style="text-align:center;">กู้ / เปลี่ยนรหัสผ่าน</h2>

        <?php if ($message): ?>
          <p style="text-align:center;color:#b0004b;font-weight:700;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if ($step === 1): ?>
          <form method="post">
            <div class="form-group">
              <label>กรอกชื่อผู้ใช้ของคุณ</label>
              <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
            </div>
            <div class="form-actions">
              <button class="btn btn-primary" type="submit">ยืนยันชื่อผู้ใช้</button>
              <a href="login.php" class="btn btn-outline">ยกเลิก</a>
            </div>
          </form>
        <?php else: ?>
          <form method="post">
            <div class="form-group">
              <label>รหัสผ่านใหม่</label>
              <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
              <label>ยืนยันรหัสผ่านใหม่</label>
              <input type="password" name="confirm_password" required>
            </div>
            <div class="form-actions">
              <button class="btn btn-primary" type="submit">เปลี่ยนรหัสผ่าน</button>
              <a href="login.php" class="btn btn-outline">ยกเลิก</a>
            </div>
          </form>
        <?php endif; ?>
      </section>
    </main>
    <div class="site-footer">ระบบของหายได้คืน</div>
  </div>
</body>
</html>
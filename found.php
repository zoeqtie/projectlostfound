<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: /lostfound/login.php");
    exit;
}
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = trim($_POST['item_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $item_date = $_POST['item_date'] ?? '';
    $contact = trim($_POST['contact'] ?? '');
    if ($item_name === "" || $location === "" || $item_date === "" || $contact === "") {
        $message = "กรุณากรอกข้อมูลให้ครบ";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO items (type, item_name, description, location, item_date, contact) VALUES ('found',?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "sssss", $item_name, $description, $location, $item_date, $contact);
        if (mysqli_stmt_execute($stmt)) {
            $message = "บันทึกการแจ้งของพบสำเร็จ";
        } else {
            $message = "บันทึกไม่สำเร็จ: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>แจ้งของพบ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="site-header">
    <div class="header-inner">
      <div class="logo"><div class="icon">✧</div><div class="title">Lost & Found</div></div>
      <div class="nav">
        <a class="header-btn" href="/lostfound/index.php">หน้าแรก</a>
        <a class="header-btn" href="/lostfound/lost.php">แจ้งของหาย</a>
        <a class="header-btn" href="/lostfound/search.php">ค้นหาของ</a>
        <a class="header-btn" href="/lostfound/logout.php">ออกจากระบบ</a>
      </div>
    </div>
  </div>

  <div class="wrapper">
    <div class="card" style="max-width:720px; margin:0 auto;">
      <h1>แจ้งของพบ</h1>
      <?php if ($message): ?><p style="color:#d6336c;"><strong><?php echo htmlspecialchars($message); ?></strong></p><?php endif; ?>
      <form method="post">
        <div class="form-group"><label>ชื่อสิ่งของ</label><input type="text" name="item_name" required></div>
        <div class="form-group"><label>รายละเอียด / ลักษณะเด่น</label><textarea name="description"></textarea></div>
        <div class="form-group"><label>สถานที่ที่พบ</label><input type="text" name="location" required></div>
        <div class="form-group"><label>วันที่พบ</label><input type="date" name="item_date" required></div>
        <div class="form-group"><label>ช่องทางติดต่อผู้เก็บได้</label><input type="text" name="contact" required></div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">บันทึก</button>
          <a class="btn btn-outline" href="/lostfound/index.php">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
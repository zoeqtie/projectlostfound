<?php
session_start();
require_once 'db.php';

$q = trim($_GET['q'] ?? '');
$type = $_GET['type'] ?? 'all';

$sql = "SELECT * FROM items WHERE 1";
if ($type === 'lost' || $type === 'found') {
    $sql .= " AND type='" . ($type === 'lost' ? 'lost' : 'found') . "'";
}
if ($q !== '') {
    $q_safe = mysqli_real_escape_string($conn, $q);
    $sql .= " AND (item_name LIKE '%$q_safe%' OR location LIKE '%$q_safe%' OR contact LIKE '%$q_safe%')";
}
$sql .= " ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>ค้นหาของ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="site-header">
    <div class="header-inner">
      <div class="logo"><div class="icon">✧</div><div class="title">Lost & Found</div></div>
      <div class="nav">
        <a class="header-btn" href="/lostfound/index.php">หน้าแรก</a>
        <a class="header-btn" href="/lostfound/lost.php">แจ้งของหาย</a>
        <a class="header-btn" href="/lostfound/found.php">แจ้งของพบ</a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a class="header-btn" href="/lostfound/logout.php">ออกจากระบบ</a>
        <?php else: ?>
          <a class="header-btn" href="/lostfound/login.php">เข้าสู่ระบบ</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="wrapper">
    <div class="card" style="max-width:900px; margin:0 auto;">
      <h1>ค้นหาของหาย / ของพบ</h1>
      <form method="get">
        <div class="form-group">
          <label>คำค้นหา (ชื่อ / สถานที่ / เบอร์)</label>
          <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>">
        </div>
        <div class="form-group">
          <label>ประเภท</label>
          <select name="type">
            <option value="all" <?php if ($type==='all') echo 'selected'; ?>>ทั้งหมด</option>
            <option value="lost" <?php if ($type==='lost') echo 'selected'; ?>>ของหาย</option>
            <option value="found" <?php if ($type==='found') echo 'selected'; ?>>ของพบ</option>
          </select>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit">ค้นหา</button>
          <a class="btn btn-outline" href="/lostfound/index.php">กลับหน้าแรก</a>
        </div>
      </form>

      <div style="margin-top:16px;">
        <table class="table">
          <thead>
            <tr><th>ประเภท</th><th>ชื่อสิ่งของ</th><th>สถานที่</th><th>วันที่</th><th>ติดต่อ</th></tr>
          </thead>
          <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?php echo $row['type'] === 'lost' ? 'ของหาย' : 'ของพบ'; ?></td>
                  <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['location']); ?></td>
                  <td><?php echo htmlspecialchars($row['item_date']); ?></td>
                  <td><?php echo htmlspecialchars($row['contact']); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="5" style="text-align:center;">ไม่พบข้อมูล</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
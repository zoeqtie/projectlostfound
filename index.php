<?php
session_start();
require_once 'db.php';
$isLogin = isset($_SESSION['user_id']);

// ดึงรายการล่าสุด 5 รายการ (ถ้ามี)
$items = [];
$result = mysqli_query($conn, "SELECT * FROM items ORDER BY created_at DESC LIMIT 5");
if ($result) {
    while ($r = mysqli_fetch_assoc($result)) $items[] = $r;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ระบบของหายได้คืน - Kuromi Theme</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="site-header">
    <div class="header-inner">
      <div class="logo">
        <div class="icon">✧</div>
        <div class="title">LOSTFOUND</div>
      </div>

      <div class="nav">
        <a class="header-btn" href="/lostfound/index.php"><span class="badge">🏠</span> หน้าแรก</a>
        <a class="header-btn" href="/lostfound/lost.php"><span class="badge">🧸</span>แจ้งของหาย</a>
        <a class="header-btn" href="/lostfound/found.php"><span class="badge">🎁</span>แจ้งของพบ</a>
        <a class="header-btn" href="/lostfound/search.php"><span class="badge">🔎</span>ค้นหาของ</a>

        <?php if ($isLogin): ?>
          <a class="header-btn" href="/lostfound/logout.php">ออกจากระบบ</a>
        <?php else: ?>
          <a class="header-btn" href="/lostfound/login.php">เข้าสู่ระบบ</a>
          <a class="header-btn" href="/lostfound/register.php">สมัครสมาชิก</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="wrapper">
    <div class="card hero">
      <div class="hero-left">
        <h1>ระบบของหายได้คืน</h1>
        <p>บันทึกของหาย ของพบ และค้นหาเพื่อคืนของ</p>
        <div class="hero-buttons">
          <a class="btn btn-primary" href="/lostfound/lost.php">แจ้งของหาย</a>
          <a class="btn btn-secondary" href="/lostfound/found.php">แจ้งของพบ</a>
          <a class="btn btn-outline" href="/lostfound/search.php">ค้นหาของ</a>
        </div>
      </div>
      <div style="width:220px; text-align:center;">
        <div class="card" style="border-radius:14px;">
          <div style="font-weight:800; font-size:20px; color:#2a1324;">รายการล่าสุด</div>
          <div style="margin-top:10px; text-align:left;">
            <?php if (empty($items)): ?>
              <div style="text-align:center; color:#7a6a77;">ยังไม่มีรายการ</div>
            <?php else: ?>
              <ul style="list-style:none; padding:0; margin:0;">
                <?php foreach($items as $it): ?>
                  <li style="padding:8px 0; border-bottom:1px dashed rgba(0,0,0,0.04);">
                    <strong style="display:block;"><?php echo htmlspecialchars($it['item_name']); ?></strong>
                    <small style="color:#7a6a77;"><?php echo ($it['type']==='lost'?'ของหาย':'ของพบ').' • '.htmlspecialchars($it['location']); ?></small>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="corner"></div>
    </div>

    <div class="card">
      <h2>วิธีใช้งาน</h2>
      <ol style="padding-left:16px; color:#4b3b4b;">
        <li>สมัครสมาชิกและเข้าสู่ระบบเพื่อบันทึกรายการ (แนะนำ)</li>
        <li>เลือกเมนูแจ้งของหาย / แจ้งของพบ เพื่อบันทึกรายการ</li>
        <li>ค้นหาจากหน้า ค้นหาของ หากต้องการตรวจสอบ</li>
      </ol>
    </div>

    <footer class="site-footer">© ระบบของหายได้คืน</footer>
  </div>
</body>
</html>
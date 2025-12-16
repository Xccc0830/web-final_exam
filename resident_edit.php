<?php
// resident_edit.php (PDO 轉換版本)
include("header.php");
require_once 'db.php'; // 現在引入的是 $pdo

$id = $_GET['id'] ?? 0; // 預防 ID 不存在

if ($id) {
    // 1. 準備語句
    $stmt = $pdo->prepare("SELECT * FROM residents WHERE id = ?");
    
    // 2. 執行並綁定參數
    $stmt->execute([$id]);
    
    // 3. 取得單筆結果
    $resident = $stmt->fetch();
    
    if (!$resident) {
        die("未找到該住民資料。");
    }
} else {
    die("缺少住民 ID。");
}
?>

<div class="container mt-4">
    <h2>編輯住民資料</h2>

    <form method="POST" action="update.php"> 
        <input type="hidden" name="id" value="<?= htmlspecialchars($resident['id']) ?>">

        <div class="mb-3">
            <label class="form-label">學號</label>
            <input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($resident['student_id']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">姓名</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($resident['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">房號</label>
            <input type="text" name="room" class="form-control" value="<?= htmlspecialchars($resident['room']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">聯絡電話</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($resident['phone']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">更新資料</button>
        <a href="resident_list.php" class="btn btn-secondary">返回列表</a>
    </form>
</div>

<?php
include("footer.php");
// 註：PDO 連線不需要像 mysqli 一樣呼叫 $stmt->close() 和 $conn->close()，它會在腳本結束時自動關閉。
?>
<?php
// violation_create.php (PDO 轉換版本)

require_once("db.php");
include("header.php");

// 檢查是否為 Admin 身份
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='container mt-4'><div class='alert alert-danger'>您沒有權限存取此頁面。</div></div>";
    include("footer.php");
    exit;
}

// 預先查詢住民列表供表單選擇 (PDO SELECT)
try {
    // 【修正：第 6 行】將 $conn->query() 替換為 $pdo->query()
    $stmt_residents = $pdo->query("SELECT id, name, student_id, room FROM residents ORDER BY name ASC");
    $residents_list = $stmt_residents->fetchAll(); 
    
} catch (PDOException $e) {
    $residents_list = []; // 查詢失敗則設置為空陣列
    echo "<div class='alert alert-danger'>載入住民列表失敗: " . $e->getMessage() . "</div>";
}


// 新增違規 (POST 處理)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_POST['resident_id'] ?? 0;
    $violation = $_POST['violation'] ?? '';
    $points = $_POST['points'] ?? 0;

    // 基本驗證
    if (!$resident_id || empty($violation) || !is_numeric($points)) {
        $error_msg = "請填寫所有欄位。";
    } else {
        try {
            // 準備 INSERT 語句 (PDO 預備語句)
            $stmt = $pdo->prepare("INSERT INTO violations (resident_id, violation, points, created_at) VALUES (?, ?, ?, NOW())");
            
            // 執行並綁定參數
            $stmt->execute([$resident_id, $violation, $points]);

            // 成功後導向列表頁
            if ($stmt->rowCount() > 0) {
                 header("Location: violation_list_all.php?msg=add_success");
                 exit;
            } else {
                 $error_msg = "新增失敗，請檢查輸入。";
            }

        } catch (PDOException $e) {
            $error_msg = "資料庫錯誤: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-4">
    <h2>新增違規紀錄</h2>
    
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="violation_create.php">
        <div class="mb-3">
            <label class="form-label">選擇住民</label>
            <select name="resident_id" class="form-control" required>
                <option value="">請選擇住民</option>
                <?php 
                // 【PDO 修正】：使用 foreach 遍歷 $residents_list
                foreach($residents_list as $row): 
                ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (房號: <?= $row['room'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">違規內容</label>
            <input type="text" name="violation" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">扣除點數</label>
            <input type="number" name="points" class="form-control" min="1" value="5" required>
        </div>

        <button type="submit" class="btn btn-danger">新增違規</button>
        <a href="violation_list_all.php" class="btn btn-secondary">返回列表</a>
    </form>
</div>

<?php include("footer.php"); ?>
<?php
include("db.php");
// 確保 header.php 被包含在內，且已處理 session_start() 和權限檢查
include("header.php");

// 檢查是否為 Admin 身份，如果不是，則導向儀表板或顯示錯誤
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='container mt-4'><div class='alert alert-danger'>您沒有權限存取此頁面。</div></div>";
    include("footer.php");
    exit;
}

// 預先查詢住民列表供手動選擇
$residents_result = $conn->query("SELECT id, name, student_id, room FROM residents ORDER BY name");


// 處理簽到 POST 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 優先處理條碼掃描輸入
    $scanned_student_id = isset($_POST['scanned_student_id']) ? trim($_POST['scanned_student_id']) : '';
    $resident_id = '';
    $success_message = '簽到成功！';

    if (!empty($scanned_student_id)) {
        // 1. 透過學號查找 resident_id
        $stmt_find = $conn->prepare("SELECT id, name FROM residents WHERE student_id = ?");
        $stmt_find->bind_param("s", $scanned_student_id);
        $stmt_find->execute();
        $result_find = $stmt_find->get_result();

        if ($result_find->num_rows === 1) {
            $resident_data = $result_find->fetch_assoc();
            $resident_id = $resident_data['id'];
            $resident_name = $resident_data['name'];
            $success_message = "學號 {$scanned_student_id} ({$resident_name}) 簽到成功！";
        } else {
            // 找不到學號
            echo "<div class='alert alert-danger'>錯誤：學號 {$scanned_student_id} 找不到對應的住民。</div>";
        }
        $stmt_find->close();

    } elseif (isset($_POST['resident_id']) && !empty($_POST['resident_id'])) {
        // 2. 處理下拉選單手動選擇
        $resident_id = $_POST['resident_id'];
    }

    if (!empty($resident_id)) {
        // 3. 執行簽到 INSERT
        $stmt = $conn->prepare("INSERT INTO checkins (resident_id, checkin_time) VALUES (?, NOW())");
        $stmt->bind_param("i", $resident_id);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>{$success_message}</div>";
            // 簽到成功後，重新整理頁面，但清除 URL 上的 POST 數據
            echo "<meta http-equiv='refresh' content='1;url=checkin_create.php'>"; 
            exit;
        } else {
            echo "<div class='alert alert-danger'>簽到失敗，請重試！</div>";
        }
        $stmt->close();
    } elseif (empty($scanned_student_id)) {
        // 只有在兩種輸入都沒有的情況下才顯示警告
        echo "<div class='alert alert-warning'>請選擇住民或掃描條碼。</div>";
    }
}
?>

<div class="container mt-4">
    <h2>新增簽到紀錄</h2>
    <form id="checkinForm" method="POST">
        
        <div class="card mb-4 bg-light p-4">
            <h4>條碼掃描簽到</h4>
            <div class="mb-3">
                <label for="scanned_student_id" class="form-label">掃描學生證條碼 (學號)</label>
                <input type="text" name="scanned_student_id" id="scanned_student_id" 
                       class="form-control form-control-lg" 
                       placeholder="請將光標置於此處並掃描條碼" 
                       autofocus onkeyup="if(event.key === 'Enter') { document.getElementById('checkinForm').submit(); }">
            </div>
        </div>

        <div class="text-center mb-4">
            <span class="text-muted">或</span>
            <hr>
        </div>

        <div class="card mb-4 p-4">
            <h4>手動選擇簽到</h4>
            <div class="mb-3">
                <label for="resident_id" class="form-label">住民</label>
                <select name="resident_id" id="resident_id" class="form-select">
                    <option value="">請選擇住民</option>
                    <?php while ($r = $residents_result->fetch_assoc()): ?>
                        <option value="<?= $r['id'] ?>">
                            <?= htmlspecialchars($r['name']) ?> (<?= htmlspecialchars($r['student_id']) ?>, 房號 <?= htmlspecialchars($r['room']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">確認手動簽到</button>
        </div>

        <a href="checkin_list_all.php" class="btn btn-secondary">返回簽到管理</a>
    </form>
</div>

<script>
    // 確保每次載入頁面時，光標都聚焦在條碼輸入框上
    document.getElementById('scanned_student_id').focus();

    // JavaScript 邏輯：當條碼輸入框有輸入時，清除下拉選單的值，確保只提交一種方式
    document.getElementById('scanned_student_id').addEventListener('input', function() {
        document.getElementById('resident_id').value = '';
    });
    
    // 當下拉選單有選擇時，清除條碼輸入框的值
    document.getElementById('resident_id').addEventListener('change', function() {
        document.getElementById('scanned_student_id').value = '';
    });
</script>

<?php include("footer.php"); ?>
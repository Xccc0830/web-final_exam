<?php
// checkin_create.php (PDO 轉換版本)

require_once("db.php"); // 確保引入 $pdo 連線
include("header.php");

// 檢查是否為 Admin 身份，如果不是，則導向儀表板或顯示錯誤
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='container mt-4'><div class='alert alert-danger'>您沒有權限存取此頁面。</div></div>";
    include("footer.php");
    exit;
}

// 預先查詢住民列表供手動選擇 (PDO SELECT)
try {
    $stmt_residents = $pdo->query("SELECT id, name, student_id, room FROM residents ORDER BY name");
    $residents_list = $stmt_residents->fetchAll();
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>載入住民列表失敗: " . $e->getMessage() . "</div>";
    $residents_list = [];
}

// 處理簽到 POST 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scanned_student_id = isset($_POST['scanned_student_id']) ? trim($_POST['scanned_student_id']) : '';
    $resident_id = $_POST['resident_id'] ?? 0;
    $final_resident_id = 0;
    $success_message = '';
    $error_message = '';

    try {
        if (!empty($scanned_student_id)) {
            // 1. 透過學號查找 resident_id 
            $stmt_find = $pdo->prepare("SELECT id, name FROM residents WHERE student_id = ?");
            $stmt_find->execute([$scanned_student_id]);
            $resident = $stmt_find->fetch();

            if ($resident) {
                $final_resident_id = $resident['id'];
                $success_message = htmlspecialchars($resident['name']) . ' (學號:' . $scanned_student_id . ') 簽到成功！';
            } else {
                $error_message = '錯誤：學號 ' . $scanned_student_id . ' 不存在！';
            }
        } elseif (!empty($resident_id)) {
            // 2. 透過手動選擇的 resident_id 進行簽到
            $final_resident_id = (int)$resident_id;
            // 順便查出名字，用於顯示成功訊息
            $stmt_name = $pdo->prepare("SELECT name FROM residents WHERE id = ?");
            $stmt_name->execute([$final_resident_id]);
            $resident_name = $stmt_name->fetchColumn();
            if ($resident_name) {
                 $success_message = htmlspecialchars($resident_name) . ' 手動簽到成功！';
            }
        }

        // 3. 執行 INSERT 簽到紀錄 (PDO 預備語句)
        if ($final_resident_id > 0 && empty($error_message)) {
            $stmt_insert = $pdo->prepare("INSERT INTO checkins (resident_id) VALUES (?)");
            $stmt_insert->execute([$final_resident_id]);
            // 檢查是否成功插入 (雖然 PDO 預設拋出例外，但多檢查一次無妨)
            if ($stmt_insert->rowCount() === 0) {
                 $error_message = '資料庫操作失敗，未寫入紀錄。';
            }
        }
    } catch (PDOException $e) {
        $error_message = '資料庫錯誤：' . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h2>門禁簽到系統</h2>
    <p>請使用條碼掃描或手動選擇住民來紀錄簽到時間。</p>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="checkin_create.php">

        <div class="card mb-4 p-4 bg-light">
            <h4>條碼掃描簽到</h4>
            <div class="mb-3">
                <label for="scanned_student_id" class="form-label">掃描學號條碼</label>
                <input type="text" id="scanned_student_id" name="scanned_student_id" class="form-control" autofocus autocomplete="off">
            </div>
            <span class="text-muted">（掃描後會自動提交，無需按鈕）</span>
            <hr>
        </div>

        <div class="card mb-4 p-4">
            <h4>手動選擇簽到</h4>
            <div class="mb-3">
                <label for="resident_id" class="form-label">住民</label>
                <select name="resident_id" id="resident_id" class="form-select">
                    <option value="">請選擇住民</option>
                    <?php 
                    // 【PDO 修正】: 遍歷 $residents_list
                    foreach ($residents_list as $r): 
                    ?>
                        <option value="<?= $r['id'] ?>">
                            <?= htmlspecialchars($r['name']) ?> (<?= htmlspecialchars($r['student_id']) ?>, 房號 <?= htmlspecialchars($r['room']) ?>)
                        </option>
                    <?php endforeach; ?>
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
        if (this.value !== '') {
            document.getElementById('resident_id').value = ''; // 清空手動選擇
        }
    });
    
    // 當手動選擇下拉選單時，清除條碼輸入框的值
    document.getElementById('resident_id').addEventListener('change', function() {
        if (this.value !== '') {
            document.getElementById('scanned_student_id').value = ''; // 清空條碼輸入
        }
    });

    // 條碼掃描後自動提交表單 (假設條碼長度為 6-8 位學號)
    document.getElementById('scanned_student_id').addEventListener('keyup', function() {
        // 您可能需要根據您的條碼系統調整這個長度判斷
        if (this.value.length >= 6 && this.value.length <= 8) { 
            this.form.submit();
        }
    });
</script>

<?php include("footer.php"); ?>
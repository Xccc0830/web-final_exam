<?php
// violation_create.php (支援 搜尋 + 列點違規 + 檔案上傳版本)

require_once("db.php");
include("header.php");

// 檢查是否為 Admin 身份
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='container mt-4'><div class='alert alert-danger'>您沒有權限存取此頁面。</div></div>";
    include("footer.php");
    exit;
}

// --------------------------------------------------------
// 1. 定義標準違規項目清單
// --------------------------------------------------------
$standard_violations = [
    ['晚歸或未依規定時間點名', 5],
    ['房間髒亂經檢查未改善', 3],
    ['攜帶或使用違禁電器（如電爐、電鍋等）', 10],
    ['未經允許帶外人進入宿舍', 15],
    ['深夜喧嘩或製造噪音', 3],
    ['破壞公物或宿舍設施', 20],
];

// --------------------------------------------------------
// 2. 預先查詢住民資料
// --------------------------------------------------------
try {
    $stmt_residents = $pdo->query("SELECT id, name, student_id, room FROM residents ORDER BY room ASC, name ASC");
    $residents_list = $stmt_residents->fetchAll(); 
} catch (PDOException $e) {
    $residents_list = [];
}

// --------------------------------------------------------
// 3. 新增違規處理邏輯 (含檔案上傳)
// --------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_POST['resident_id'] ?? 0;
    $violation_type = $_POST['violation_type'] ?? ''; 
    $violation_other = $_POST['violation_other'] ?? ''; 
    $points = 0;
    $violation_description = '';
    $evidence_path = null; // 預設沒有檔案

    // A. 處理違規內容與點數
    if ($violation_type === 'other') {
        $violation_description = trim($violation_other);
        $points = (int)($_POST['points_other'] ?? 0);
    } else {
        if (!empty($violation_type)) {
            list($desc, $pts) = explode('|', $violation_type);
            $violation_description = trim($desc);
            $points = (int)trim($pts);
        }
    }

    // B. 處理檔案上傳 (防呆強化版)
    if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] === 0) {
        
        // 1. 強制定義絕對路徑，確保 PHP 知道要把東西丟到硬碟哪個格子
        $target_dir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'violations' . DIRECTORY_SEPARATOR;

        // 2. 如果資料夾不存在，強制建立 (含權限 0777)
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = $_FILES['evidence']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // 3. 檢查副檔名
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array($file_ext, $allowed)) {
            
            // 4. 重新命名檔案，避免中文檔名或重複
            $save_name = time() . "_" . uniqid() . "." . $file_ext;
            $full_save_path = $target_dir . $save_name;

            // 5. 搬移檔案並檢查結果
            if (move_uploaded_file($_FILES['evidence']['tmp_name'], $full_save_path)) {
                // 資料庫存相對路徑：給網頁 <img> 標籤用的
                $evidence_path = 'uploads/violations/' . $save_name;
            } else {
                // 如果失敗，直接顯示錯誤訊息並停住 (Debug 用)
                die("檔案搬移失敗！請確認資料夾是否可寫入。路徑：" . $full_save_path);
            }
        }
    }

    // C. 寫入資料庫
    if (!$resident_id || empty($violation_description) || $points <= 0) {
        $error_msg = "請選擇一位住民，並確保違規內容與點數填寫正確。";
    } else {
        try {
            // 注意：請確保資料表已有 evidence_path 欄位
            $stmt = $pdo->prepare("INSERT INTO violations (resident_id, violation, points, evidence_path, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$resident_id, $violation_description, $points, $evidence_path]);
            
            header("Location: violation_list_all.php?msg=add_success");
            exit;
        } catch (PDOException $e) {
            $error_msg = "資料庫錯誤: " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4 text-danger">新增違規紀錄</h2>
    
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="violation_create.php" id="violationForm" enctype="multipart/form-data">
        
        <div class="card mb-4 shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">1. 搜尋並選取住民</h5>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-white border-primary">🔍</span>
                    <input type="text" id="residentSearch" class="form-control border-primary" placeholder="輸入房號、姓名或學號..." onkeyup="filterResidents()">
                </div>
                
                <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                    <table class="table table-hover table-sm border" id="residentTable">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="15%">選取</th>
                                <th width="25%">房號</th>
                                <th width="30%">姓名</th>
                                <th width="30%">學號</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($residents_list as $row): ?>
                            <tr onclick="selectResident(this)" style="cursor: pointer;">
                                <td class="text-center">
                                    <input type="radio" name="resident_id" value="<?= $row['id'] ?>" class="resident-radio" required>
                                </td>
                                <td class="room-cell"><?= htmlspecialchars($row['room']) ?></td>
                                <td class="name-cell"><?= htmlspecialchars($row['name']) ?></td>
                                <td class="id-cell"><?= htmlspecialchars($row['student_id']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">2. 選擇違規項目 (自動帶入扣點)</h5>
            </div>
            <div class="list-group list-group-flush">
                <?php foreach ($standard_violations as $v): 
                    $desc = htmlspecialchars($v[0]);
                    $pts = $v[1];
                    $value = $desc . '|' . $pts;
                ?>
                    <label class="list-group-item list-group-item-action py-3">
                        <input class="form-check-input me-3" type="radio" name="violation_type" value="<?= $value ?>" required onchange="toggleOther(false);">
                        <span class="fw-bold"><?= $desc ?></span>
                        <span class="badge rounded-pill bg-danger float-end">扣 <?= $pts ?> 點</span>
                    </label>
                <?php endforeach; ?>
                
                <label class="list-group-item list-group-item-action list-group-item-warning py-3">
                    <input class="form-check-input me-3" type="radio" name="violation_type" value="other" onchange="toggleOther(true);">
                    <span class="fw-bold">其他 (自定義項目)</span>
                </label>
            </div>
        </div>
        
        <div id="otherViolationFields" class="card p-3 mb-4 border-warning" style="display:none; background-color: #fffcf0;">
            <div class="row">
                <div class="col-md-8 mb-3 mb-md-0">
                    <label class="form-label">違規描述</label>
                    <input type="text" name="violation_other" id="violation_other" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">扣除點數</label>
                    <input type="number" name="points_other" id="points_other" class="form-control" min="1" value="1">
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-secondary">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">3. 上傳佐證資料 (選填)</h5>
            </div>
            <div class="card-body">
                <label class="form-label">上傳照片或文件 (支援 JPG, PNG, PDF)</label>
                <input type="file" name="evidence" class="form-control" accept="image/*, application/pdf">
                <div class="form-text">若有現場照片或書面切結書，請掃描或拍照上傳存證。</div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-danger btn-lg shadow">確認提交違規紀錄</button>
            <a href="violation_list_all.php" class="btn btn-outline-secondary">取消</a>
        </div>
    </form>
</div>

<script>
    function filterResidents() {
        const input = document.getElementById('residentSearch').value.toUpperCase();
        const tr = document.getElementById('residentTable').getElementsByTagName('tr');
        for (let i = 1; i < tr.length; i++) {
            const text = tr[i].textContent.toUpperCase();
            tr[i].style.display = text.indexOf(input) > -1 ? "" : "none";
        }
    }

    function selectResident(row) {
        row.querySelector('.resident-radio').checked = true;
        document.querySelectorAll('#residentTable tr').forEach(r => r.classList.remove('table-primary'));
        row.classList.add('table-primary');
    }

    function toggleOther(show) {
        document.getElementById('otherViolationFields').style.display = show ? 'block' : 'none';
        document.getElementById('violation_other').required = show;
    }
</script>

<style>
    .table-primary { background-color: #e7f1ff !important; }
    .sticky-top { z-index: 1020; }
</style>

<?php include("footer.php"); ?>
<?php
// resident_list.php - 修正 SQL 注入與新增權限保護

require_once 'db.php';
include("header.php");

// -----------------------------------------------------------
// 1. 【關鍵修正】身份組別檢查：確保只有 Admin 才能訪問此頁面
// -----------------------------------------------------------
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='container mt-4'><div class='alert alert-danger'>您沒有權限存取此頁面。</div></div>";
    include("footer.php");
    exit;
}

// -----------------------------------------------------------
// 2. 【關鍵修正】搜尋功能：使用預備語句防止 SQL 注入
// -----------------------------------------------------------
$keyword = $_GET["keyword"] ?? "";
$params = [];
$types = '';

$sql = "SELECT id, student_id, name, room, phone FROM residents";

if (!empty($keyword)) {
    // 增加 WHERE 條件
    $sql .= " WHERE student_id LIKE ? 
              OR name LIKE ?
              OR room LIKE ?";
    
    // 預備語句需要完整匹配的 LIKE 字串
    $search_term = "%" . $keyword . "%";
    
    // 由於我們對三個欄位使用相同的關鍵字，將其重複加入參數陣列
    $params = [$search_term, $search_term, $search_term];
    $types = 'sss'; // 三個參數都是字串 (s)
}

$sql .= " ORDER BY room, student_id"; // 保持排序

// 準備語句
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

// 綁定參數 (只有在有關鍵字時才綁定)
if (!empty($keyword)) {
    // 使用 call_user_func_array 處理動態參數數量
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <h2>住民資料列表</h2>
    <p>所有註冊於系統中的住宿生詳細資料。</p>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="搜尋學號 / 姓名 / 房號" value="<?= htmlspecialchars($keyword) ?>">
            <button class="btn btn-primary">搜尋</button>
        </div>
    </form>

    <a href="resident_create.php" class="btn btn-success mb-3">新增住民</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>學號</th>
                    <th>姓名</th>
                    <th>房號</th>
                    <th>聯繫方式</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["student_id"]) ?></td>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["room"]) ?></td>
                        <td><?= htmlspecialchars($row["phone"]) ?></td>
                        <td>
                            <a href="resident_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">編輯</a>
                            <a href="resident_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('確定要刪除嗎？');">刪除</a>
                            <a href="violation_list.php?resident_id=<?= $row['id'] ?>" class="btn btn-info btn-sm">違規紀錄</a>
                            <a href="checkin_list.php?resident_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">簽到紀錄</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">未找到符合條件的住民資料。</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// 關閉語句和連線
$stmt->close(); 
$conn->close(); // 【修正】確保資料庫連線被關閉
include("footer.php");
?>
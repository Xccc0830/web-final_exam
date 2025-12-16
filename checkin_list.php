<?php
// checkin_list.php (PDO 轉換版本)

include("header.php");
require_once("db.php");

$resident_id = $_GET['resident_id'] ?? 0;
$resident = null;
$list = [];
$total = 0;

if (!$resident_id) {
    die("缺少住民 ID。");
}

try {
    // 1. 查詢住民資料 (PDO 預備語句)
    $stmt = $pdo->prepare("SELECT * FROM residents WHERE id=?");
    $stmt->execute([$resident_id]);
    $resident = $stmt->fetch();

    if (!$resident) {
        die("未找到該住民資料。");
    }

    // 2. 查詢簽到紀錄列表 (PDO 預備語句)
    $stmt2 = $pdo->prepare("SELECT * FROM checkins WHERE resident_id=? ORDER BY checkin_time DESC");
    $stmt2->execute([$resident_id]);
    $list = $stmt2->fetchAll();

    // 3. 查詢總簽到次數 (PDO 預備語句)
    $stmt3 = $pdo->prepare("SELECT COUNT(*) AS total FROM checkins WHERE resident_id=?");
    $stmt3->execute([$resident_id]);
    $total = $stmt3->fetchColumn(); // fetchColumn(0) 直接獲取第一個欄位的值

} catch (PDOException $e) {
    die("資料庫查詢錯誤: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2><?= htmlspecialchars($resident['name']) ?> 的簽到紀錄</h2>
    <p>總簽到次數：<strong><?= $total ?></strong></p>

    <a href="checkin_insert.php?resident_id=<?= $resident_id ?>" class="btn btn-success mb-3">＋ 新增簽到</a>
    <a href="resident_list.php" class="btn btn-secondary mb-3">返回住民列表</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>簽到時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($list) > 0): ?>
                <?php foreach($list as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['checkin_time']) ?></td>
                    <td>
                        <a href="checkin_delete.php?id=<?= $c['id'] ?>&resident_id=<?= $resident_id ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('確定要刪除這筆簽到紀錄嗎？');">刪除</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2" class="text-center">目前沒有簽到紀錄</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>
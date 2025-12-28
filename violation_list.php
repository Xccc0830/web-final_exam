<?php
// violation_list.php (PDO 統一版本)
include("header.php");
require_once 'db.php'; // 確保 db.php 提供的是 $pdo

$resident_id = $_GET['resident_id'] ?? 0;

if (!$resident_id) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>缺少住民 ID。</div></div>";
    include("footer.php");
    exit;
}

try {
    // 1. 取得住民基本資料 (將 $conn 改為 $pdo，並移除 bind_param)
    $stmt = $pdo->prepare("SELECT * FROM residents WHERE id = ?");
    $stmt->execute([$resident_id]);
    $res = $stmt->fetch();

    if (!$res) {
        die("未找到該住民資料。");
    }

    // 2. 取得該住民所有違規紀錄
    $stmt2 = $pdo->prepare("SELECT * FROM violations WHERE resident_id = ? ORDER BY created_at DESC");
    $stmt2->execute([$resident_id]);
    $violations = $stmt2->fetchAll();

    // 3. 計算總點數 (SUM)
    $stmt3 = $pdo->prepare("SELECT SUM(points) as total_points FROM violations WHERE resident_id = ?");
    $stmt3->execute([$resident_id]);
    $total = $stmt3->fetch();
    $total_points = $total['total_points'] ?? 0;

} catch (PDOException $e) {
    die("資料庫查詢失敗: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2><?= htmlspecialchars($res['name']) ?> 的違規紀錄</h2>
    <p>總點數：<strong class="text-danger"><?= htmlspecialchars($total_points) ?></strong></p>

    <a href="violation_create.php?resident_id=<?= $resident_id ?>" class="btn btn-success mb-3">＋ 新增違規紀錄</a>
    <a href="resident_list.php" class="btn btn-secondary mb-3">返回住民列表</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>違規內容</th>
                    <th>點數</th>
                    <th>日期</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($violations) > 0): ?>
                    <?php foreach ($violations as $v): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['violation']) ?></td>
                        <td><?= htmlspecialchars($v['points']) ?></td>
                        <td><?= htmlspecialchars($v['created_at']) ?></td>
                        <td>
                            <a href="violation_delete.php?id=<?= $v['id'] ?>&resident_id=<?= $resident_id ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('確定要刪除嗎？');">刪除</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">目前無任何違規紀錄。</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("footer.php"); ?>
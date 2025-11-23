<?php
include("header.php");
include("db.php");

$resident_id = $_GET['resident_id'] ?? 0;

// 查住民資料
$stmt = $conn->prepare("SELECT * FROM residents WHERE id=?");
$stmt->bind_param("i", $resident_id);
$stmt->execute();
$resident = $stmt->get_result()->fetch_assoc();

// 查簽到紀錄
$stmt2 = $conn->prepare("SELECT * FROM checkins WHERE resident_id=? ORDER BY checkin_time DESC");
$stmt2->bind_param("i", $resident_id);
$stmt2->execute();
$list = $stmt2->get_result();

// 統計簽到次數
$stmt3 = $conn->prepare("SELECT COUNT(*) AS total FROM checkins WHERE resident_id=?");
$stmt3->bind_param("i", $resident_id);
$stmt3->execute();
$total = $stmt3->get_result()->fetch_assoc()['total'];
?>

<div class="container mt-4">
    <h2><?= $resident['name'] ?> 的簽到紀錄</h2>
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
            <?php while($c = $list->fetch_assoc()): ?>
            <tr>
                <td><?= $c['checkin_time'] ?></td>
                <td>
                    <a href="checkin_delete.php?id=<?= $c['id'] ?>&resident_id=<?= $resident_id ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('確定要刪除這筆紀錄嗎？')">
                       刪除
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>

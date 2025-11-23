<?php
include("header.php");
include("db.php");

$resident_id = $_GET['resident_id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM residents WHERE id=?");
$stmt->bind_param("i", $resident_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

$stmt2 = $conn->prepare("SELECT * FROM violations WHERE resident_id=? ORDER BY created_at DESC");
$stmt2->bind_param("i", $resident_id);
$stmt2->execute();
$violations = $stmt2->get_result();

$stmt3 = $conn->prepare("SELECT SUM(points) as total_points FROM violations WHERE resident_id=?");
$stmt3->bind_param("i", $resident_id);
$stmt3->execute();
$total = $stmt3->get_result()->fetch_assoc();
$total_points = $total['total_points'] ?? 0;
?>

<div class="container mt-4">
    <h2><?= $res['name'] ?> 的違規紀錄</h2>
    <p>總點數：<strong><?= $total_points ?></strong></p>

    <a href="violation_create.php?resident_id=<?= $resident_id ?>" class="btn btn-success mb-3">＋ 新增違規紀錄</a>
    <a href="resident_list.php" class="btn btn-secondary mb-3">返回住民列表</a>

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
            <?php while($v = $violations->fetch_assoc()): ?>
            <tr>
                <td><?= $v['violation'] ?></td>
                <td><?= $v['points'] ?></td>
                <td><?= $v['created_at'] ?></td>
                <td>
                    <a href="violation_delete.php?id=<?= $v['id'] ?>&resident_id=<?= $resident_id ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('確定要刪除嗎？');">刪除</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>

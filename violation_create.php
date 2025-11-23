<?php
include("header.php");
include("db.php");

$resident_id = $_GET['resident_id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM residents WHERE id=?");
$stmt->bind_param("i", $resident_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
?>

<div class="container mt-4">
    <h2>為 <?= $res['name'] ?> 新增違規紀錄</h2>

    <form method="POST" action="violation_insert.php">
        <input type="hidden" name="resident_id" value="<?= $resident_id ?>">

        <div class="mb-3">
            <label class="form-label">違規內容</label>
            <input type="text" name="violation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">點數</label>
            <input type="number" name="points" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">新增</button>
        <a href="violation_list.php?resident_id=<?= $resident_id ?>" class="btn btn-secondary">返回列表</a>
    </form>
</div>

<?php include("footer.php"); ?>


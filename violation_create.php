<?php
include("db.php");
include("header.php");

// 取得所有住民
$residents = $conn->query("SELECT * FROM residents ORDER BY name ASC");

// 新增違規
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_POST['resident_id'];
    $violation = $_POST['violation'];
    $points = $_POST['points'];

    $stmt = $conn->prepare("INSERT INTO violations (resident_id, violation, points, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isi", $resident_id, $violation, $points);
    $stmt->execute();

    echo "<div class='alert alert-success'>新增違規成功！</div>";
}
?>

<div class="container mt-4">
    <h2>新增違規紀錄</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">選擇住民</label>
            <select name="resident_id" class="form-control" required>
                <?php while($row = $residents->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (房號: <?= $row['room'] ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">違規內容</label>
            <input type="text" name="violation" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">點數</label>
            <input type="number" name="points" class="form-control" required min="0">
        </div>
        <button type="submit" class="btn btn-success">新增</button>
    </form>
</div>

<?php include("footer.php"); ?>

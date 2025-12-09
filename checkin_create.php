<?php
include("db.php");
include("header.php");

$residents_result = $conn->query("SELECT id, name, student_id, room FROM residents ORDER BY name");

// 新增簽到
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_POST['resident_id'];

    if (!empty($resident_id)) {
        $stmt = $conn->prepare("INSERT INTO checkins (resident_id, checkin_time) VALUES (?, NOW())");
        $stmt->bind_param("i", $resident_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>簽到成功！</div>";
            echo "<meta http-equiv='refresh' content='1;url=checkin_list_all.php'>";
            exit;
        } else {
            echo "<div class='alert alert-danger'>簽到失敗，請重試！</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>請選擇住民</div>";
    }
}
?>

<div class="container mt-4">
    <h2>新增簽到紀錄</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="resident_id" class="form-label">住民</label>
            <select name="resident_id" id="resident_id" class="form-select" required>
                <option value="">請選擇住民</option>
                <?php while ($r = $residents_result->fetch_assoc()): ?>
                    <option value="<?= $r['id'] ?>">
                        <?= htmlspecialchars($r['name']) ?> (<?= htmlspecialchars($r['student_id']) ?>, 房號 <?= htmlspecialchars($r['room']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success">簽到</button>
        <a href="checkin_list_all.php" class="btn btn-secondary">返回簽到管理</a>
    </form>
</div>

<?php include("footer.php"); ?>

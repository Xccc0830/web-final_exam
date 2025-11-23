<?php
include("header.php");
include("db.php");

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM residents WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$resident = $result->fetch_assoc();
?>

<div class="container mt-4">
    <h2>編輯住民資料</h2>

    <form method="POST" action="update.php">
        <input type="hidden" name="id" value="<?= $resident['id'] ?>">

        <div class="mb-3">
            <label class="form-label">學號</label>
            <input type="text" name="student_id" class="form-control" value="<?= $resident['student_id'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">姓名</label>
            <input type="text" name="name" class="form-control" value="<?= $resident['name'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">房號</label>
            <input type="text" name="room" class="form-control" value="<?= $resident['room'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">聯絡電話</label>
            <input type="text" name="phone" class="form-control" value="<?= $resident['phone'] ?>">
        </div>

        <button type="submit" class="btn btn-primary">更新資料</button>
        <a href="resident_list.php" class="btn btn-secondary">返回列表</a>
    </form>
</div>

<?php
include("footer.php");
?>

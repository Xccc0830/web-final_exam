<?php
include("header.php");
?>

<div class="container mt-4">
    <h2>新增住民資料</h2>

    <form method="POST" action="insert.php">
        <div class="mb-3">
            <label class="form-label">學號</label>
            <input type="text" name="student_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">姓名</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">房號</label>
            <input type="text" name="room" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">聯絡電話</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">新增住民</button>
        <a href="resident_list.php" class="btn btn-secondary">返回列表</a>
    </form>
</div>

<?php
include("footer.php");
?>

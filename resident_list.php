<?php
include("header.php");
include("db.php");

// 搜尋功能
$keyword = $_GET["keyword"] ?? "";

$sql = "SELECT * FROM residents";
if (!empty($keyword)) {
    $sql .= " WHERE student_id LIKE '%$keyword%' 
              OR name LIKE '%$keyword%'
              OR room LIKE '%$keyword%'";
}

$result = $conn->query($sql);
?>

<div class="container mt-4">
    <h2>住民資料列表</h2>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="搜尋學號 / 姓名 / 房號" value="<?= $keyword ?>">
            <button class="btn btn-primary">搜尋</button>
        </div>
    </form>

    <a href="resident_create.php" class="btn btn-success mb-3">新增住民</a>

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
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row["student_id"] ?></td>
                <td><?= $row["name"] ?></td>
                <td><?= $row["room"] ?></td>
                <td><?= $row["phone"] ?></td>
                <td>
                    <a href="resident_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">編輯</a>
                    <a href="resident_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('確定要刪除嗎？');">刪除</a>
                    <a href="violation_list.php?resident_id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">違規紀錄</a>
                    <a href="checkin_list.php?resident_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">簽到紀錄</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
include("footer.php");
?>

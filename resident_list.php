<?php
// resident_list.php (PDO 轉換版本)

require_once 'db.php'; // 現在引入的是 $pdo
include("header.php");


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='container mt-4'><div class='alert alert-danger'>您沒有權限存取此頁面。</div></div>";
    include("footer.php");
    exit;
}

$keyword = $_GET["keyword"] ?? "";
$params = []; 

$sql = "SELECT id, student_id, name, room, phone FROM residents";

if (!empty($keyword)) {
    $sql .= " WHERE student_id LIKE :kwd 
              OR name LIKE :kwd
              OR room LIKE :kwd";
    
    $search_term = "%" . $keyword . "%";
    
    $params = [':kwd' => $search_term]; 
}

$sql .= " ORDER BY room, student_id"; 

try {
    $stmt = $pdo->prepare($sql);

    $stmt->execute($params); 

    $residents = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die('Database query failed: ' . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>住民資料列表</h2>
    <p>所有註冊於系統中的住宿生詳細資料。</p>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="搜尋學號 / 姓名 / 房號" value="<?= htmlspecialchars($keyword) ?>">
            <button class="btn btn-primary">搜尋</button>
        </div>
    </form>

    <a href="resident_create.php" class="btn btn-success mb-3">新增住民</a>

    <div class="table-responsive">
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
                <?php if (count($residents) > 0): ?>
                    <?php foreach($residents as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["student_id"]) ?></td>
                        <td><?= htmlspecialchars($row["name"]) ?></td>
                        <td><?= htmlspecialchars($row["room"]) ?></td>
                        <td><?= htmlspecialchars($row["phone"]) ?></td>
                        <td>
                            <a href="resident_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">編輯</a>
                            <a href="resident_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('確定要刪除嗎？');">刪除</a>
                            <a href="violation_list.php?resident_id=<?= $row['id'] ?>" class="btn btn-info btn-sm">違規紀錄</a>
                            <a href="checkin_list.php?resident_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">簽到紀錄</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">未找到符合條件的住民資料。</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("footer.php");?>
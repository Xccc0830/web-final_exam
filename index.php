<?php
// index.php
// 不需要登入系統，所以不用 session_start()
?>

<?php include("header.php"); ?>

<div class="container mt-5">
    <h1 class="mb-5 text-center">宿舍住民安全與紀律管理系統</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="resident_list.php" class="text-decoration-none text-dark">
                <div class="feature-card border rounded shadow-sm">
                    <h4 class="mt-3">住民資料管理</h4>
                    <p>查詢 / 新增 / 編輯 / 刪除</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="violation_list_all.php" class="text-decoration-none text-dark">
                <div class="feature-card border rounded shadow-sm">
                    <h4 class="mt-3">違規記點管理</h4>
                    <p>新增違規 / 查看總點數</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="checkin_list_all.php" class="text-decoration-none text-dark">
                <div class="feature-card border rounded shadow-sm">
                    <h4 class="mt-3">住民簽到 / 門禁</h4>
                    <p>回報住民回宿舍時間</p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<?php
// index.php
// 不需要登入系統，所以不用 session_start()
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>宿舍管理系統</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f7f9fc;
        }
        .navbar {
            background: #0d6efd;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }

        /* 封面大圖示 */
        .feature-icon {
            font-size: 4rem; /* 圖示大小 */
            display: block;
        }
        .feature-card {
            text-align: center;
            padding: 2rem;
            transition: transform 0.2s;
        }
        .feature-card:hover {
            transform: scale(1.05);
            background-color: #e9f0ff;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">住民資料系統</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="resident_list.php">住民列表</a></li>
                <li class="nav-item"><a class="nav-link" href="resident_create.php">新增住民</a></li>
                <li class="nav-item"><a class="nav-link" href="violation_list_all.php">違規管理</a></li>
                <li class="nav-item"><a class="nav-link" href="checkin_list_all.php">簽到管理</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- 首頁封面內容 -->
<div class="container mt-5">
    <h1 class="mb-5 text-center">🏠 宿舍住民安全與紀律管理系統</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="resident_list.php" class="text-decoration-none text-dark">
                <div class="feature-card border rounded shadow-sm">
                    <span class="feature-icon">👤</span>
                    <h4 class="mt-3">住民資料管理</h4>
                    <p>查詢 / 新增 / 編輯 / 刪除</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="violation_list_all.php" class="text-decoration-none text-dark">
                <div class="feature-card border rounded shadow-sm">
                    <span class="feature-icon">⚠️</span>
                    <h4 class="mt-3">違規記點管理</h4>
                    <p>新增違規 / 查看總點數</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="checkin_list_all.php" class="text-decoration-none text-dark">
                <div class="feature-card border rounded shadow-sm">
                    <span class="feature-icon">🏃‍♂️</span>
                    <h4 class="mt-3">住民簽到 / 門禁</h4>
                    <p>回報住民回宿舍時間</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center mt-5 mb-4 text-secondary">
    © <?php echo date("Y"); ?> 宿舍管理系統
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

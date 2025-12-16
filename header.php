<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE;
$resident_name = $is_logged_in ? htmlspecialchars($_SESSION['name']) : '';

// 【關鍵修改】：新增判斷是否為管理員
$is_admin = $is_logged_in && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>住民資料管理系統</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f7f9fc;
        }
        .navbar {
            background: #0d6efd;
        }
        .navbar-brand, .nav-link, .welcome-text {
            color: white !important;
        }
        .welcome-text {
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            white-space: nowrap;
        }
    </style> 
</head>

<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">住民資料系統</a> <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                
                <?php if ($is_admin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="resident_list.php">住民列表</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="resident_create.php">新增住民</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="violation_list_all.php">違規管理</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checkin_list_all.php">簽到管理</a>
                    </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">儀表板</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if ($is_logged_in): ?>
                    <span class="navbar-text welcome-text">
                        歡迎，**<?php echo $resident_name; ?>**
                        <?php if ($is_admin): ?>
                            (管理員)
                        <?php endif; ?>
                    </span>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">登出</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light" href="login.php" style="border: 1px solid white;">
                            登入查詢
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>住民資料管理系統</title>

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
    </style> 
</head>

<body>
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
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">儀表板</a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">

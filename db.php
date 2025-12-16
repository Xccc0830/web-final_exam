<?php
// db.php (PDO 轉換版本)

$host = "localhost";
$db   = "web期末專案"; // 您的資料庫名稱
$user = "root";
$pass = "";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    // 設置錯誤模式為例外，遇到錯誤時拋出 PDOException
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // 預設的結果集返回關聯陣列
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // 禁用模擬預處理，使用原生 MySQL 預處理，提高安全性
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     // 建立 PDO 連線物件，命名為 $pdo
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // 連線失敗時，顯示錯誤並終止程式
     die("Database connection failed: " . $e->getMessage());
}

// 注意：現在整個系統中，資料庫連線變數名稱為 $pdo
?>
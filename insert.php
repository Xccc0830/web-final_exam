<?php
// insert.php (PDO 統一版本)
require_once 'db.php'; 

// 1. 取得並清理輸入資料
$student_id = $_POST['student_id'] ?? '';
$name = $_POST['name'] ?? '';
$room = $_POST['room'] ?? '';
$phone = $_POST['phone'] ?? '';

// 新增安全性處理：預設密碼和角色
$default_password = '123456'; 
$password_hash = password_hash($default_password, PASSWORD_DEFAULT);
$role = 'student'; 

// 檢查關鍵欄位是否為空
if (empty($student_id) || empty($name) || empty($room)) {
    header("Location: resident_create.php?error=missing_fields"); 
    exit;
}

try {
    // 2. 準備 SQL 語句
    $sql = "INSERT INTO residents (student_id, name, room, phone, password, role) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);

    // 3. 執行語句
    if ($stmt->execute([$student_id, $name, $room, $phone, $password_hash, $role])) {
        header("Location: resident_list.php?msg=add_success"); 
        exit;
    }

} catch (PDOException $e) {
    if ($e->getCode() == '23000') {
         header("Location: resident_create.php?error=student_id_exists");
         exit;
    }
    die("新增失敗: " . $e->getMessage());
}
?>
<?php
// insert.php (PDO 轉換版本)

// 確保連線檔案使用 $pdo 變數
require_once 'db.php'; 

// 1. 取得並清理輸入資料
// 建議使用 isset 或空值合併運算子 (??) 處理未定義的 POST 變數
$student_id = $_POST['student_id'] ?? '';
$name = $_POST['name'] ?? '';
$room = $_POST['room'] ?? '';
$phone = $_POST['phone'] ?? '';

// 新增安全性處理：預設密碼和角色
$default_password = '123456'; // 建議您讓系統自動產生安全密碼或讓管理員設定
$password_hash = password_hash($default_password, PASSWORD_DEFAULT);
$role = 'student'; // 預設角色為學生

// 檢查關鍵欄位是否為空
if (empty($student_id) || empty($name) || empty($room)) {
    // 導向回新增頁面或顯示錯誤
    header("Location: resident_create.php?error=missing_fields"); 
    exit;
}

try {
    // 2. 準備 SQL 語句 (使用 ? 作為佔位符，確保包含 password 和 role)
    // 欄位: student_id, name, room, phone, password, role
    $sql = "INSERT INTO residents (student_id, name, room, phone, password, role) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);

    // 3. 執行語句並綁定參數
    // PDO 會自動處理資料類型，無需像 mysqli 一樣指定 'ssssi'
    $success = $stmt->execute([$student_id, $name, $room, $phone, $password_hash, $role]);

    if ($success) {
        // 成功，導向列表頁
        // 可以在 URL 中加入成功訊息
        header("Location: resident_list.php?msg=add_success"); 
        exit;
    } else {
        // 理論上在 PDO::ATTR_ERRMODE 設置為 EXCEPTION 時，執行失敗會拋出例外
        // 這裡作為備用處理
        header("Location: resident_list.php?error=add_failed_unknown");
        exit;
    }

} catch (PDOException $e) {
    // 捕捉資料庫層級的錯誤，例如學號重複 (Duplicate entry)
    // 顯示錯誤訊息 (正式環境應記錄到日誌而非直接輸出)
    $error_msg = "新增失敗: " . $e->getMessage();
    
    // 如果是學號重複的錯誤 (MySQL 錯誤碼 23000)
    if ($e->getCode() == '23000') {
         header("Location: resident_create.php?error=student_id_exists");
         exit;
    }

    echo $error_msg;
    // 可以在這裡記錄錯誤到 log 檔案
}
?>
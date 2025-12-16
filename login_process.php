<?php
// login_process.php (PDO 轉換版本)
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php'; // 現在引入的是 PDO 連線 $pdo

$student_id = trim($_POST['student_id'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($student_id) || empty($password)) {
    $_SESSION['login_error'] = "請輸入學號和密碼。";
    header("location: login.php");
    exit;
}

try {
    // 1. 準備語句：使用命名參數 :student_id
    $stmt = $pdo->prepare("SELECT id, password, name, role FROM residents WHERE student_id = :student_id");
    
    // 2. 執行並傳入參數 (PDO 安全防範 SQL 注入)
    $stmt->execute(['student_id' => $student_id]);
    
    // 3. 取得結果
    $resident = $stmt->fetch();

    if ($resident && password_verify($password, $resident['password'])) {
        // 驗證成功，設定 Session
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['resident_id'] = $resident['id'];
        $_SESSION['name'] = $resident['name'];
        $_SESSION['role'] = $resident['role'];
        
        header("location: dashboard.php");
        exit;
    } else {
        // 驗證失敗
        $_SESSION['login_error'] = "學號或密碼錯誤。";
        $_SESSION['temp_student_id'] = $student_id;
        header("location: login.php");
        exit;
    }

} catch (PDOException $e) {
    // 處理資料庫錯誤
    $_SESSION['login_error'] = "登入錯誤 (DB): " . $e->getMessage();
    header("location: login.php");
    exit;
}
?>
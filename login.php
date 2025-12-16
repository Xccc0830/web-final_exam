<?php
// login.php

// 引入 Header (包含 session_start() 和 HTML 開頭)
include("header.php"); 

// 檢查使用者是否已經登入。如果已登入，直接導向儀表板。
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
    header("location: dashboard.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm p-4 mt-5">
                <h2 class="card-title text-center mb-4">🏠 使用者登入</h2>
                
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php echo htmlspecialchars($_SESSION['login_error']); ?>
                    </div>
                    <?php unset($_SESSION['login_error']); // 清除錯誤訊息 ?>
                <?php endif; ?>
                
                <form action="login_process.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="student_id" class="form-label">學號 (Student ID):</label>
                        <input type="text" id="student_id" name="student_id" class="form-control" required
                               value="<?php echo isset($_SESSION['temp_student_id']) ? htmlspecialchars($_SESSION['temp_student_id']) : ''; ?>">
                        <?php unset($_SESSION['temp_student_id']);  ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">密碼 (Password):</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mt-3">登入查詢</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
// 引入 Footer (關閉 HTML 標籤)
include("footer.php"); 
?>
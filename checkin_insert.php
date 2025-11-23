<?php
include("db.php");

$resident_id = $_GET['resident_id'] ?? 0;

$stmt = $conn->prepare("INSERT INTO checkins (resident_id) VALUES (?)");
$stmt->bind_param("i", $resident_id);

if ($stmt->execute()) {
    header("Location: checkin_list.php?resident_id=$resident_id");
    exit;
} else {
    echo "新增失敗: " . $stmt->error;
}

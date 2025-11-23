<?php
include("db.php");

$resident_id = $_POST['resident_id'];
$violation = $_POST['violation'];
$points = $_POST['points'];

$stmt = $conn->prepare("INSERT INTO violations (resident_id, violation, points) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $resident_id, $violation, $points);

if ($stmt->execute()) {
    header("Location: violation_list.php?resident_id=$resident_id");
    exit;
} else {
    echo "新增失敗: " . $stmt->error;
}

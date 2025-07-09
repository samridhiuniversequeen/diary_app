<?php
session_start();
include("db.php");

$id = $_POST["id"];
$stmt = $conn->prepare("DELETE FROM diary_entries WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: diary.php");
?>

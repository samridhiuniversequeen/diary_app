<?php
session_start();
include("db.php");

$id = $_POST["id"];
$content = $_POST["content"];

$stmt = $conn->prepare("UPDATE diary_entries SET content=? WHERE id=?");
$stmt->bind_param("si", $content, $id);
$stmt->execute();

header("Location: diary.php");
?>

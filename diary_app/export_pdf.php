<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load mPDF

session_start();
include("db.php");

$email = $_SESSION["email"];
$mpdf = new \Mpdf\Mpdf();
$html = "<h2>Diary Entries for $email</h2>";

$result = $conn->query("SELECT * FROM diary_entries WHERE email='$email'");
while ($row = $result->fetch_assoc()) {
    $html .= "<p><strong>".$row["created_at"].":</strong><br>" . nl2br($row["content"]) . "</p><hr>";
}

$mpdf->WriteHTML($html);
$mpdf->Output("diary.pdf", "D");
?>

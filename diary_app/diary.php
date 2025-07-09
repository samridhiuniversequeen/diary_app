<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: index.html");
    exit();
}
include("db.php");
$email = $_SESSION["email"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Diary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($email); ?></h2>

    <!-- Entry form -->
    <form action="save_entry.php" method="POST">
        <textarea name="content" rows="5" cols="50" placeholder="Write your diary here..." required></textarea><br>
        <input type="text" name="tag" placeholder="Add a tag (e.g., Happy, Sad)">
        <button type="submit">Save</button>
    </form>

    <!-- Filters -->
    <form method="GET">
        <input type="text" name="tag" placeholder="Filter by tag" value="<?php echo $_GET['tag'] ?? ''; ?>">
        <input type="date" name="date" value="<?php echo $_GET['date'] ?? ''; ?>">
        <button type="submit">Apply Filters</button>
    </form>

    <!-- Export -->
    <form action="export_pdf.php" method="POST">
        <button type="submit">Export All to PDF</button>
    </form>

    <!-- Dark mode -->
    <button onclick="toggleDark()">Toggle Dark Mode</button>

    <script>
        function toggleDark() {
            document.body.classList.toggle("dark");
            localStorage.setItem("theme", document.body.classList.contains("dark") ? "dark" : "");
        }
        window.onload = () => {
            if (localStorage.getItem("theme") === "dark") {
                document.body.classList.add("dark");
            }
        };
    </script>

    <hr>
    <h3>Your Entries</h3>

    <?php
    // Build filter query
    $filter = "WHERE email='$email'";
    if (!empty($_GET["tag"])) {
        $tag = $conn->real_escape_string($_GET["tag"]);
        $filter .= " AND tag='$tag'";
    }
    if (!empty($_GET["date"])) {
        $date = $conn->real_escape_string($_GET["date"]);
        $filter .= " AND DATE(created_at)='$date'";
    }

    $query = "SELECT * FROM diary_entries $filter ORDER BY created_at DESC";
    $result = $conn->query($query);

    if (!$result) {
        echo "<p style='color:red;'>Query Error: " . $conn->error . "</p>";
    } elseif ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<form method='POST' action='edit_entry.php'>
                    <textarea name='content' rows='4' cols='50'>" . htmlspecialchars($row["content"]) . "</textarea>
                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                    <button type='submit'>Update</button>
                </form>
                <form method='POST' action='delete_entry.php'>
                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                    <button type='submit'>Delete</button>
                </form>
                <small><strong>Tag:</strong> " . htmlspecialchars($row["tag"]) . " | <strong>Date:</strong> " . $row["created_at"] . "</small>
                <hr>";
        }
    } else {
        echo "<p>No entries found.</p>";
    }
    ?>

    <br>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>

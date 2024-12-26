<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Events</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <h1>Archived Events</h1>
        <nav>
            <a href="index.php">Back</a> 
        </nav>
    </header>
    <section>
        <ul class="event-list">
            <?php
            $result = $conn->query("SELECT * FROM events WHERE date < NOW() ORDER BY date DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<li class='event-item' style='border-color: {$row['color_scheme']}'>
                        <span>{$row['emoji']} {$row['name']}</span>
                        <p>Date: {$row['date']}</p>
                    </li>";
            }
            ?>
        </ul>
    </section>
</body>
</html>

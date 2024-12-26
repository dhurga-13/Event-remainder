<?php
include 'db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $color = $_POST['color'];
    $emoji = $_POST['emoji'];

    // If background is not provided, set a default one
    $background = isset($_POST['background_url']) && !empty($_POST['background_url']) ? $_POST['background_url'] : 'th.jpg';

    // Prepare and execute the query to insert the event
    $stmt = $conn->prepare("INSERT INTO events (name, date, color_scheme, emoji, background) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $date, $color, $emoji, $background);

    if ($stmt->execute()) {
        echo "Event added successfully!";
        header('Location: index.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

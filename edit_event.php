<?php
include 'db.php';

if (!isset($_GET['id'])) {
    echo "No event ID provided.";
    exit();
}

$eventId = $_GET['id'];

// Update background image if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['background'])) {
    $background = $_POST['background'];
    $stmt = $conn->prepare("UPDATE events SET background = ? WHERE id = ?");
    $stmt->bind_param("si", $background, $eventId);
    $stmt->execute();
    $stmt->close();
    header("Location: view_event.php?id=$eventId");
    exit();
}

// Available background images
$backgroundImages = [
    'th.jpg',
    'th2.jpg',
    'th3.jpg'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event Background</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* General page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #eaf4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        h2 {
            font-size: 2rem;
            color: #007bff;
            text-align: center;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        /* Form button styling */
        button[type="submit"] {
            padding: 0.75rem 2rem;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 1.5rem;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Background options styling */
        .background-options {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .background-preview {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border: 3px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            transition: border-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Highlight selected image */
        input[type="radio"]:checked + .background-preview {
            border-color: #007bff;
            box-shadow: 0px 4px 12px rgba(0, 123, 255, 0.5);
            transform: scale(1.05);
        }

        label {
            display: inline-block;
        }

        /* Form input styling */
        input[type="radio"] {
            display: none;
        }
    </style>
</head>
<body>
    <h2>Select a Background Image</h2>
    <div class="container">    
        <form action="edit_event.php?id=<?php echo $eventId; ?>" method="post">
            <div class="background-options">
                <?php foreach ($backgroundImages as $image): ?>
                    <label>
                        <input type="radio" name="background" value="<?php echo $image; ?>" required>
                        <img src="<?php echo $image; ?>" alt="Background Option" class="background-preview">
                    </label>
                <?php endforeach; ?>
            </div>
            <button type="submit">Set Background</button>
        </form>
    </div>
</body>
</html>

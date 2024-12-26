<?php
include 'db.php';

// Check if an event ID is provided and fetch event details from the database
if (isset($_GET['id'])) {
    $eventId = $_GET['id'];
    $result = $conn->query("SELECT * FROM events WHERE id = $eventId");
    $event = $result->fetch_assoc();

    if (!$event) {
        echo "Event not found.";
        exit();
    }
} else {
    echo "No event ID provided.";
    exit();
}

// Assume the stored time is in IST
$eventDateIST = new DateTime($event['date'], new DateTimeZone('Asia/Kolkata'));

// Format the event date in IST for display
$formattedEventDate = $eventDateIST->format('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($event['name']); ?> - Countdown</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            background-image: url('<?php echo htmlspecialchars($event['background']); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: <?php echo htmlspecialchars($event['color_scheme']); ?>;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
            overflow: hidden; /* Prevents scroll when confetti is active */
        }

        .countdown-container {
            text-align: center;
            padding: 50px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            max-width: 600px;
            width: 100%;
        }

        .countdown-timer {
            font-size: 36px;
            font-weight: bold;
            color: <?php echo htmlspecialchars($event['color_scheme']); ?>;
        }

        .edit-button, .home-button {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            padding: 10px 20px;
            position: absolute;
            transition: background-color 0.3s ease;
        }

        .home-button { left: 20px; top: 20px; }
        .edit-button { right: 20px; top: 20px; border-radius: 50%; }

        .home-button:hover, .edit-button:hover {
            background-color: #0056b3;
        }

        /* Pop-up styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            max-width: 500px;
            color: #4CAF50;
        }

        .popup-content h2 {
            margin: 0;
            font-size: 32px;
        }

        .popup-content p {
            margin: 20px 0;
        }

        .popup-content button {
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup-content button:hover {
            background: #45a049;
        }

        /* Emoji popper animation */
        .emoji-popper {
            position: absolute;
            font-size: 24px;
            animation: fall 3s linear infinite;
            z-index: 10;
        }

        /* Keyframes for falling animation */
        @keyframes fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="countdown-container">
        <h1><?php echo $event['emoji'] . " " . htmlspecialchars($event['name']); ?></h1>
        <p>Event Date (IST): <?php echo $formattedEventDate; ?></p>
        <div id="countdown-timer" class="countdown-timer"></div>
    </div>

    <!-- Home button -->
    <a href="index.php" class="home-button">Back</a>

    <!-- Edit button -->
    <a href="edit_event.php?id=<?php echo $eventId; ?>" class="edit-button" title="Edit Background">
        &#9881;
    </a>

    <!-- Celebration Pop-Up -->
    <div class="popup-overlay" id="popup-overlay">
        <div class="popup-content">
            <h2>🎉 Celebration Time! 🎉</h2>
            <p>The event has started! Enjoy the celebration!</p>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
// JavaScript countdown logic
function startCountdown(eventDate) {
    var countdownDisplay = document.getElementById("countdown-timer");

    var interval = setInterval(function() {
        var now = new Date().getTime();
        var timeRemaining = eventDate - now;

        var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
        var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

        countdownDisplay.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s";

        if (timeRemaining <= 0) {
            clearInterval(interval);
            countdownDisplay.innerHTML = "Event Started!";
            showEventNotification();
        }
    }, 1000);
}

// Function to show the celebration pop-up and notification
function showEventNotification() {
    document.getElementById("popup-overlay").style.display = "flex";
    startEmojiPopper("<?php echo addslashes($event['emoji']); ?>");

    if (Notification.permission === "granted") {
        new Notification("The event has started!");
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                new Notification("The event has started!");
            }
        });
    }
}

// Close the pop-up
function closePopup() {
    document.getElementById("popup-overlay").style.display = "none";
    const emojis = document.querySelectorAll(".emoji-popper");
    emojis.forEach(emoji => {
        emoji.remove(); // Remove emoji element to stop the animation
    });
}

// Emoji popper effect
function startEmojiPopper(emoji) {
    const maxEmojis = 50;

    for (let i = 0; i < maxEmojis; i++) {
        const emojiElement = document.createElement("div");
        emojiElement.classList.add("emoji-popper");
        emojiElement.innerText = emoji;
        emojiElement.style.left = Math.random() * 100 + "vw";
        emojiElement.style.animationDuration = Math.random() * 2 + 3 + "s";
        emojiElement.style.fontSize = Math.random() * 10 + 20 + "px";

        document.body.appendChild(emojiElement);

        // Remove the emoji after the animation ends
        emojiElement.addEventListener("animationend", () => {
            emojiElement.remove();
        });
    }
}

window.onload = function() {
    var eventDateIST = new Date("<?php echo $formattedEventDate; ?>").getTime();
    startCountdown(eventDateIST);
};
</script>

</body>
</html>

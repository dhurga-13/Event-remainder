<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Countdown Events</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
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

        .container {
            display: flex;
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            width: 100%;
        }

        /* Event List Section */
        .event-list {
            width: 60%;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .event-list h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .event-list nav a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            padding: 0 0.5rem;
        }

        .event-list nav a:hover {
            color: #80ced6;
        }

        .events {
            margin-top: 1rem;
        }

        .event {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f0f7f7;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        }

        .event h3 {
            font-size: 1.2rem;
            margin: 0 0 0.5rem 0;
        }

        .event a {
            color: #007bff;
            text-decoration: none;
        }

        .event p {
            color: #555;
        }

        /* Event Form Section */
        .event-form {
            width: 40%;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .event-form h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .event-form form {
            display: flex;
            flex-direction: column;
        }

        .event-form input[type="text"],
        .event-form input[type="datetime-local"],
        .event-form input[type="color"] {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
        }

        .event-form button {
            padding: 0.75rem;
            background-color: #80ced6;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .event-form button:hover {
            background-color: #66bbbd;
            transform: translateY(-3px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }

        /* Celebration Pop-Up styles */
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
            z-index: 10;
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
            z-index: 5;
        }

        /* Keyframes for falling animation */
        @keyframes fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
        .archive-list {
            margin-top: 2rem;
        }

        .archive-list h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .archived-event {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section: Active Events List -->
        <div class="event-list">
            
            <nav>
                <a href="front.html">Home</a> |
                <a href="archive.php">Past Events</a>
            </nav>
            <h4>Active Countdown Events</h4>
            <div class="events">
                <?php
                $result = $conn->query("SELECT * FROM events WHERE date >= NOW() ORDER BY date ASC");
                $events = []; // Array to store events data for JavaScript
                while ($row = $result->fetch_assoc()) {
                    $events[] = $row; // Store each event
                    echo "<div class='event' style='border-left: 4px solid {$row['color_scheme']};'>";
                    echo "<h3><a href='view_event.php?id={$row['id']}'>{$row['emoji']} {$row['name']}</a></h3>";
                    echo "<p>Date: {$row['date']}</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Right Section: Add New Event Form -->
        <div class="event-form">
            <h2>Add New Event</h2>
            <form action="add_event.php" method="post">
                <input type="text" name="name" placeholder="Event Name" required>
                <input type="datetime-local" name="date" required>
                <input type="color" name="color" placeholder="Color Scheme" value="#ffffff" required>
                <input type="text" name="emoji" placeholder="Emoji" required>
                <button type="submit">Add Event</button>
            </form>
        </div>
    </div>

    <!-- Celebration Pop-Up -->
    <div class="popup-overlay" id="popup-overlay">
        <div class="popup-content">
            <h2>🎉 Celebration Time! 🎉</h2>
            <p>The event has started! Enjoy the celebration!</p>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
        const events = <?php echo json_encode($events); ?>;
        
        // Function to show celebration popup and emoji animation
        function showEventNotification(eventName) {
            document.getElementById("popup-overlay").style.display = "flex";
            startEmojiPopper();

            if (Notification.permission === "granted") {
                new Notification(eventName + " has started!");
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        new Notification(eventName + " has started!");
                    }
                });
            }
        }
        

        // Function to close the popup
        function closePopup() {
            document.getElementById("popup-overlay").style.display = "none";
    const emojis = document.querySelectorAll(".emoji-popper");
    emojis.forEach(emoji => {
        emoji.remove(); 
    });
        }

        
        function startEmojiPopper() {
            const emojiOptions = ["🎉", "🎈", "🥳", "🎊", "✨"];
            const maxEmojis = 50;

            for (let i = 0; i < maxEmojis; i++) {
                const emoji = document.createElement("div");
                emoji.classList.add("emoji-popper");
                emoji.innerText = emojiOptions[Math.floor(Math.random() * emojiOptions.length)];
                emoji.style.left = Math.random() * 100 + "vw";
                emoji.style.animationDuration = Math.random() * 2 + 3 + "s";
                emoji.style.fontSize = Math.random() * 10 + 20 + "px";
                document.body.appendChild(emoji);

               
                emoji.addEventListener("animationend", () => {
                    emoji.remove();
                });
            }
        }

        // Check events to trigger notification
        function checkEventStart() {
            const now = new Date().getTime();
            
            events.forEach(event => {
                const eventDate = new Date(event.date).getTime();
                if (eventDate <= now && !event.notified) {
                    showEventNotification(event.emoji + " " + event.name);
                    event.notified = true; // Mark as notified to avoid duplicate alerts
                }
            });
        }

        
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }

        // Run check every second
        setInterval(checkEventStart, 1000);
    </script>
</body>
</html>
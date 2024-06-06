<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinite-Planet</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Montserrat', sans-serif;
            overflow: hidden;
        }
        .video-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            transition: transform 0.5s ease;
        }
        .video-bg:hover {
            transform: scale(1.1);
        }
        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #FF7828;
        }
        .time {
            font-size: 6em;
            color: #e0e0e0;
            text-shadow: 9px 2px #000000;
            padding: 20px;
            border-radius: 10px;
        }
        .ip-address {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5em;
            color: #3a843c;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px;
            border-radius: 5px;
        }

        /* Media Query for Mobile View */
        @media (max-width: 768px) {
            .time {
                font-size: 4em; /* Adjusted font size for better mobile view */
            }
            .ip-address {
                font-size: 1em; /* Adjust font size for better mobile view */
                padding: 5px; /* Adjust padding for better mobile view */
            }
        }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop>
        <source src="1.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="content">
        <div class="time" id="time"></div>
    </div>
    <div class="ip-address" id="ip"></div>

    <script>
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const milliseconds = String(now.getMilliseconds()).padStart(3, '0');
            document.getElementById('time').innerText = `${hours}:${minutes}:${seconds}.${milliseconds}`;
        }

        setInterval(updateTime, 1);
        updateTime();

        // Fetch the user's IP address
        async function fetchIP() {
            try {
                const response = await fetch('https://api.ipify.org?format=json');
                const data = await response.json();
                document.getElementById('ip').innerText = `IP: ${data.ip}`;
            } catch (error) {
                console.error('Error fetching IP:', error);
            }
        }

        fetchIP();
    </script>
</body>
</html>

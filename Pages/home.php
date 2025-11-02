<?php
$connection = mysqli_connect("localhost", "root", "", "r&r_dbs");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page | R & R HARDWARE AND CONSTRUCTION SUPPLIES</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #1f2937;
        }

        header {
            background-color: #374151; /* Steel gray */
            color: #facc15; /* Yellow-gold accent */
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        .nav-bar {
            display: flex;
            justify-content: center;
            background-color: #1f2937;
            padding: 10px;
            flex-wrap: wrap;
        }

        .nav-bar button {
            background-color: #facc15;
            color: #111827;
            border: none;
            padding: 12px 20px;
            margin: 5px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-bar button:hover {
            background-color: #eab308;
        }

        main {
            text-align: center;
            padding: 60px 20px;
        }

        main h2 {
            margin-top: 10px;
            color: #374151;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            padding: 20px;
            background-color: #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .nav-bar {
                flex-direction: column;
                align-items: center;
            }

            .nav-bar button {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>R & R HARDWARE AND CONSTRUCTION SUPPLIES</h1>
</header>

<div class="nav-bar">
    <button onclick="location.href='home.php'">🏠 Home</button>
    <button onclick="location.href='cuslist.php'">👥 Customer List</button>
    <button onclick="location.href='cusrec.php'">🧾 Customer Receipts</button>
    <button onclick="location.href='daysales.php'">📊 Day Sales</button>
</div>

<main>
    <marquee><h1>Good Day!</h1>
    <h2>Welcome to R & R Hardware and Construction Supplies' Management System</h2></marquee>
</main>

<footer>
    &copy; <?php echo date('Y'); ?> R & R Hardware and Construction Supplies. All rights reserved.
</footer>

</body>
</html>

<?php
mysqli_close($connection);
?>
